<x-app-layout :seo="$page->seo ?? null">
    
    @if ($page && is_array($page->content))
        @foreach ($page->content as $block)
            @php
                $type = $block['type'];
                $data = $block['data'] ?? [];
            @endphp

            {{-- Wrapper for Live Preview Updates --}}
            <div data-preview-type="{{ $type }}">

                @switch($type)
                    @case('hero_video')
                         <x-blocks.hero_video :data="array_merge([
                            'heading' => $page->title ?? 'EXPLORE EAST JAVA',
                            'subheading' => 'Experience the majestic sunrise of Bromo and the blue fire of Ijen Crater with our premium expedition service.', 
                            'button_text' => 'Start Adventure',
                            'button_url' => '#packages',
                            'backgrounds' => [
                                [
                                    'url' => 'https://images.unsplash.com/photo-1548574505-5e239809ee19?ixlib=rb-1.2.1&auto=format&fit=crop&w=1956&q=80',
                                    'type' => 'image',
                                    'mime_type' => 'image/jpeg'
                                ]
                            ]
                        ], $data)" />
                        @break

                    @case('about_us')
                        @php
                            $aboutData = $data;
                            $aboutData['badge'] = $data['badge_text'] ?? $data['badge'] ?? null;
                            $aboutData['title'] = $data['title'] ?? null;
                            $aboutData['description'] = $data['description'] ?? $data['content'] ?? null;
                            $aboutData['show_cta'] = $data['show_cta'] ?? true;
                            $aboutData['cta_text'] = $data['cta_text'] ?? null;
                            $aboutData['cta_url'] = $data['cta_url'] ?? null;
                            $aboutData['show_founder'] = $data['show_founder'] ?? true;
                            $aboutData['founder_name'] = $data['founder_name'] ?? null;
                            $aboutData['founder_role'] = $data['founder_role'] ?? null;
                            
                            // Map Features
                            $features = [];
                            if (!empty($data['feature_1_title'])) {
                                $features[] = ['title' => $data['feature_1_title'], 'description' => $data['feature_1_text'] ?? '', 'icon' => 'guide'];
                            }
                            if (!empty($data['feature_2_title'])) {
                                $features[] = ['title' => $data['feature_2_title'], 'description' => $data['feature_2_text'] ?? '', 'icon' => 'booking'];
                            }
                            if (!empty($features)) {
                                $aboutData['features'] = $features;
                            }

                            // Map Images
                             if (($data['source_type'] ?? '') === 'media_library' && !empty($data['media_id'])) {
                                 $media = \App\Models\Media::find($data['media_id']);
                                 if($media && !empty($media->file_path)) {
                                     $path = $media->file_path;
                                     $aboutData['main_image'] = str_starts_with($path, 'http') ? $path : \Illuminate\Support\Facades\Storage::url($path);
                                 } else {
                                     // Media deleted or path empty - use placeholder
                                     $aboutData['main_image'] = 'https://placehold.co/600x800?text=No+Image';
                                 }
                            } elseif (($data['source_type'] ?? '') === 'url') {
                                $aboutData['main_image'] = $data['image_url'] ?? null;
                            }

                            if (($data['secondary_source_type'] ?? '') === 'media_library' && !empty($data['secondary_media_id'])) {
                                 $media = \App\Models\Media::find($data['secondary_media_id']);
                                 if($media && !empty($media->file_path)) {
                                     $path = $media->file_path;
                                     $aboutData['secondary_image'] = str_starts_with($path, 'http') ? $path : \Illuminate\Support\Facades\Storage::url($path);
                                 } else {
                                     // Media deleted or path empty - use placeholder
                                     $aboutData['secondary_image'] = 'https://placehold.co/500x500?text=No+Image';
                                 }
                            } elseif (($data['secondary_source_type'] ?? '') === 'url') {
                                $aboutData['secondary_image'] = $data['secondary_image_url'] ?? null;
                            }
                        @endphp
                        <x-sections.about-us :data="$aboutData" />
                        @break

                    @case('exclusive_destinations')
                        @php
                            $destinationsData = $data;
                            $prefix = $data['title_prefix'] ?? '';
                            $suffix = $data['title_suffix'] ?? '';
                            if($prefix || $suffix) {
                                $destinationsData['title'] = $prefix . ' <span class="text-brand-primary font-hand italic">' . $suffix . '</span>';
                            }
                            $destinationsData['badge'] = $data['badge_text'] ?? $data['badge'] ?? null;
                            
                            // Always fetch ALL destinations from database, ordered
                            $destinations = \App\Models\Destination::orderBy('sort_order')->get();
                        @endphp
                        <x-sections.exclusive-destinations :destinations="$destinations" :data="$destinationsData" />
                        @break

                    @case('gallery')
                         @php
                            $galleryData = $data;
                            $prefix = $data['title_prefix'] ?? '';
                            $suffix = $data['title_suffix'] ?? '';
                            if($prefix || $suffix) {
                                $galleryData['title'] = $prefix . ' <span class="text-brand-primary font-hand italic">' . $suffix . '</span>';
                            }
                            $galleryData['badge'] = $data['badge_text'] ?? $data['badge'] ?? null;
                            
                            // Check if images exist and are not placeholders
                            $manualImages = $data['gallery_images'] ?? $data['images'] ?? [];
                            $hasRealImages = !empty($manualImages) && !collect($manualImages)->every(fn($img) => str_contains($img['image'] ?? '', 'placehold.co'));
                            
                            if ($hasRealImages) {
                                $galleryData['images'] = $manualImages;
                            } else {
                                // First try to fetch from Gallery table
                                $sizes = ['large', 'small', 'small', 'tall', 'small', 'wide'];
                                $galleryItems = \App\Models\Gallery::orderBy('sort_order')->take(12)->get();
                                
                                if ($galleryItems->isNotEmpty()) {
                                    $galleryData['images'] = $galleryItems->map(function($item, $index) use ($sizes) {
                                        $imagePath = $item->image_path;
                                        // Check if has media relation
                                        if ($item->image_media_id) {
                                            $media = \App\Models\Media::find($item->image_media_id);
                                            if ($media) {
                                                $imagePath = $media->file_path;
                                            }
                                        }
                                        return [
                                            'image' => str_starts_with($imagePath ?? '', 'http') 
                                                ? $imagePath 
                                                : \Illuminate\Support\Facades\Storage::url($imagePath),
                                            'size' => $sizes[$index % count($sizes)] ?? 'small',
                                            'caption' => $item->title ?? '',
                                        ];
                                    })->toArray();
                                } else {
                                    // Fallback to Media table
                                    $mediaImages = \App\Models\Media::where('type', 'image')
                                        ->latest()
                                        ->take(12)
                                        ->get();
                                    
                                    $galleryData['images'] = $mediaImages->map(function($media, $index) use ($sizes) {
                                        return [
                                            'image' => str_starts_with($media->file_path, 'http') 
                                                ? $media->file_path 
                                                : \Illuminate\Support\Facades\Storage::url($media->file_path),
                                            'size' => $sizes[$index % count($sizes)] ?? 'small',
                                            'caption' => $media->alt_text ?? $media->name ?? '',
                                        ];
                                    })->toArray();
                                }
                            }
                        @endphp
                        <x-sections.gallery :data="$galleryData" />
                        @break

                    @case('package_slider')
                        @php
                            $packagesData = $data;
                            $prefix = $data['title_prefix'] ?? '';
                            $suffix = $data['title_suffix'] ?? '';
                            if($prefix || $suffix) {
                                $packagesData['title'] = $prefix . ' <span class="text-brand-primary font-hand italic">' . $suffix . '</span>';
                            }
                            $packagesData['badge'] = $data['badge_text'] ?? $data['badge'] ?? null;
                            
                            $packages = [];
                            if(!empty($data['package_ids'])) {
                                $packages = \App\Models\Package::with(['destination', 'categoryRelation'])->whereIn('id', $data['package_ids'])->where('status', 'published')->get();
                            } else {
                                // Default to all published packages if none selected
                                $packages = \App\Models\Package::with(['destination', 'categoryRelation'])->where('status', 'published')->get();
                            }
                        @endphp
                        <x-sections.package-slider :packages="$packages" :data="$packagesData" />
                        @break

                    @case('testimonials_marquee')
                        @php
                            $testimonialsData = $data;
                            $prefix = $data['title_prefix'] ?? '';
                            $suffix = $data['title_suffix'] ?? '';
                            if($prefix || $suffix) {
                                $testimonialsData['title'] = $prefix . ' <span class="text-brand-primary font-hand italic">' . $suffix . '</span>';
                            }
                            $testimonialsData['badge'] = $data['badge_text'] ?? $data['badge'] ?? null;
                            
                            // Fetch testimonials from manual data or database
                            $testimonialsList = $data['manual_testimonials'] ?? $data['testimonials'] ?? [];
                            
                            // If no manual testimonials, fetch from database
                            if(empty($testimonialsList)) {
                                $testimonialsList = \App\Models\Testimonial::publiclyVisible()
                                    ->orderBy('created_at', 'desc')
                                    ->get()
                                    ->map(function($t) {
                                        return [
                                            'name' => $t->name,
                                            'role' => $t->display_role,
                                            'content' => $t->content ?? $t->message ?? $t->review,
                                            'avatar' => $t->display_photo_url,
                                            'rating' => $t->rating ?? 5,
                                        ];
                                    })
                                    ->toArray();
                            }
                        @endphp
                         <x-sections.testimonials-marquee :testimonials="$testimonialsList" :data="$testimonialsData" />
                        @break

                    @case('blog_news')
                        @php
                            $blogNewsData = $data;
                            $prefix = $data['title_prefix'] ?? '';
                            $suffix = $data['title_suffix'] ?? '';
                            if($prefix || $suffix) {
                                $blogNewsData['title'] = $prefix . ' <span class="text-brand-primary font-hand italic">' . $suffix . '</span>';
                            }
                            $blogNewsData['badge'] = $data['badge_text'] ?? $data['badge'] ?? null;

                            $latest_posts = collect([]);
                            if(($data['auto_fetch'] ?? true)) {
                                 // Default logic if not specified in block (though block defaults to true)
                                 $limit = (int)($data['post_count'] ?? 3);
                                 $latest_posts = \App\Models\Blog::with(['category', 'author'])->where('status', 'published')->latest()->take($limit)->get();
                            }
                        @endphp
                        
                        @if(($data['auto_fetch'] ?? true) && $latest_posts->count() > 0)
                             <x-sections.blog-news :posts="$latest_posts" :data="$blogNewsData" />
                        @elseif(!empty($data['posts']))
                             <x-sections.blog-news :posts="$data['posts']" :data="$blogNewsData" />
                        @else
                             <x-sections.blog-news :data="$blogNewsData" />
                        @endif
                        @break

                    @case('hotspots')
                        <x-blocks.hotspots :data="$data" />
                        @break

                    @case('text_section')
                        <x-blocks.text_section :data="$data" />
                        @break

                @endswitch
            </div>
        @endforeach
    @endif

    @if(request('editor_mode'))
    <script>
        window.addEventListener('message', (event) => {
            if (event.data.type === 'UPDATE_BLOCK_FIELD') {
                const { blockType, field, value } = event.data;
                
                // Note: This simple selector might act on ALL blocks of same type. 
                // A more robust ID-based approach is needed for multiple same-type blocks, 
                // but Filament visual editor usually handles this by refreshing the iframe or we accept this limitation for now.
                const containers = document.querySelectorAll(`[data-preview-type="${blockType}"]`);
                
                containers.forEach(container => {
                    // 1. Text Content Updates
                    const textTargets = container.querySelectorAll(`[data-live="${field}"]`);
                    textTargets.forEach(target => {
                         target.innerHTML = value;
                    });
                    
                    // 2. Attribute Updates
                    const allElements = container.querySelectorAll('*');
                    allElements.forEach(el => {
                        if (el.dataset.liveAttr) {
                            const parts = el.dataset.liveAttr.split(':');
                            if (parts.length === 2 && parts[0] === field) {
                                el.setAttribute(parts[1], value);
                            }
                        }
                        
                        // Special Case: Image Sources
                        if ((field.includes('image') || field.includes('url')) && el.tagName === 'IMG' && el.dataset.live === field) {
                            if (value) {
                                console.log(`[Media Debug] Updating Image (${field}) to:`, value);
                                el.src = value;
                            } else {
                                console.warn(`[Media Debug] Creating empty image source for ${field}, skipping update to prevent 404.`);
                            }
                        }
                    });

                    // 3. Visibility Updates (Buttons/Sections)
                    const visibleTargets = container.querySelectorAll(`[data-live-visible="${field}"]`);
                    visibleTargets.forEach(target => {
                        const isVisible = value === true || value === 'true' || value === 1 || value === '1';
                        
                        if (isVisible) {
                            target.style.display = ''; // Reset to default (block/inline)
                            target.classList.remove('hidden');
                        } else {
                            target.style.display = 'none';
                            target.classList.add('hidden');
                        }
                    });
                });
            }
        });
    </script>
    @endif
</x-app-layout>
