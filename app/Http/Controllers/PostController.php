<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Admin sees all, User sees only theirs
        $query = $user->hasRole('admin') 
            ? \App\Models\Post::query() 
            : \App\Models\Post::where('user_id', $user->id);

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
        $query->where('is_published', $request->status === 'published');
        }

        // User Dashboard to Content Filter
        if ($request->filled('status') && $request->status !== 'all') {
        $query->where('is_published', $request->status === 'published');
        }

        // Sorting Filter (Newest/Oldest)
        if ($request->sort === 'oldest') {
        $query->orderBy('created_at', 'asc');
        } else {
            $query->latest(); 
        }

        // Stats based on scope
        $total = (clone $query)->count();
        $published = (clone $query)->where('is_published', true)->count();
        $drafts = (clone $query)->where('is_published', false)->count();

        // Paginate 10 items
        $posts = (clone $query)->with('user')->latest()->paginate(10);

        return view('posts.index', compact('posts', 'total', 'published', 'drafts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $request->user()->posts()->create([
        'title' => $validated['title'],
        'body' => $validated['body'],
        'is_published' => false, // Default to draft
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        if ($post->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
        abort(403, 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
        abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255', 
        'body' => 'required|string',
        ]);

        $post->fill($validated);

        $post->is_published = (bool) $request->input('is_published');

        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
        abort(403, 'Unauthorized action.');
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }

    public function published()
    {
        $user = auth()->user();

        // Admin sees all published, User sees only theirs
        $query = $user->hasRole('admin') 
            ? \App\Models\Post::query() 
            : \App\Models\Post::where('user_id', $user->id);

        // Filter only published items and paginate 10
        $dashboardPosts = (clone $query)->where('is_published', true)->latest()->paginate(10);

        return view('posts.published', [
            'dashboardPosts' => $dashboardPosts,
            'globalTotalContent' => (clone $query)->count(),
            'globalPublishedCount' => (clone $query)->where('is_published', true)->count(),
            'globalDraftCount' => (clone $query)->where('is_published', false)->count(),
        ]);
    }
    
        public function preview(Post $post)
    {
        // Ensure regular users can only preview their own drafts, 
        // but everyone can see published posts.
        if (!$post->is_published && $post->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return view('posts.preview', compact('post'));
    }
}
