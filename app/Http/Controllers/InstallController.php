<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Billing\StripeBilling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InstallController extends Controller
{
    protected function installedPath(): string
    {
        return storage_path('app/installed');
    }

    protected function ensureNotInstalled()
    {
        if (File::exists($this->installedPath())) {
            abort(404);
        }
    }

    public function welcome()
    {
        $this->ensureNotInstalled();

        return view('install.welcome');
    }

    public function requirements()
    {
        $this->ensureNotInstalled();

        $checks = [
            'PHP >= 8.2' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'PDO' => extension_loaded('pdo'),
            'PDO MySQL' => extension_loaded('pdo_mysql'),
            'Mbstring' => extension_loaded('mbstring'),
            'OpenSSL' => extension_loaded('openssl'),
            'Tokenizer' => extension_loaded('tokenizer'),
            'XML' => extension_loaded('xml'),
            'Ctype' => extension_loaded('ctype'),
            'JSON' => extension_loaded('json'),
            'BCMath' => extension_loaded('bcmath'),
            'Fileinfo' => extension_loaded('fileinfo'),
            'Curl' => extension_loaded('curl'),
            'storage/ writable' => is_writable(storage_path()),
            'bootstrap/cache writable' => is_writable(base_path('bootstrap/cache')),
            '.env writable or creatable' => is_writable(base_path()) || (file_exists(base_path('.env')) && is_writable(base_path('.env'))),
        ];

        $ok = ! in_array(false, $checks, true);

        return view('install.requirements', compact('checks', 'ok'));
    }

    public function databaseForm()
    {
        $this->ensureNotInstalled();

        return view('install.database');
    }

    public function databaseStore(Request $request)
    {
        $this->ensureNotInstalled();

        $data = $request->validate([
            'app_url' => ['required', 'url'],
            'central_domain' => ['required', 'string', 'max:255'],
            'db_host' => ['required', 'string'],
            'db_port' => ['required', 'string'],
            'db_database' => ['required', 'string'],
            'db_username' => ['required', 'string'],
            'db_password' => ['nullable', 'string'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email'],
            'admin_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $appUrl = rtrim($data['app_url'], '/');
        $domain = strtolower(trim($data['central_domain']));

        try {
            config([
                'database.connections.mysql.host' => $data['db_host'],
                'database.connections.mysql.port' => $data['db_port'],
                'database.connections.mysql.database' => $data['db_database'],
                'database.connections.mysql.username' => $data['db_username'],
                'database.connections.mysql.password' => $data['db_password'] ?? '',
            ]);
            DB::purge('mysql');
            DB::connection('mysql')->getPdo();
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors(['db_database' => 'Connexion MySQL impossible : '.$e->getMessage()]);
        }

        $env = $this->buildEnv($data, $appUrl, $domain);
        File::put(base_path('.env'), $env);

        try {
            Artisan::call('key:generate', ['--force' => true]);
            Artisan::call('config:clear');

            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--class' => 'PlanSeeder', '--force' => true]);
            Artisan::call('db:seed', ['--class' => 'AdminSeeder', '--force' => true]);
            try {
                Artisan::call('storage:link');
            } catch (\Throwable $e) {
                // Lien déjà existant : OK
            }

            $free = Plan::where('slug', 'free')->first();

            User::updateOrCreate(
                ['email' => $data['admin_email']],
                [
                    'name' => $data['admin_name'],
                    'password' => $data['admin_password'],
                    'role' => User::ROLE_SUPERADMIN,
                    'tenant_id' => null,
                    'is_active' => true,
                ]
            );

            if ($free && ! Tenant::where('slug', 'default')->exists()) {
                $tenant = Tenant::create([
                    'name' => 'Default Project',
                    'slug' => 'default',
                    'plan_id' => $free->id,
                    'visual_theme' => 'classic',
                    'is_active' => true,
                    'domain_status' => 'none',
                    'domain_verification_token' => Str::random(40),
                ]);
                app(StripeBilling::class)->activate($tenant, $free, 'manual', 'manual-install');
            }

            File::ensureDirectoryExists(dirname($this->installedPath()));
            File::put($this->installedPath(), now()->toDateTimeString());
            Artisan::call('config:cache');
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors([
                'db_database' => 'Installation interrompue : '.$e->getMessage(),
            ]);
        }

        return redirect()->route('install.done');
    }

    public function done()
    {
        return view('install.done');
    }

    protected function buildEnv(array $data, string $appUrl, string $domain): string
    {
        $pass = $data['db_password'] ?? '';
        $key = 'base64:'.base64_encode(random_bytes(32));

        return <<<ENV
APP_NAME=Evolora
APP_ENV=production
APP_KEY={$key}
APP_DEBUG=false
APP_URL={$appUrl}

CENTRAL_DOMAIN={$domain}
CENTRAL_DOMAINS={$domain},www.{$domain}
TENANCY_CNAME_TARGET=cname.{$domain}

APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr
APP_FAKER_LOCALE=fr_FR

APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12
LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST={$data['db_host']}
DB_PORT={$data['db_port']}
DB_DATABASE={$data['db_database']}
DB_USERNAME={$data['db_username']}
DB_PASSWORD="{$pass}"

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_DOMAIN=null
QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=local
BROADCAST_CONNECTION=log

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@{$domain}"
MAIL_FROM_NAME="Evolora"

STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=
STRIPE_PRICE_PRO=
STRIPE_PRICE_BUSINESS=
PAYPAL_MODE=sandbox
PAYPAL_CLIENT_ID=
PAYPAL_CLIENT_SECRET=
PAYPAL_WEBHOOK_ID=
PAYPAL_PLAN_PRO=
PAYPAL_PLAN_BUSINESS=
BILLING_CURRENCY=eur
ENV;
    }
}
