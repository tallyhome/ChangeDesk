<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetSuperadminPassword extends Command
{
    protected $signature = 'evolora:reset-superadmin
                            {email=superadmin@evolora.app : Email du superadmin}
                            {--password=password : Nouveau mot de passe}';

    protected $description = 'Réinitialise le mot de passe superadmin (secours si la connexion échoue)';

    public function __construct()
    {
        parent::__construct();
        $this->setAliases(['chanlog:reset-superadmin']);
    }

    public function handle(): int
    {
        $email = strtolower(trim((string) $this->argument('email')));
        $password = (string) $this->option('password');

        $user = User::where('email', $email)->first();

        if (! $user) {
            // Ancien email legacy
            $legacy = User::whereIn('email', [
                'superadmin@changelog.fr',
                'superadmin@chanlog.app',
            ])->first();
            if ($legacy) {
                $from = $legacy->email;
                $legacy->update([
                    'email' => $email,
                    'name' => 'Super Admin',
                    'password' => $password,
                    'role' => User::ROLE_SUPERADMIN,
                    'tenant_id' => null,
                    'is_active' => true,
                ]);
                $user = $legacy->fresh();
                $this->warn('Compte legacy '.$from.' migré vers '.$email);
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
