<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaLibraryController extends Controller
{
    /**
     * List all media assets from public storage.
     */
    /**
     * List all media assets from database.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Media::latest();

        // Optional type filter
        if ($request->has('type')) {
            $type = $request->query('type');
            if ($type === 'image') {
                $query->where('type', 'image');
            } elseif ($type === 'video') {
                $query->where('type', 'video');
            }
        }

        $media = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'url' => $item->url, // Uses the accessor we fixed earlier
                'path' => $item->file_path,
                'name' => $item->name ?? basename($item->file_path),
                'type' => $item->mime_type ?? $item->type,
                'size' => $item->size,
            ];
        });

        return response()->json([
            'data' => $media
        ]);
    }

    /**
     * Store a new file upload.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:51200|mimes:jpg,jpeg,png,gif,webp,svg,mp4,mov,avi,wmv,pdf',
            'directory' => 'nullable|string|in:media,gallery,banners,packages,destinations,blog-thumbnails,heroes,settings,about,hotspots,testimonials,seo-images',
        ]);

        $directory = $request->input('directory', 'media'); // Default to 'media' top level or specific
        $file = $request->file('file');
        $path = $file->store($directory, 'public');
        
        $type = Str::startsWith($file->getMimeType(), 'video') ? 'video' : 'image';

        $media = \App\Models\Media::create([
            'name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'type' => $type,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'alt_text' => $file->getClientOriginalName(),
        ]);

        return response()->json([
            'id' => $media->id,
            'url' => $media->url,
            'path' => $media->file_path,
            'name' => $media->name,
            'type' => $media->mime_type,
        ]);
    }
}
