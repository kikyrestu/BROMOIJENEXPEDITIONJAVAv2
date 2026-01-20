<?php

use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

echo "Cleaning Media Database...\n";
Media::truncate();
echo "Media table truncated.\n";

echo "Cleaning Storage Files...\n";
if (Storage::disk('public')->exists('media')) {
    Storage::disk('public')->deleteDirectory('media');
    Storage::disk('public')->makeDirectory('media'); // Recreate empty folder
    echo "Media directory cleaned.\n";
} else {
    echo "Media directory does not exist or empty.\n";
}

// Optional: specific subfolders if used
$subfolders = ['testimonials', 'blog-covers', 'hotspots'];
foreach ($subfolders as $folder) {
    if (Storage::disk('public')->exists($folder)) {
        Storage::disk('public')->deleteDirectory($folder);
         Storage::disk('public')->makeDirectory($folder);
        echo "Cleaned $folder.\n";
    }
}

echo "Done!\n";
