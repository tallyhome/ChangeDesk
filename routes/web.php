<?php

use Illuminate\Support\Facades\Route;
// Supprimez cette ligne car vous utilisez VersionController pour le changelog
// use App\Http\Controllers\Admin\ChangelogController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\VersionController;
use App\Http\Controllers\Admin\ImageUploadController;

// Routes principales
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/changelog', [PageController::class, 'changelog'])->name('changelog');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Routes admin
// Dans le groupe des routes admin
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/pages', [AdminController::class, 'index'])->name('admin.pages.index');
    Route::get('/pages/{id}/edit', [AdminController::class, 'edit'])->name('admin.pages.edit');
    Route::put('/pages/{id}', [AdminController::class, 'update'])->name('admin.pages.update');
    
    // Routes pour le changelog - toutes avec VersionController
    Route::get('/changelog', [VersionController::class, 'index'])->name('admin.changelog');
    Route::get('/changelog/create', [VersionController::class, 'create'])->name('admin.changelog.create');
    Route::post('/changelog', [VersionController::class, 'store'])->name('admin.changelog.store');
    Route::get('/changelog/{version}/edit', [VersionController::class, 'edit'])->name('admin.changelog.edit');
    Route::put('/changelog/{version}', [VersionController::class, 'update'])->name('admin.changelog.update');
    Route::delete('/changelog/{version}', [VersionController::class, 'destroy'])->name('admin.changelog.destroy');
    
    // Upload d'image
    Route::post('/upload-image', [ImageUploadController::class, 'store'])->name('admin.upload.image');
});

// Supprimez ce groupe de routes entier car il est redondant et cause des conflits
// Route::prefix('admin')->name('admin.')->group(function () {
//     Route::get('/changelog', [ChangelogController::class, 'index'])->name('changelog');
//     Route::get('/changelog/create', [ChangelogController::class, 'create'])->name('changelog.create');
//     Route::post('/changelog', [ChangelogController::class, 'store'])->name('changelog.store');
//     Route::get('/changelog/{id}/edit', [ChangelogController::class, 'edit'])->name('changelog.edit');
//     Route::put('/changelog/{id}', [ChangelogController::class, 'update'])->name('changelog.update');
//     Route::delete('/changelog/{id}', [ChangelogController::class, 'destroy'])->name('changelog.destroy');
// });
