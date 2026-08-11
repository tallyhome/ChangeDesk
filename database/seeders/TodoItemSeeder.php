<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\TodoItem;
use Illuminate\Database\Seeder;

class TodoItemSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'default')->first();
        if ($tenant) {
            Tenant::setCurrent($tenant);
        }

        if (TodoItem::withoutGlobalScope('tenant')->where('tenant_id', $tenant?->id)->exists()) {
            return;
        }

        TodoItem::create([
            'title' => 'Mode sombre public',
            'description' => 'Exemple de fonctionnalité à venir — à supprimer ou modifier.',
            'status' => 'in_progress',
            'progress' => 60,
            'color' => 'primary',
            'expected_date' => now()->addDays(20)->toDateString(),
        ]);

        TodoItem::create([
            'title' => 'Export PDF des releases',
            'description' => 'Exemple roadmap — contenu fictif.',
            'status' => 'planned',
            'progress' => 10,
            'color' => 'secondary',
            'expected_date' => now()->addDays(45)->toDateString(),
        ]);
    }
}
