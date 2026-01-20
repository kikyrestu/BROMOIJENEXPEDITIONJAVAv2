<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch published blogs, ordered by newest first
        $blogs = Blog::with(['author', 'category'])
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return view('pages.blogs.index', compact('blogs'));
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->whereNotNull('published_at')
            ->with(['author', 'category'])
            ->firstOrFail();

        // Related posts (same category, excluding current)
        $relatedPosts = Blog::where('category_id', $blog->category_id)
            ->where('id', '!=', $blog->id)
            ->whereNotNull('published_at')
            ->limit(3)
            ->get();

        return view('pages.blogs.show', compact('blog', 'relatedPosts'));
    }
}
