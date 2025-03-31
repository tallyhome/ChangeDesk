<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\VersionController;
use App\Http\Controllers\Admin\ImageUploadController;
use App\Http\Controllers\Admin\TodoItemController;
use App\Http\Controllers\Admin\BugReportController;
use App\Http\Controllers\WikiController;
use App\Http\Controllers\Admin\AdminWikiController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Routes principales
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/changelog', [PageController::class, 'changelog'])->name('changelog');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/todolist', [PageController::class, 'todolist'])->name('todolist');
Route::get('/bug-report', [PageController::class, 'bugReport'])->name('bug-report');
Route::post('/bug-report', [PageController::class, 'storeBugReport'])->name('bug-report.store');
Route::get('/bug-report/{id}', [PageController::class, 'showBugReport'])->name('bug-report.show');

// Routes publiques du wiki
Route::prefix('wiki')->group(function () {
    Route::get('/', [WikiController::class, 'index'])->name('wiki');
    Route::get('/search', [WikiController::class, 'search'])->name('wiki.search');
    Route::get('/category/{slug}', [WikiController::class, 'category'])->name('wiki.category');
    Route::get('/{slug}', [WikiController::class, 'show'])->name('wiki.show');
});

// Routes d'administration du wiki
Route::middleware(['auth'])->prefix('admin/wiki')->name('admin.wiki.')->group(function () {
    // Gestion des catégories (placées avant les routes avec paramètres)
    Route::get('/categories', [AdminWikiController::class, 'categories'])->name('categories.index');
    Route::get('/categories/create', [AdminWikiController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [AdminWikiController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminWikiController::class, 'editCategory'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminWikiController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminWikiController::class, 'destroyCategory'])->name('categories.destroy');

    // Gestion des articles
    Route::get('/', [AdminWikiController::class, 'index'])->name('index');
    Route::get('/create', [AdminWikiController::class, 'create'])->name('create');
    Route::post('/', [AdminWikiController::class, 'store'])->name('store');
    Route::post('/preview', [AdminWikiController::class, 'preview'])->name('preview');
    Route::post('/toggle-status', [AdminWikiController::class, 'toggleWikiStatus'])->name('toggle-status');
    
    // Routes avec paramètres en dernier
    Route::get('/{article}/edit', [AdminWikiController::class, 'edit'])->name('edit');
    Route::put('/{article}', [AdminWikiController::class, 'update'])->name('update');
    Route::delete('/{article}', [AdminWikiController::class, 'destroy'])->name('destroy');
    Route::get('/{article}', [AdminWikiController::class, 'show'])->name('show');
    Route::post('/{article}/toggle-publication', [AdminWikiController::class, 'togglePublication'])->name('toggle-publication');
});

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Routes admin
// Dans le groupe des routes admin
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Routes pour le profil administrateur
    Route::get('/profile', [\App\Http\Controllers\AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\AdminProfileController::class, 'update'])->name('profile.update');
    Route::get('/pages', [AdminController::class, 'index'])->name('pages.index');
    Route::get('/pages/{page}/edit', [AdminController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{page}', [AdminController::class, 'update'])->name('pages.update');
    
    // Routes pour le changelog - toutes avec VersionController
    Route::get('/changelog', [VersionController::class, 'index'])->name('changelog');
    Route::get('/changelog/create', [VersionController::class, 'create'])->name('changelog.create');
    Route::post('/changelog', [VersionController::class, 'store'])->name('changelog.store');
    Route::get('/changelog/{version}/edit', [VersionController::class, 'edit'])->name('changelog.edit');
    Route::put('/changelog/{version}', [VersionController::class, 'update'])->name('changelog.update');
    Route::delete('/changelog/{version}', [VersionController::class, 'destroy'])->name('changelog.destroy');
    Route::post('/changelog/toggle-status', [VersionController::class, 'toggleChangelogStatus'])->name('changelog.toggle-status');
    
    // Upload d'image - Correction de la route
    Route::post('/upload/image', [App\Http\Controllers\Admin\ImageUploadController::class, 'store'])
        ->name('upload.image');
    
    // Routes pour les TodoItems
    Route::get('/todolist', [TodoItemController::class, 'index'])->name('todolist');
    Route::get('/todolist/create', [TodoItemController::class, 'create'])->name('todolist.create');
    Route::post('/todolist', [TodoItemController::class, 'store'])->name('todolist.store');
    Route::get('/todolist/{todoItem}/edit', [TodoItemController::class, 'edit'])->name('todolist.edit');
    Route::put('/todolist/{todoItem}', [TodoItemController::class, 'update'])->name('todolist.update');
    Route::delete('/todolist/{todoItem}', [TodoItemController::class, 'destroy'])->name('todolist.destroy');
    Route::post('/todolist/toggle-status', [TodoItemController::class, 'toggleTodoStatus'])->name('todolist.toggle-status');
    
    // Routes pour les rapports de bugs
    Route::get('/bug-reports', [BugReportController::class, 'index'])->name('bug_reports');
    Route::get('/bug-reports/create', [BugReportController::class, 'create'])->name('bug_reports.create');
    Route::post('/bug-reports', [BugReportController::class, 'store'])->name('bug_reports.store');
    Route::get('/bug-reports/{bugReport}/edit', [BugReportController::class, 'edit'])->name('bug_reports.edit');
    Route::put('/bug-reports/{bugReport}', [BugReportController::class, 'update'])->name('bug_reports.update');
    Route::delete('/bug-reports/{bugReport}', [BugReportController::class, 'destroy'])->name('bug_reports.destroy');
    Route::post('/bug-reports/toggle-status', [BugReportController::class, 'toggleBugReportStatus'])->name('bug_reports.toggle-status');
    // Correction: utilisation du contrôleur TodoItemController au lieu de TodoController
    Route::delete('/todos/{id}', [TodoItemController::class, 'destroy'])->name('todos.destroy');
    
    // Routes pour les paramètres
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/toggle', [SettingController::class, 'toggle'])->name('settings.toggle');

    // ... existing code ...
    Route::get('/wiki/settings', [AdminWikiController::class, 'settings'])->name('wiki.settings');
    // ... existing code ...
});
