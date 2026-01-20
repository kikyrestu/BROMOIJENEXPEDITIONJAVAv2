<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class VisualEditorController extends Controller
{
    /**
     * Show the Visual Editor Shell.
     */
    public function edit(Request $request, Page $page)
    {
        // Enforce access control if needed beyond middleware
        // if (!auth()->user()->can('update', $page)) { abort(403); }

        return view('admin.editor.shell', [
            'page' => $page,
            'iframeUrl' => route('home', ['editor_mode' => 'true', 'page_id' => $page->id]),
            'allDestinations' => \App\Models\Destination::select('id', 'name')->get(),
            'allPackages' => \App\Models\Package::select('id', 'name')->get(),
        ]);
    }

    /**
     * Handle AJAX update from the Visual Editor.
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'present|array', // Allow empty content array
        ]);

        \Illuminate\Support\Facades\Log::info('VisualEditor Update Payload:', [
            'page_id' => $page->id,
            'content_count' => count($validated['content']),
            'sample_keys' => array_keys($validated['content'][0]['data'] ?? []),
            'hero_template' => $validated['content'][0]['data']['template'] ?? 'N/A',
            'spots_count' => count($validated['content'][0]['data']['spots'] ?? []),
            'backgrounds_debug' => $validated['content'][0]['data']['backgrounds'] ?? 'MISSING_KEY',
        ]);

        // Explicitly set attributes
        $page->title = $validated['title'];
        $page->content = $validated['content'];
        $result = $page->save();

        // Reload from DB to confirm what was saved
        $page->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Page updated successfully!',
            'debug_persisted_spots' => $page->content[0]['data']['spots'] ?? 'MISSING',
            'debug_result' => $result,
        ]);
    }
}
