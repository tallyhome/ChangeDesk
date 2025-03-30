<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WikiController;
use App\Http\Controllers\Admin\AdminWikiController;

// Routes publiques du wiki
Route::prefix('wiki')->group(function () {
    Route::get('/', [WikiController::class, 'index'])->name('wiki');
    Route::get('/search', [WikiController::class, 'search'])->name('wiki.search');
    Route::get('/category/{slug}', [WikiController::class, 'category'])->name('wiki.category');
    Route::get('/{slug}', [WikiController::class, 'show'])->name('wiki.show');
});

// Routes d'administration du wiki
Route::middleware(['auth'])->prefix('admin/wiki')->name('admin.wiki.')->group(function () {
    // Gestion des articles
    Route::get('/', [AdminWikiController::class, 'index'])->name('index');
    Route::get('/create', [AdminWikiController::class, 'create'])->name('create');
    Route::post('/', [AdminWikiController::class, 'store'])->name('store');
    Route::get('/{article}', [AdminWikiController::class, 'show'])->name('show');
    Route::get('/{article}/edit', [AdminWikiController::class, 'edit'])->name('edit');
    Route::put('/{article}', [AdminWikiController::class, 'update'])->name('update');
    Route::delete('/{article}', [AdminWikiController::class, 'destroy'])->name('destroy');
    Route::post('/preview', [AdminWikiController::class, 'preview'])->name('preview');
    
    // Gestion des catégories
    Route::get('/categories', [AdminWikiController::class, 'categories'])->name('categories');
    Route::get('/categories/create', [AdminWikiController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [AdminWikiController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminWikiController::class, 'editCategory'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminWikiController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminWikiController::class, 'destroyCategory'])->name('categories.destroy');
});