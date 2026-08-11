<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->unsignedInteger('price_cents')->default(0);
            $table->string('interval')->default('month'); // month|year
            $table->string('stripe_price_id')->nullable();
            $table->string('paypal_plan_id')->nullable();
            $table->json('features')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->string('visual_theme')->default('classic')->after('branding');
            $table->timestamp('suspended_at')->nullable()->after('is_active');
            $table->text('suspension_reason')->nullable()->after('suspended_at');
            $table->foreignId('plan_id')->nullable()->after('suspension_reason')->constrained('plans')->nullOnDelete();
            $table->json('feature_overrides')->nullable()->after('plan_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('role');
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('active'); // active|trialing|past_due|canceled|incomplete
            $table->string('provider')->default('manual'); // stripe|paypal|manual
            $table->string('provider_subscription_id')->nullable()->index();
            $table->string('provider_customer_id')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('current_period_start')->nullable();
            $table->timestamp('current_period_end')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();
            $table->string('provider'); // stripe|paypal|manual
            $table->string('provider_payment_id')->nullable()->index();
            $table->unsignedInteger('amount_cents');
            $table->string('currency', 3)->default('eur');
            $table->string('status')->default('pending'); // pending|paid|failed|refunded
            $table->string('invoice_url')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->json('payload')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->index(['action', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('subscriptions');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropConstrainedForeignId('plan_id');
            $table->dropColumn(['visual_theme', 'suspended_at', 'suspension_reason', 'feature_overrides']);
        });

        Schema::dropIfExists('plans');
    }
};
