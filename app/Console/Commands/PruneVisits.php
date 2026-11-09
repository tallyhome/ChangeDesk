<?php

namespace App\Console\Commands;

use App\Models\Visit;
use Illuminate\Console\Command;

class PruneVisits extends Command
{
    protected $signature = 'visits:prune';

    protected $description = 'Supprime les visites plus anciennes que la durée de conservation';

    public function handle(): int
    {
        $days = (int) config('security.visit_retention_days');
        if ($days < 1) {
            $this->info('Conservation illimitée, rien à supprimer.');

            return self::SUCCESS;
        }

        $deleted = Visit::withoutGlobalScope('tenant')
            ->where('created_at', '<', now()->subDays($days))
            ->delete();

        $this->info($deleted.' visite(s) supprimée(s).');

        return self::SUCCESS;
    }
}
