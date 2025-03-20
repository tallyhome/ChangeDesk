<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\VersionController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\Admin\TodoItemController;
use App\Http\Controllers\Admin\BugReportController;

// Routes principales
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/changelog', [PageController::class, 'changelog'])->name('changelog');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/todolist', [PageController::class, 'todolist'])->name('todolist');
Route::get('/bug-report', [PageController::class, 'bugReport'])->name('bug-report');
Route::post('/bug-report', [PageController::class, 'storeBugReport'])->name('bug-report.store');
Route::get('/bug-report/{id}', [PageController::class, 'showBugReport'])->name('bug-report.show');

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
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/pages', [AdminController::class, 'index'])->name('pages.index');
    Route::get('/pages/{id}/edit', [AdminController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{id}', [AdminController::class, 'update'])->name('pages.update');
    
    // Routes pour le changelog - toutes avec VersionController
    Route::get('/changelog', [VersionController::class, 'index'])->name('changelog');
    Route::get('/changelog/create', [VersionController::class, 'create'])->name('changelog.create');
    Route::post('/changelog', [VersionController::class, 'store'])->name('changelog.store');
    Route::get('/changelog/{version}/edit', [VersionController::class, 'edit'])->name('changelog.edit');
    Route::put('/changelog/{version}', [VersionController::class, 'update'])->name('changelog.update');
    Route::delete('/changelog/{version}', [VersionController::class, 'destroy'])->name('changelog.destroy');
    
    // Upload d'image
    Route::post('/upload/image', [ImageUploadController::class, 'upload'])->name('upload.image');
    
    // Routes pour les TodoItems
    Route::get('/todolist', [TodoItemController::class, 'index'])->name('todolist');
    Route::get('/todolist/create', [TodoItemController::class, 'create'])->name('todolist.create');
    Route::post('/todolist', [TodoItemController::class, 'store'])->name('todolist.store');
    Route::get('/todolist/{todoItem}/edit', [TodoItemController::class, 'edit'])->name('todolist.edit');
    Route::put('/todolist/{todoItem}', [TodoItemController::class, 'update'])->name('todolist.update');
    Route::delete('/todolist/{todoItem}', [TodoItemController::class, 'destroy'])->name('todolist.destroy');
    
    // Routes pour les rapports de bugs
    // Routes pour les rapports de bugs
    Route::get('/bug-reports', [BugReportController::class, 'index'])->name('bug_reports');
    Route::get('/bug-reports/create', [BugReportController::class, 'create'])->name('bug_reports.create');
    Route::post('/bug-reports', [BugReportController::class, 'store'])->name('bug_reports.store');
    Route::get('/bug-reports/{bugReport}/edit', [BugReportController::class, 'edit'])->name('bug_reports.edit');
    Route::put('/bug-reports/{bugReport}', [BugReportController::class, 'update'])->name('bug_reports.update');
    Route::delete('/bug-reports/{bugReport}', [BugReportController::class, 'destroy'])->name('bug_reports.destroy');
});
