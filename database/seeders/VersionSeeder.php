<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\Version;
use Illuminate\Database\Seeder;

class VersionSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'default')->first();
        if ($tenant) {
            Tenant::setCurrent($tenant);
        }

        if (Version::withoutGlobalScope('tenant')->where('tenant_id', $tenant?->id)->exists()) {
            return;
        }

        Version::create([
            'version_number' => '1.0.0',
            'release_date' => now()->subMonths(2)->toDateString(),
            'description' => 'Première version publique (exemple)',
            'content' => '<ul><li>Lancement du produit démo</li><li>Page changelog</li><li>Espace admin</li></ul>',
        ]);

        Version::create([
            'version_number' => '1.1.0',
            'release_date' => now()->subWeeks(3)->toDateString(),
            'description' => 'Améliorations UX (exemple)',
            'content' => '<ul><li>Meilleure navigation</li><li>Corrections mineures</li></ul>',
        ]);
    }
}
