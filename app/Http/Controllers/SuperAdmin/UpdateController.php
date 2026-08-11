<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\GithubUpdater;
use App\Support\GithubUpdateAuth;
use Illuminate\Http\Request;
use Throwable;

class UpdateController extends Controller
{
    public function index(GithubUpdater $updater)
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
        ]);
    }

    public function apply(Request $request, GithubUpdater $updater)
    {
        $request->validate([
            'confirm' => ['accepted'],
        ]);

        try {
            $release = $updater->latestRelease();
            if (! $updater->isUpdateAvailable($release)) {
                return back()->with('error', 'Aucune mise à jour disponible.');
            }

            $result = $updater->apply($release);
            AuditLog::record('platform.updated', null, $result);

            $msg = "Mise à jour {$result['from']} → {$result['to']} appliquée.";
            if (! $result['migrated']) {
                $msg .= ' Relancez manuellement : php artisan migrate --force';
            }

            return redirect()->route('superadmin.updates.index')->with('success', $msg);
        } catch (Throwable $e) {
            return back()->with('error', 'Échec MAJ : '.$e->getMessage());
        }
    }
}
