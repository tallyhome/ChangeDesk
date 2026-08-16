<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetSuperadminPassword extends Command
{
    protected $signature = 'chanlog:reset-superadmin
                            {email=superadmin@chanlog.app : Email du superadmin}
                            {--password=password : Nouveau mot de passe}';

    protected $description = 'Réinitialise le mot de passe superadmin (secours si la connexion échoue)';

    public function handle(): int
    {
        $email = strtolower(trim((string) $this->argument('email')));
        $password = (string) $this->option('password');

        $user = User::where('email', $email)->first();

        if (! $user) {
            // Ancien email legacy
            $legacy = User::where('email', 'superadmin@changelog.fr')->first();
            if ($legacy) {
                $legacy->update([
                    'email' => $email,
                    'name' => 'Super Admin',
                    'password' => $password,
                    'role' => User::ROLE_SUPERADMIN,
                    'tenant_id' => null,
                    'is_active' => true,
                ]);
                $user = $legacy->fresh();
                $this->warn('Compte legacy superadmin@changelog.fr migré vers '.$email);
            }
        }

        if (! $user) {
            $user = User::create([
                'name' => 'Super Admin',
                'email' => $email,
                'password' => $password,
                'role' => User::ROLE_SUPERADMIN,
                'tenant_id' => null,
                'is_active' => true,
            ]);
            $this->info('Compte superadmin créé.');
        } else {
            $user->update([
                'password' => $password,
                'role' => User::ROLE_SUPERADMIN,
                'tenant_id' => null,
                'is_active' => true,
            ]);
            $this->info('Mot de passe superadmin réinitialisé.');
        }

        $this->line('');
        $this->line('Email : '.$email);
        $this->line('Mot de passe : '.$password);
        $this->line('Connexion : '.rtrim(config('app.url'), '/').'/login');

        return self::SUCCESS;
    }
}
