<?php

use App\Http\Controllers\Admin\AdminWikiController;
use App\Http\Controllers\Admin\AppearanceController;
use App\Http\Controllers\Admin\BillingController as AdminBillingController;
use App\Http\Controllers\Admin\BugReportController;
use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\Admin\ImageUploadController;
use App\Http\Controllers\Admin\ProjectOnboardingController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TodoItemController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImpersonationController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ThemePreviewController;
use App\Http\Controllers\SuperAdmin\AuditLogController;
use App\Http\Controllers\SuperAdmin\BillingController as SuperAdminBillingController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\LandingThemeController as SuperAdminLandingThemeController;
use App\Http\Controllers\SuperAdmin\PlanController as SuperAdminPlanController;
use App\Http\Controllers\SuperAdmin\TenantController as SuperAdminTenantController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\UpdateController as SuperAdminUpdateController;
use App\Http\Controllers\VersionController;
use App\Http\Controllers\Webhooks\PayPalWebhookController;
use App\Http\Controllers\Webhooks\StripeWebhookController;
use App\Http\Controllers\WikiController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/theme-preview/{theme}', ThemePreviewController::class)->name('theme.preview');

Route::prefix('install')->name('install.')->group(function () {
    Route::get('/', [InstallController::class, 'welcome'])->name('welcome');
    Route::get('/requirements', [InstallController::class, 'requirements'])->name('requirements');
    Route::get('/database', [InstallController::class, 'databaseForm'])->name('database');
    Route::post('/database', [InstallController::class, 'databaseStore'])->name('database.store');
    Route::get('/done', [InstallController::class, 'done'])->name('done');
});

Route::post('/webhooks/stripe', StripeWebhookController::class)->name('webhooks.stripe');
Route::post('/webhooks/paypal', PayPalWebhookController::class)->name('webhooks.paypal');

// Login accessible aussi depuis un sous-domaine tenant (puis redirect admin central)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware(['central', 'guest'])->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware(['central', 'auth'])->group(function () {
    Route::post('/impersonation/leave', [ImpersonationController::class, 'leave'])->name('impersonation.leave');
});

Route::middleware(['tenant.host', 'tenant.notSuspended'])->group(function () {
    Route::get('/changelog', [PageController::class, 'changelog'])->name('changelog');
    Route::get('/terms', [PageController::class, 'terms'])->name('terms');
    Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');

    Route::middleware('module:todolist')->group(function () {
        Route::get('/todolist', [PageController::class, 'todolist'])->name('todolist');
    });

    Route::middleware('module:bugs')->group(function () {
        Route::get('/bug-report', [PageController::class, 'bugReport'])->name('bug-report');
        Route::post('/bug-report', [PageController::class, 'storeBugReport'])->name('bug-report.store');
        Route::get('/bug-report/{id}', [PageController::class, 'showBugReport'])->name('bug-report.show');
    });

    Route::middleware('module:wiki')->prefix('wiki')->group(function () {
        Route::get('/', [WikiController::class, 'index'])->name('wiki');
        Route::get('/search', [WikiController::class, 'search'])->name('wiki.search');
        Route::get('/category/{slug}', [WikiController::class, 'category'])->name('wiki.category');
        Route::get('/{slug}', [WikiController::class, 'show'])->name('wiki.show');
    });
});

Route::middleware(['central', 'auth', 'client'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/onboarding', [ProjectOnboardingController::class, 'create'])->name('onboarding.create');
        Route::post('/onboarding', [ProjectOnboardingController::class, 'store'])->name('onboarding.store');
    });

