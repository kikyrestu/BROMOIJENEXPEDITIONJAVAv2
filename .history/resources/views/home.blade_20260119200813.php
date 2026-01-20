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
                                 if($media) {
                                     $path = $media->path;
                                     $aboutData['main_image'] = str_starts_with($path, 'http') ? $path : \Illuminate\Support\Facades\Storage::url($path);
                                 }
                            } elseif (($data['source_type'] ?? '') === 'url') {
                                $aboutData['main_image'] = $data['image_url'] ?? null;
                            }

                            if (($data['secondary_source_type'] ?? '') === 'media_library' && !empty($data['secondary_media_id'])) {
                                 $media = \App\Models\Media::find($data['secondary_media_id']);
                                 if($media) {
                                     $path = $media->path;
                                     $aboutData['secondary_image'] = str_starts_with($path, 'http') ? $path : \Illuminate\Support\Facades\Storage::url($path);
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
                            
                            $destinations = collect([]);
                            if(!empty($data['destination_ids'])) {
                                $destinations = \App\Models\Destination::whereIn('id', $data['destination_ids'])->get();
                            }
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
                            $galleryData['images'] = $data['gallery_images'] ?? $data['images'] ?? [];
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
                                $packages = \App\Models\Package::with('destination')->whereIn('id', $data['package_ids'])->get();
                            } else {
                                // Default to latest 6 packages if none selected
                                $packages = \App\Models\Package::with('destination')->latest()->take(6)->get();
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
                            
                            $testimonialsList = $data['manual_testimonials'] ?? $data['testimonials'] ?? [];
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
                                 $latest_posts = \App\Models\Blog::where('status', 'published')->latest()->take($limit)->get();
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
                            el.src = value;
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
