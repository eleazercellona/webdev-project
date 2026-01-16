<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    $user = Auth::user();

    // 1. Determine the query scope: Admin sees all, User sees only theirs
    $query = $user->hasRole('admin') ? Post::query() : Post::where('user_id', $user->id);

    // 2. Calculate stats (cloning ensures counts are accurate)
    $total = (clone $query)->count();
    $published = (clone $query)->where('is_published', true)->count();
    $drafts = (clone $query)->where('is_published', false)->count();

    // 3. Paginate the main list (10 items per page)
    // We use paginate instead of get/take to enable the 1-10 numbering
    $dashboardPosts = (clone $query)->with('user')->latest()->paginate(10);

    // For the User-specific sections (Drafts vs Published)
    $userDrafts = (clone $query)->where('is_published', false)
        ->latest()
        ->take(3)
        ->get();
    
    $userPublished = (clone $query)->where('is_published', true)
        ->latest()
        ->take(2)
        ->get();

    return view('dashboard', compact('total', 'published', 'drafts', 'dashboardPosts', 'userDrafts', 'userPublished'));
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('posts', \App\Http\Controllers\PostController::class);
    Route::get('/published', [PostController::class, 'published'])->name('posts.published');
    Route::get('/posts/{post}/preview', [PostController::class, 'preview'])->name('posts.preview');
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

