<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PageSeeder::class,
            AdminSeeder::class,
            VersionSeeder::class, // Ajout du seeder de versions
            TodoItemSeeder::class,
            BugReportSeeder::class,
        ]);
    }
}
