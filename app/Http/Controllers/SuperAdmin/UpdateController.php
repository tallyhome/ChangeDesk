<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\GithubUpdater;
use App\Services\UpdateProgress;
use App\Support\GithubUpdateAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Throwable;

class UpdateController extends Controller
{
    public function index(GithubUpdater $updater, UpdateProgress $progress)
    {
        $current = $updater->currentVersion();
        $release = null;
        $error = null;
        $available = false;
        $fresh = request()->boolean('fresh');

        try {
            $release = $updater->latestRelease($fresh);
            $available = $updater->isUpdateAvailable($release);
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }

        $state = $progress->read();
        $showSuccessOnce = ($state['status'] ?? '') === 'done';
        if ($showSuccessOnce) {
            $progress->acknowledge();
        }

        return view('superadmin.updates.index', [
            'current' => $current,
            'release' => $release,
            'available' => $available,
            'error' => $error,
            'repo' => GithubUpdateAuth::REPO,
            'hasToken' => GithubUpdateAuth::hasToken(),
            'progress' => $state,
            'showSuccessOnce' => $showSuccessOnce,
        ]);
    }

    public function progress(UpdateProgress $progress)
    {
        return response()->json($progress->read());
    }

    public function apply(Request $request, GithubUpdater $updater, UpdateProgress $progress)
    {
        $request->validate([
            'confirm' => ['accepted'],
        ]);

        $state = $progress->read();
        if (($state['status'] ?? '') === 'running') {
            return response()->json(['ok' => true, 'started' => true, 'already' => true]);
        }

        try {
            $release = $updater->latestRelease(true);
            if (! $updater->isUpdateAvailable($release)) {
                return response()->json(['ok' => false, 'error' => 'Aucune mise à jour disponible.'], 422);
            }

            $tag = (string) ($release['tag'] ?? '');
            $from = $updater->currentVersion();
            $progress->start($from, $tag);
            $progress->step(3, 'Démarrage', 'Lancement du processus d’installation…');

            // Processus détaché → le polling peut avancer (évite le blocage 1 worker PHP)
            if ($this->spawnBackgroundUpdate($tag)) {
                return response()->json([
                    'ok' => true,
                    'started' => true,
                    'async' => true,
                    'message' => 'Installation démarrée — suivez la barre de progression.',
                ]);
            }

            // Fallback sync si exec/popen indisponibles
            if (function_exists('session_write_close')) {
                session()->save();
                session_write_close();
            }

            set_time_limit(600);
            $result = $updater->apply($release);
            AuditLog::record('platform.updated', null, $result);

            return response()->json([
                'ok' => true,
                'started' => false,
                'async' => false,
                'message' => "Mise à jour {$result['from']} → {$result['to']} appliquée.",
                'result' => $result,
            ]);
        } catch (Throwable $e) {
            $progress->fail($e->getMessage());

            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    protected function spawnBackgroundUpdate(string $tag): bool
    {
        $php = PHP_BINARY ?: 'php';
        $artisan = base_path('artisan');
        $log = storage_path('logs/update-apply.log');
        File::ensureDirectoryExists(dirname($log));

        $cmd = sprintf(
            '%s %s evolora:apply-update %s',
            escapeshellarg($php),
            escapeshellarg($artisan),
            escapeshellarg($tag)
        );

        try {
            if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
                if (! function_exists('popen')) {
                    return false;
                }
                // Guillemets vides = titre fenêtre requis par "start"
                $win = 'start /B "" '.$cmd.' >> '.escapeshellarg($log).' 2>&1';
                pclose(popen($win, 'r'));

                return true;
            }

            if (! function_exists('exec')) {
                return false;
            }

            exec('nohup '.$cmd.' >> '.escapeshellarg($log).' 2>&1 &');

            return true;
        } catch (Throwable) {
            return false;
        }
    }
}
