<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\GithubUpdater;
use App\Services\UpdateProgress;
use App\Support\GithubUpdateAuth;
use Illuminate\Http\Request;
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

        return view('superadmin.updates.index', [
            'current' => $current,
            'release' => $release,
            'available' => $available,
            'error' => $error,
            'repo' => GithubUpdateAuth::REPO,
            'hasToken' => GithubUpdateAuth::hasToken(),
            'progress' => $progress->read(),
        ]);
    }

    public function progress(UpdateProgress $progress)
    {
        return response()->json($progress->read());
    }

    public function apply(Request $request, GithubUpdater $updater)
    {
        $request->validate([
            'confirm' => ['accepted'],
        ]);

        // Libère le lock de session pour que le polling progress fonctionne en parallèle
        if (function_exists('session_write_close')) {
            session()->save();
            session_write_close();
        }

        try {
            $release = $updater->latestRelease(true);
            if (! $updater->isUpdateAvailable($release)) {
                return response()->json(['ok' => false, 'error' => 'Aucune mise à jour disponible.'], 422);
            }

            set_time_limit(600);
            $result = $updater->apply($release);
            AuditLog::record('platform.updated', null, $result);

            return response()->json([
                'ok' => true,
                'message' => "Mise à jour {$result['from']} → {$result['to']} appliquée. Migrations et caches exécutés automatiquement.",
                'result' => $result,
            ]);
        } catch (Throwable $e) {
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
