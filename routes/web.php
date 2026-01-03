<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ... existing routes ...

// This group is protected. Only 'admin' can enter.
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    Route::get('/admin/dashboard', function () {
        return "<h1>Welcome to the Admin Dashboard</h1><p>Only admins can see this!</p>";
    })->name('admin.dashboard');

    // You can add more admin routes here later (e.g., /admin/users, /admin/reports)
});