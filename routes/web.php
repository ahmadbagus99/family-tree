<?php

use App\Http\Controllers\Admin\PersonController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\FamilyTreeController;
use App\Http\Controllers\PublicStorageFileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public Family Tree Views
// File upload (disk public) — lewat app agar tidak kena 403 symlink / web server
Route::get('/media/{path}', [PublicStorageFileController::class, 'show'])
    ->where('path', '.*')
    ->name('public-storage.file');

Route::get('/family-tree', [FamilyTreeController::class, 'index'])->name('family-tree.index');
Route::get('/family-tree/person/{person}', [FamilyTreeController::class, 'show'])->name('family-tree.show');
Route::get('/family-tree/{family}', [FamilyTreeController::class, 'family'])->name('family-tree.family');

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::resource('people', PersonController::class);
    Route::post('people/{person}/marriages', [PersonController::class, 'addMarriage'])->name('people.marriages');
    Route::delete('marriages/{marriage}', [PersonController::class, 'deleteMarriage'])->name('people.marriages.delete');
});
