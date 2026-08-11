<?php

use App\Http\Controllers\Admin\AdminWikiController;
use App\Http\Controllers\Admin\BugReportController;
use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\Admin\ImageUploadController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TodoItemController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SuperAdmin\TenantController as SuperAdminTenantController;
use App\Http\Controllers\VersionController;
use App\Http\Controllers\WikiController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

// Auth + inscription (domaine central uniquement)
Route::middleware(['central', 'guest'])->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::middleware(['central', 'auth'])->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Contenu public du tenant (sous-domaine ou domaine custom vérifié)
Route::middleware(['tenant.host'])->group(function () {
    Route::get('/changelog', [PageController::class, 'changelog'])->name('changelog');
    Route::get('/terms', [PageController::class, 'terms'])->name('terms');
    Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
    Route::get('/todolist', [PageController::class, 'todolist'])->name('todolist');
    Route::get('/bug-report', [PageController::class, 'bugReport'])->name('bug-report');
    Route::post('/bug-report', [PageController::class, 'storeBugReport'])->name('bug-report.store');
    Route::get('/bug-report/{id}', [PageController::class, 'showBugReport'])->name('bug-report.show');

    Route::prefix('wiki')->group(function () {
        Route::get('/', [WikiController::class, 'index'])->name('wiki');
        Route::get('/search', [WikiController::class, 'search'])->name('wiki.search');
        Route::get('/category/{slug}', [WikiController::class, 'category'])->name('wiki.category');
        Route::get('/{slug}', [WikiController::class, 'show'])->name('wiki.show');
    });
});

// Admin client (domaine central + tenant depuis l'utilisateur connecté)
Route::middleware(['central', 'auth', 'client', 'tenant.fromAuth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/domain', [DomainController::class, 'edit'])->name('domain.edit');
        Route::put('/domain', [DomainController::class, 'update'])->name('domain.update');
        Route::post('/domain/verify', [DomainController::class, 'verify'])->name('domain.verify');

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

// Superadmin plateforme
Route::middleware(['central', 'auth', 'superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/', [SuperAdminTenantController::class, 'index'])->name('tenants.index');
        Route::get('/tenants/{tenant}', [SuperAdminTenantController::class, 'show'])->name('tenants.show');
        Route::post('/tenants/{tenant}/toggle', [SuperAdminTenantController::class, 'toggle'])->name('tenants.toggle');

        // Backups globaux (DB partagée) — réservés au superadmin
        Route::get('/backups', [\App\Http\Controllers\Admin\DatabaseBackupController::class, 'index'])->name('backups.index');
        Route::post('/backups', [\App\Http\Controllers\Admin\DatabaseBackupController::class, 'create'])->name('backups.create');
        Route::get('/backups/{filename}/download', [\App\Http\Controllers\Admin\DatabaseBackupController::class, 'download'])->name('backups.download');
        Route::post('/backups/{filename}/restore', [\App\Http\Controllers\Admin\DatabaseBackupController::class, 'restore'])->name('backups.restore');
        Route::delete('/backups/{filename}', [\App\Http\Controllers\Admin\DatabaseBackupController::class, 'destroy'])->name('backups.destroy');
    });
