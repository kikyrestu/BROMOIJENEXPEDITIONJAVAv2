<?php

namespace App\Http\Controllers;

use App\Models\ReviewToken;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ClientReviewController extends Controller
{
    public function create(string $token)
    {
        $reviewToken = ReviewToken::where('token', $token)->first();

        if (!$reviewToken || !$reviewToken->isValid()) {
            abort(403, 'This review link is invalid or has already been used.');
        }

        return view('pages.reviews.create', ['token' => $token]);
    }

    public function store(Request $request, string $token)
    {
        $reviewToken = ReviewToken::where('token', $token)->first();

        if (!$reviewToken || !$reviewToken->isValid()) {
            abort(403, 'This review link is invalid or has already been used.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string',
            'photo' => 'nullable|image|max:10240',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('testimonials', 'public');
        }

        $payload = [
            'name' => $validated['name'],
            'role' => $validated['country'],
            'content' => $validated['content'],
            'rating' => $validated['rating'],
            'status' => 'approved',
            'review_token_id' => $reviewToken->id,
        ];

        if (Schema::hasColumn('testimonials', 'country')) {
            $payload['country'] = $validated['country'];
        }

        if (Schema::hasColumn('testimonials', 'photo_path')) {
            $payload['photo_path'] = $photoPath;
        }

        if (Schema::hasColumn('testimonials', 'avatar')) {
            $payload['avatar'] = $photoPath;
        }

        Testimonial::create($payload);

        $reviewToken->markUsed();

        return redirect()->route('client.review.success');
    }

    public function success()
    {
        return view('pages.reviews.success');
    }
}