Route::middleware(['central', 'auth', 'client', 'tenant.fromAuth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/domain', [DomainController::class, 'edit'])->name('domain.edit');
        Route::put('/domain', [DomainController::class, 'update'])->name('domain.update');
        Route::post('/domain/verify', [DomainController::class, 'verify'])->name('domain.verify');

        Route::get('/appearance', [AppearanceController::class, 'edit'])->name('appearance.edit');
        Route::put('/appearance', [AppearanceController::class, 'update'])->name('appearance.update');

        Route::get('/billing', [AdminBillingController::class, 'index'])->name('billing.index');
        Route::post('/billing/checkout', [AdminBillingController::class, 'checkout'])->name('billing.checkout');
        Route::get('/billing/success', [AdminBillingController::class, 'success'])->name('billing.success');

        Route::get('/visits', [\App\Http\Controllers\Admin\AdminVisitController::class, 'index'])->name('visits.index');
        Route::get('/visits/analysis', [\App\Http\Controllers\Admin\AdminVisitController::class, 'analysis'])->name('visits.analysis');
        Route::get('/visits/chart-data', [\App\Http\Controllers\Admin\AdminVisitController::class, 'getChartData'])->name('visits.chart-data');
        Route::get('/visits/active-visitors', [\App\Http\Controllers\Admin\AdminVisitController::class, 'getActiveVisitors'])->name('visits.active-visitors');

        Route::get('/profile', [\App\Http\Controllers\AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\AdminProfileController::class, 'update'])->name('profile.update');
        Route::get('/pages', [AdminController::class, 'index'])->name('pages.index');
        Route::get('/pages/{page}/edit', [AdminController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [AdminController::class, 'update'])->name('pages.update');

        Route::get('/changelog', [VersionController::class, 'index'])->name('changelog');
        Route::get('/changelog/create', [VersionController::class, 'create'])->name('changelog.create');
        Route::post('/changelog', [VersionController::class, 'store'])->name('changelog.store');
        Route::get('/changelog/{version}/edit', [VersionController::class, 'edit'])->name('changelog.edit');
        Route::put('/changelog/{version}', [VersionController::class, 'update'])->name('changelog.update');
        Route::delete('/changelog/{version}', [VersionController::class, 'destroy'])->name('changelog.destroy');
        Route::post('/changelog/toggle-status', [VersionController::class, 'toggleChangelogStatus'])->name('changelog.toggle-status');

        Route::post('/upload/image', [ImageUploadController::class, 'store'])->name('upload.image');

        Route::get('/todolist', [TodoItemController::class, 'index'])->name('todolist');
        Route::get('/todolist/create', [TodoItemController::class, 'create'])->name('todolist.create');
        Route::post('/todolist', [TodoItemController::class, 'store'])->name('todolist.store');
        Route::get('/todolist/{todoItem}/edit', [TodoItemController::class, 'edit'])->name('todolist.edit');
        Route::put('/todolist/{todoItem}', [TodoItemController::class, 'update'])->name('todolist.update');
        Route::delete('/todolist/{todoItem}', [TodoItemController::class, 'destroy'])->name('todolist.destroy');
        Route::post('/todolist/toggle-status', [TodoItemController::class, 'toggleTodoStatus'])->name('todolist.toggle-status');

        Route::get('/bug-reports', [BugReportController::class, 'index'])->name('bug_reports');
        Route::get('/bug-reports/create', [BugReportController::class, 'create'])->name('bug_reports.create');
        Route::post('/bug-reports', [BugReportController::class, 'store'])->name('bug_reports.store');
        Route::get('/bug-reports/{bugReport}/edit', [BugReportController::class, 'edit'])->name('bug_reports.edit');
        Route::put('/bug-reports/{bugReport}', [BugReportController::class, 'update'])->name('bug_reports.update');
        Route::delete('/bug-reports/{bugReport}', [BugReportController::class, 'destroy'])->name('bug_reports.destroy');
        Route::post('/bug-reports/toggle-status', [BugReportController::class, 'toggleBugReportStatus'])->name('bug_reports.toggle-status');
        Route::delete('/todos/{id}', [TodoItemController::class, 'destroy'])->name('todos.destroy');

        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/toggle', [SettingController::class, 'toggle'])->name('settings.toggle');

        Route::prefix('wiki')->name('wiki.')->group(function () {
            Route::get('/categories', [AdminWikiController::class, 'categories'])->name('categories.index');
            Route::get('/categories/create', [AdminWikiController::class, 'createCategory'])->name('categories.create');
            Route::post('/categories', [AdminWikiController::class, 'storeCategory'])->name('categories.store');
            Route::get('/categories/{category}/edit', [AdminWikiController::class, 'editCategory'])->name('categories.edit');
            Route::put('/categories/{category}', [AdminWikiController::class, 'updateCategory'])->name('categories.update');
            Route::delete('/categories/{category}', [AdminWikiController::class, 'destroyCategory'])->name('categories.destroy');

            Route::get('/', [AdminWikiController::class, 'index'])->name('index');
            Route::get('/create', [AdminWikiController::class, 'create'])->name('create');
            Route::post('/', [AdminWikiController::class, 'store'])->name('store');
            Route::post('/preview', [AdminWikiController::class, 'preview'])->name('preview');
            Route::post('/toggle-status', [AdminWikiController::class, 'toggleWikiStatus'])->name('toggle-status');

            Route::get('/settings', [AdminWikiController::class, 'settings'])->name('settings');
            Route::post('/settings/update', [AdminWikiController::class, 'updateSettings'])->name('settings.update');

            Route::get('/{article}/edit', [AdminWikiController::class, 'edit'])->name('edit');
            Route::put('/{article}', [AdminWikiController::class, 'update'])->name('update');
            Route::delete('/{article}', [AdminWikiController::class, 'destroy'])->name('destroy');
            Route::get('/{article}', [AdminWikiController::class, 'show'])->name('show');
            Route::post('/{article}/toggle-publication', [AdminWikiController::class, 'togglePublication'])->name('toggle-publication');
        });
    });

