<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index()
    {
        // 1. Calculate Stats for the top cards
        $total = \App\Models\Post::count();
        $published = \App\Models\Post::where('is_published', true)->count();
        $drafts = \App\Models\Post::where('is_published', false)->count();

        // 2. Get posts with pagination (10 per page)
        $posts = \App\Models\Post::with('user')->latest()->paginate(10);

        // 3. Pass everything to the view
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
        'body' => 'required|string',
        // We might add 'is_published' validation here later for admins
        ]);

        $post->update($validated);

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
}
