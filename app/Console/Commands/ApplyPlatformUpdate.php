<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Services\GithubUpdater;
use Illuminate\Console\Command;
use Throwable;

class ApplyPlatformUpdate extends Command
{
    protected $signature = 'evolora:apply-update {tag? : Tag GitHub à installer (sinon dernière release)}';

    protected $description = 'Applique une mise à jour plateforme en arrière-plan (progression via progress.json)';

    public function __construct()
    {
        parent::__construct();
        $this->setAliases(['chanlog:apply-update']);
    }

    public function handle(GithubUpdater $updater): int
    {
        try {
            $release = $updater->latestRelease(true);
            $tag = $this->argument('tag');
            if ($tag && ($release['tag'] ?? null) !== $tag) {
                // Si le tag demandé diffère du cache, on force quand même la dernière release fraîche
                $release = $updater->latestRelease(true);
            }

            if (! $updater->isUpdateAvailable($release)) {
                $this->warn('Aucune mise à jour disponible.');

                return self::SUCCESS;
            }

            $result = $updater->apply($release);
            AuditLog::record('platform.updated', null, $result);
            $this->info("OK {$result['from']} → {$result['to']}");

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
