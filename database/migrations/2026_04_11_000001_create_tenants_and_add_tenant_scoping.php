<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('custom_domain')->nullable()->unique();
            $table->string('domain_status')->default('none'); // none|pending|verified
            $table->string('domain_verification_token')->nullable();
            $table->json('branding')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('client')->after('password');
            $table->foreignId('tenant_id')->nullable()->after('role')->constrained()->nullOnDelete();
        });

        $tenantTables = [
            'versions',
            'todo_items',
            'bug_reports',
            'wiki_categories',
            'wiki_articles',
            'pages',
            'settings',
            'visits',
        ];

        foreach ($tenantTables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (! Schema::hasColumn($tableName, 'tenant_id')) {
                    $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->nullOnDelete();
                    $table->index('tenant_id');
                }
            });
        }

        if (Schema::hasTable('settings')) {
            try {
                Schema::table('settings', function (Blueprint $table) {
                    $table->dropUnique(['key']);
                });
            } catch (\Throwable $e) {
            }

            Schema::table('settings', function (Blueprint $table) {
                $table->unique(['tenant_id', 'key']);
            });
        }

        if (Schema::hasTable('wiki_categories')) {
            try {
                Schema::table('wiki_categories', function (Blueprint $table) {
                    $table->dropUnique(['slug']);
                });
            } catch (\Throwable $e) {
            }

            Schema::table('wiki_categories', function (Blueprint $table) {
                $table->unique(['tenant_id', 'slug']);
            });
        }

        if (Schema::hasTable('wiki_articles')) {
            try {
                Schema::table('wiki_articles', function (Blueprint $table) {
                    $table->dropUnique(['slug']);
                });
            } catch (\Throwable $e) {
            }

            Schema::table('wiki_articles', function (Blueprint $table) {
                $table->unique(['tenant_id', 'slug']);
            });
        }

        $legacyTenantId = DB::table('tenants')->insertGetId([
            'name' => 'Default Project',
            'slug' => 'default',
            'custom_domain' => null,
            'domain_status' => 'none',
            'domain_verification_token' => Str::random(32),
            'branding' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($tenantTables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'tenant_id')) {
                DB::table($tableName)->whereNull('tenant_id')->update(['tenant_id' => $legacyTenantId]);
            }
        }

        DB::table('users')->whereNull('tenant_id')->update([
            'tenant_id' => $legacyTenantId,
            'role' => 'client',
        ]);

        $superadminExists = DB::table('users')->where('email', 'superadmin@changelog.fr')->exists();
        if (! $superadminExists) {
            DB::table('users')->insert([
                'name' => 'Super Admin',
                'email' => 'superadmin@changelog.fr',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'tenant_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('users')->where('email', 'superadmin@changelog.fr')->update([
                'role' => 'superadmin',
                'tenant_id' => null,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tenant_id');
            $table->dropColumn('role');
        });

        $tenantTables = [
            'versions',
            'todo_items',
            'bug_reports',
            'wiki_categories',
            'wiki_articles',
            'pages',
            'settings',
            'visits',
        ];

        foreach ($tenantTables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'tenant_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropConstrainedForeignId('tenant_id');
                });
            }
        }

        Schema::dropIfExists('tenants');
    }
};
