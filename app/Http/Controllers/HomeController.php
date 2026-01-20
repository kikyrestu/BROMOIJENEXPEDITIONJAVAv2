<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        // 1. Fetch Page
        $page = Page::where('slug', 'home')->first();
        
        if (!$page) {
             return "ERROR: Home Page Not Found in DB. Please run seeder.";
        }

        // 2. Fetch Destinations
        $destinations = \App\Models\Destination::where('is_featured', true)->take(3)->get();
        
        // 3. Fetch Packages
        $packages = \App\Models\Package::with('destination')->take(6)->get();

        // 4. Fetch Latest Blogs
        $latest_posts = \App\Models\Blog::where('status', 'published')->latest()->take(3)->get();

        // 5. Fetch Testimonials
        $testimonials = \App\Models\Testimonial::where('status', 'published')->latest()->take(6)->get();

        // dd([
        //     'Page Title' => $page->title,
        //     'SEO Object' => $page->seo ? 'Exists' : 'NULL',
        //     'Destinations Count' => $destinations->count(),
        //     'Packages Count' => $packages->count()
        // ]);

        return view('home', compact('page', 'destinations', 'packages', 'latest_posts', 'testimonials'));
    }
}