Route::middleware(['central', 'auth', 'superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/', SuperAdminDashboardController::class)->name('dashboard');

        Route::get('/tenants', [SuperAdminTenantController::class, 'index'])->name('tenants.index');
        Route::get('/tenants/{tenant}', [SuperAdminTenantController::class, 'show'])->name('tenants.show');
        Route::get('/tenants/{tenant}/edit', [SuperAdminTenantController::class, 'edit'])->name('tenants.edit');
        Route::put('/tenants/{tenant}', [SuperAdminTenantController::class, 'update'])->name('tenants.update');
        Route::post('/tenants/{tenant}/toggle', [SuperAdminTenantController::class, 'toggle'])->name('tenants.toggle');
        Route::post('/tenants/{tenant}/suspend', [SuperAdminTenantController::class, 'suspend'])->name('tenants.suspend');
        Route::post('/tenants/{tenant}/unsuspend', [SuperAdminTenantController::class, 'unsuspend'])->name('tenants.unsuspend');
        Route::post('/tenants/{tenant}/impersonate', [SuperAdminTenantController::class, 'impersonate'])->name('tenants.impersonate');

        Route::get('/users', [SuperAdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [SuperAdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [SuperAdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [SuperAdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [SuperAdminUserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/reset', [SuperAdminUserController::class, 'resetPassword'])->name('users.reset');
        Route::post('/users/{user}/toggle', [SuperAdminUserController::class, 'toggleActive'])->name('users.toggle');

        Route::get('/plans', [SuperAdminPlanController::class, 'index'])->name('plans.index');
        Route::get('/plans/create', [SuperAdminPlanController::class, 'create'])->name('plans.create');
        Route::post('/plans', [SuperAdminPlanController::class, 'store'])->name('plans.store');
        Route::get('/plans/{plan}/edit', [SuperAdminPlanController::class, 'edit'])->name('plans.edit');
        Route::put('/plans/{plan}', [SuperAdminPlanController::class, 'update'])->name('plans.update');
        Route::delete('/plans/{plan}', [SuperAdminPlanController::class, 'destroy'])->name('plans.destroy');

        Route::get('/billing', [SuperAdminBillingController::class, 'index'])->name('billing.index');
        Route::post('/billing/assign', [SuperAdminBillingController::class, 'assign'])->name('billing.assign');

        Route::get('/landing', [SuperAdminLandingThemeController::class, 'index'])->name('landing.index');
        Route::post('/landing', [SuperAdminLandingThemeController::class, 'update'])->name('landing.update');
        Route::get('/landing/preview/{theme}', [SuperAdminLandingThemeController::class, 'preview'])->name('landing.preview');

        Route::get('/audit', [AuditLogController::class, 'index'])->name('audit.index');

        Route::get('/updates', [SuperAdminUpdateController::class, 'index'])->name('updates.index');
        Route::get('/updates/progress', [SuperAdminUpdateController::class, 'progress'])->name('updates.progress');
        Route::post('/updates/apply', [SuperAdminUpdateController::class, 'apply'])->name('updates.apply');

        Route::get('/backups', [\App\Http\Controllers\Admin\DatabaseBackupController::class, 'index'])->name('backups.index');
        Route::post('/backups', [\App\Http\Controllers\Admin\DatabaseBackupController::class, 'create'])->name('backups.create');
        Route::get('/backups/{filename}/download', [\App\Http\Controllers\Admin\DatabaseBackupController::class, 'download'])->name('backups.download');
        Route::post('/backups/{filename}/restore', [\App\Http\Controllers\Admin\DatabaseBackupController::class, 'restore'])->name('backups.restore');
        Route::delete('/backups/{filename}', [\App\Http\Controllers\Admin\DatabaseBackupController::class, 'destroy'])->name('backups.destroy');
    });
