<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
            'is_published' => 'nullable|boolean',
            'save_as_draft' => 'nullable|boolean',
        ]);

        $isPublished = $request->boolean('is_published');

        if ($request->boolean('save_as_draft')) {
            $isPublished = false;
        }

        $slug = $this->generateUniqueSlug($validated['title']);

        $request->user()->posts()->create([
            'title' => $validated['title'],
            'slug' => $slug,
            'body' => $validated['body'],
            'is_published' => $isPublished,
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
            'is_published' => 'nullable|boolean',
        ]);

        $slug = $post->title === $validated['title']
            ? $post->slug
            : $this->generateUniqueSlug($validated['title'], $post->id);

        $post->fill([
            'title' => $validated['title'],
            'slug' => $slug,
            'body' => $validated['body'],
            'is_published' => $request->boolean('is_published'),
        ]);

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

    public function published(Request $request)
    {
        $user = auth()->user();

        // Admin sees all published, User sees only theirs
        $query = $user->hasRole('admin') 
            ? \App\Models\Post::query() 
            : \App\Models\Post::where('user_id', $user->id);

        // Filter only published items and paginate 10
        $publishedQuery = (clone $query)->where('is_published', true);

        // Sort newest/oldest (match Content tab)
        if ($request->sort === 'oldest') {
            $publishedQuery->orderBy('created_at', 'asc');
        } else {
            $publishedQuery->latest();
        }

        // Always paginate 10 per page and keep query string for filters
        $dashboardPosts = $publishedQuery
            ->with('user')
            ->paginate(10)
            ->withQueryString();

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

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title) ?: 'post';
        $slug = $baseSlug;
        $counter = 1;

        while (
            Post::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
