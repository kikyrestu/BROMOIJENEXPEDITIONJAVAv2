<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visual Editor - {{ $page->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        /* Custom Scrollbar for Sidebar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { bg-slate-100; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-slate-100 h-screen w-screen overflow-hidden flex" x-data="visualEditor()">
    
    <!-- LEFT: PREVIEW IFRAME -->
    <div class="flex-1 h-full relative transition-all duration-300 transform" 
         :class="{ 'mr-[400px]': sidebarOpen, 'mr-0': !sidebarOpen }">
        
        <!-- Toolbar -->
        <div class="h-14 bg-slate-900 border-b border-slate-700 text-white flex items-center justify-between px-6 shadow-md z-10 relative">
            <div class="flex items-center gap-4">
                <a href="/admin" class="text-slate-400 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div class="h-6 w-px bg-slate-700"></div>
                <div class="text-sm font-bold tracking-wide">Live Preview: <span class="text-slate-300 font-normal">{{ $page->title }}</span></div>
            </div>
            
            <div class="flex items-center gap-3">
                <button @click="deviceMode = 'desktop'" :class="{ 'text-blue-400': deviceMode === 'desktop', 'text-slate-400': deviceMode !== 'desktop' }" class="p-2 hover:bg-slate-800 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                    </svg>
                </button>
                <button @click="deviceMode = 'mobile'" :class="{ 'text-blue-400': deviceMode === 'mobile', 'text-slate-400': deviceMode !== 'mobile' }" class="p-2 hover:bg-slate-800 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                    </svg>
                </button>
                <div class="h-6 w-px bg-slate-700 mx-2"></div>
                <button @click="$refs.previewFrame.contentWindow.location.reload()" class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition" title="Refresh Preview">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Iframe Container -->
        <div class="w-full h-[calc(100vh-56px)] bg-slate-200/50 flex items-center justify-center p-8 overflow-hidden">
            <iframe x-ref="previewFrame" 
                    src="{{ $iframeUrl }}" 
                    class="bg-white shadow-2xl transition-all duration-500 ease-in-out border border-slate-300" 
                    :class="{ 
                        'w-full h-full rounded-none': deviceMode === 'desktop', 
                        'w-[375px] h-[667px] rounded-[40px] border-8 border-slate-800': deviceMode === 'mobile' 
                    }"
                    frameborder="0"></iframe>
        </div>
    </div>

    <!-- RIGHT: CONTROL PANEL SIDEBAR -->
    <div class="fixed right-0 top-0 h-full w-[400px] bg-white shadow-2xl z-50 transform transition-transform duration-300 border-l border-slate-200 flex flex-col"
         :class="{ 'translate-x-0': sidebarOpen, 'translate-x-full': !sidebarOpen }">
        
        <!-- Header -->
        <div class="h-14 border-b border-slate-200 flex items-center justify-between px-6 bg-slate-50 shrink-0">
            <h2 class="font-bold text-slate-800 text-lg">Page Editor</h2>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-500">Close</button>
        </div>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-6 space-y-8 custom-scrollbar">
            
            <!-- Page Metadata -->
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Page Title</label>
                <input type="text" x-model="page.title" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>

            <hr class="border-slate-100">

            <!-- Blocks List -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Content Blocks</label>
                    <span class="text-xs text-slate-400 italic">Drag to reorder (Coming Soon)</span>
                </div>
                
                <div class="space-y-4">
                    <template x-for="(block, index) in page.content" :key="index">
                        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden transition-all duration-200"
                             :class="{ 'ring-2 ring-blue-500 shadow-md': activeBlock === index, 'hover:border-blue-300': activeBlock !== index }">
                            
                            <!-- Block Header (Collapsed) -->
                            <div class="p-4 flex items-center justify-between cursor-pointer bg-slate-50 border-b border-slate-100"
                                 @click="activeBlock = (activeBlock === index ? null : index)">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-500">
                                        <span class="text-xs font-bold" x-text="index + 1"></span>
                                    </div>
                                    <span class="text-sm font-bold text-slate-700 capitalize" x-text="block.type.replace(/_/g, ' ')"></span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                     :class="{ 'rotate-180': activeBlock === index }"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            <!-- Block Editor (Expanded) -->
                            <div x-show="activeBlock === index" x-collapse class="p-5 space-y-4 bg-white">
                                
                                <!-- Dynamic Fields based on Type -->
                                
                                <!-- HERO VIDEO -->
                                <template x-if="block.type === 'hero_video'">
                                    <div class="space-y-4">
                                        <!-- Template Selector -->
                                        <div class="flex bg-slate-100 p-1 rounded-lg">
                                            <button @click="page.content[index].data.template = 'default'; updatePreview(index, 'template', 'default')" 
                                                class="flex-1 py-1.5 text-xs font-bold rounded-md transition"
                                                :class="{'bg-white shadow text-blue-600': (block.data.template || 'default') === 'default', 'text-slate-500': (block.data.template || 'default') !== 'default'}">
                                                Standard
                                            </button>
                                            <button @click="page.content[index].data.template = 'hotspots'; updatePreview(index, 'template', 'hotspots')" 
                                                 class="flex-1 py-1.5 text-xs font-bold rounded-md transition"
                                                 :class="{'bg-white shadow text-blue-600': block.data.template === 'hotspots', 'text-slate-500': block.data.template !== 'hotspots'}">
                                                Interactive
                                            </button>
                                        </div>

                                        <!-- Common Fields -->
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 mb-1">Heading</label>
                                            <input type="text" x-model="block.data.heading" 
                                                   @input="updatePreview(index, 'heading', $event.target.value)"
                                                   class="w-full text-sm border-slate-200 rounded-md p-2">
                                        </div>

                                         <div>
                                            <label class="block text-xs font-bold text-slate-500 mb-1">Subheading</label>
                                            <input type="text" x-model="block.data.subheading" 
                                                   @input="updatePreview(index, 'subheading', $event.target.value)"
                                                   class="w-full text-sm border-slate-200 rounded-md p-2">
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 mt-2">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 mb-1">Button Text</label>
                                                <input type="text" x-model="block.data.button_text" placeholder="Start Adventure"
                                                       @input="updatePreview(index, 'button_text', $event.target.value)"
                                                       class="w-full text-sm border-slate-200 rounded-md p-2">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 mb-1">Button URL</label>
                                                <input type="text" x-model="block.data.button_url" placeholder="#packages"
                                                       @input="updatePreview(index, 'button_url', $event.target.value)"
                                                       class="w-full text-sm border-slate-200 rounded-md p-2">
                                            </div>
                                            <div class="col-span-2 flex items-center mt-2">
                                                <input type="checkbox" x-model="block.data.show_button" 
                                                       @change="updatePreview(index, 'show_button', $event.target.checked)"
                                                       class="rounded border-slate-300 text-brand-accent shadow-sm focus:ring-brand-accent mr-2">
                                                <label class="text-xs font-bold text-slate-500">Show CTA Button</label>
                                            </div>
                                        </div>

                                        <!-- SPOTS MANAGER (Only for Hotspots) -->
                                        <div x-show="block.data.template === 'hotspots'" class="bg-slate-50 border border-slate-100 rounded-lg p-3">
                                            <div class="flex justify-between items-center mb-2">
                                                <label class="text-xs font-bold text-slate-400 uppercase">Hotspots</label>
                                                <button @click="
                                                    if(!page.content[index].data.spots) page.content[index].data.spots = [];
                                                    page.content[index].data.spots.push({ x: 50, y: 50, label: 'New Spot', description: '' });
                                                    $nextTick(() => { 
                                                        if($refs.previewFrame) $refs.previewFrame.contentWindow.location.reload(); 
                                                    });
                                                " class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded font-bold hover:bg-blue-200">+ Add</button>
                                            </div>
                                            
                                            <div class="space-y-2 max-h-[300px] overflow-y-auto pr-1 custom-scrollbar">
                                                <template x-for="(spot, sIndex) in (block.data.spots || [])" :key="sIndex">
                                                    <div class="bg-white border boundary-l-4 border-l-blue-500 rounded p-2 shadow-sm">
                                                        <div class="flex justify-between mb-1">
                                                            <span class="text-xs font-bold text-slate-700" x-text="'#' + (sIndex + 1)"></span>
                                                            <button @click="block.data.spots.splice(sIndex, 1); $nextTick(() => { if($refs.previewFrame) $refs.previewFrame.contentWindow.location.reload(); })" class="text-red-400 hover:text-red-600">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                                            </button>
                                                        </div>
                                                        <input type="text" x-model="spot.label" placeholder="Label" class="w-full text-xs border-slate-200 mb-1 rounded focus:ring-1">
                                                        <div class="grid grid-cols-2 gap-1">
                                                            <div>
                                                                <label class="text-[10px] text-slate-400">X%</label>
                                                                <input type="number" x-model="spot.x" class="w-full text-xs border-slate-200 rounded bg-slate-50" readonly title="Drag in preview to change">
                                                            </div>
                                                            <div>
                                                                <label class="text-[10px] text-slate-400">Y%</label>
                                                                <input type="number" x-model="spot.y" class="w-full text-xs border-slate-200 rounded bg-slate-50" readonly title="Drag in preview to change">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                                <div x-show="!block.data.spots || block.data.spots.length === 0" class="text-xs text-center text-slate-400 italic py-2">
                                                    No spots yet.
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Video Source Control -->
                                        <!-- Background Playlist -->
                                        <div>
                                            <div class="flex justify-between items-center mb-2">
                                                <label class="block text-xs font-bold text-slate-500">
                                                    Background Playlist 
                                                    (<span x-text="block.data.backgrounds ? block.data.backgrounds.length : 0"></span>)
                                                </label>
                                                <button @click="openMediaLibrary(index, 'backgrounds')" class="text-xs bg-slate-800 text-white px-2 py-1 rounded font-bold hover:bg-slate-700">+ Add Slide</button>
                                            </div>

                                            <div class="space-y-2">
                                                <template x-for="(bg, bgIndex) in (block.data.backgrounds || [])" :key="bgIndex">
                                                    <div class="flex items-center gap-2 p-2 bg-white border border-slate-200 rounded shadow-sm group">
                                                        <!-- Icon/Thumb -->
                                                        <div class="w-10 h-10 bg-slate-100 rounded flex items-center justify-center overflow-hidden shrink-0">
                                                            <template x-if="bg.mime_type && bg.mime_type.includes('image')">
                                                                <img :src="bg.url" class="w-full h-full object-cover">
                                                            </template>
                                                            <template x-if="!bg.mime_type || !bg.mime_type.includes('image')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                            </template>
                                                        </div>
                                                        
                                                        <!-- Info -->
                                                        <div class="flex-1 min-w-0">
                                                            <div class="text-[10px] text-slate-400 font-mono truncate" x-text="bg.id ? 'Media ID: ' + bg.id : 'External URL'"></div>
                                                            <div class="text-xs font-bold text-slate-700 truncate" x-text="bg.id ? 'Media File' : bg.url"></div>
                                                        </div>

                                                        <!-- Actions -->
                                                        <button @click="block.data.backgrounds.splice(bgIndex, 1); updatePreview(index, 'backgrounds', block.data.backgrounds)" class="text-slate-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                                        </button>
                                                    </div>
                                                </template>
                                                <div x-show="!block.data.backgrounds || block.data.backgrounds.length === 0" class="text-xs text-center text-slate-400 italic py-2">
                                                    No backgrounds. Add one to start.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- ABOUT US -->
                                <template x-if="block.type === 'about_us'">
                                    <div class="space-y-4">
                                        <!-- MAIN MEDIA CONTROLS -->
                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Main Media</label>
                                            <div class="space-y-3">
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-500 mb-1">Type</label>
                                                        <select x-model="block.data.media_type" 
                                                                @change="updatePreview(index, 'media_type', $event.target.value)"
                                                                class="w-full text-sm border-slate-200 rounded-md p-2">
                                                            <option value="image">Image</option>
                                                            <option value="video">Video</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-500 mb-1">Source</label>
                                                        <select x-model="block.data.source_type" 
                                                                @change="updatePreview(index, 'source_type', $event.target.value)"
                                                                class="w-full text-sm border-slate-200 rounded-md p-2">
                                                            <option value="media_library">Library</option>
                                                            <option value="url">Direct URL</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Library Picker -->
                                                <div x-show="block.data.source_type === 'media_library'">
                                                    <div class="flex items-center gap-2">
                                                        <input type="text" :value="block.data.media_id ? 'Media ID: ' + block.data.media_id : 'No Media Selected'" readonly class="w-full text-xs border-slate-200 rounded-md p-2 bg-slate-100 text-slate-500">
                                                        <button @click="openMediaLibrary(index, 'media_id')" class="px-3 py-2 bg-brand-primary text-white text-xs rounded-md shadow hover:bg-brand-dark whitespace-nowrap">
                                                            Select Media
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- URL Input -->
                                                <div x-show="block.data.source_type === 'url'">
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Media URL</label>
                                                    <input type="text" x-model="block.data.media_type === 'video' ? block.data.video_url : block.data.image_url" 
                                                           @input="updatePreview(index, block.data.media_type === 'video' ? 'video_url' : 'image_url', $event.target.value)"
                                                           class="w-full text-sm border-slate-200 rounded-md p-2" placeholder="https://example.com/image.jpg">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- SECONDARY MEDIA CONTROLS -->
                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Secondary Image (Floating)</label>
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Source</label>
                                                    <select x-model="block.data.secondary_source_type" 
                                                            @change="updatePreview(index, 'secondary_source_type', $event.target.value)"
                                                            class="w-full text-sm border-slate-200 rounded-md p-2">
                                                        <option value="media_library">Library</option>
                                                        <option value="url">Direct URL</option>
                                                    </select>
                                                </div>

                                                <!-- Library Picker -->
                                                <div x-show="!block.data.secondary_source_type || block.data.secondary_source_type === 'media_library'">
                                                    <div class="flex items-center gap-2">
                                                        <input type="text" :value="block.data.secondary_media_id ? 'Media ID: ' + block.data.secondary_media_id : 'No Media Selected'" readonly class="w-full text-xs border-slate-200 rounded-md p-2 bg-slate-100 text-slate-500">
                                                        <button @click="openMediaLibrary(index, 'secondary_media_id')" class="px-3 py-2 bg-brand-primary text-white text-xs rounded-md shadow hover:bg-brand-dark whitespace-nowrap">
                                                            Select Image
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- URL Input -->
                                                <div x-show="block.data.secondary_source_type === 'url'">
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Image URL</label>
                                                    <input type="text" x-model="block.data.secondary_image_url" 
                                                           @input="updatePreview(index, 'secondary_image_url', $event.target.value)"
                                                           class="w-full text-sm border-slate-200 rounded-md p-2" placeholder="https://example.com/small-image.jpg">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Main Content</label>
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Badge Text</label>
                                                    <input type="text" x-model="block.data.badge" 
                                                        @input="updatePreview(index, 'badge', $event.target.value)"
                                                        class="w-full text-sm border-slate-200 rounded-md p-2">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Title</label>
                                                    <input type="text" x-model="block.data.title" 
                                                        @input="updatePreview(index, 'title', $event.target.value)"
                                                        class="w-full text-sm border-slate-200 rounded-md p-2">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Content</label>
                                                    <textarea x-model="block.data.description" rows="3" 
                                                            @input="updatePreview(index, 'description', $event.target.value)"
                                                            class="w-full text-sm border-slate-200 rounded-md p-2 font-mono"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Features</label>
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Feature 1 Title</label>
                                                    <input type="text" x-model="block.data.feature_1_title" 
                                                        @input="updatePreview(index, 'feature_1_title', $event.target.value)"
                                                        class="w-full text-sm border-slate-200 rounded-md p-2">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Feature 1 Text</label>
                                                    <input type="text" x-model="block.data.feature_1_text" 
                                                        @input="updatePreview(index, 'feature_1_text', $event.target.value)"
                                                        class="w-full text-sm border-slate-200 rounded-md p-2">
                                                </div>
                                                <hr class="border-slate-200">
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Feature 2 Title</label>
                                                    <input type="text" x-model="block.data.feature_2_title" 
                                                        @input="updatePreview(index, 'feature_2_title', $event.target.value)"
                                                        class="w-full text-sm border-slate-200 rounded-md p-2">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Feature 2 Text</label>
                                                    <input type="text" x-model="block.data.feature_2_text" 
                                                        @input="updatePreview(index, 'feature_2_text', $event.target.value)"
                                                        class="w-full text-sm border-slate-200 rounded-md p-2">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Footer Info</label>
                                            <div class="space-y-3">
                                                <!-- CTA Button -->
                                                <div>
                                                    <div class="flex items-center justify-between mb-1">
                                                        <label class="block text-xs font-bold text-slate-500">CTA Button</label>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" x-model="block.data.show_cta" 
                                                                   @change="updatePreview(index, 'show_cta', $event.target.checked)"
                                                                   class="rounded border-slate-300 text-brand-accent h-3 w-3 shadow-sm focus:ring-brand-accent mr-1">
                                                            <span class="text-[10px] text-slate-400">Show</span>
                                                        </div>
                                                    </div>
                                                    <div x-show="block.data.show_cta" class="space-y-2">
                                                        <input type="text" x-model="block.data.cta_text" placeholder="Button Label"
                                                            @input="updatePreview(index, 'cta_text', $event.target.value)"
                                                            class="w-full text-sm border-slate-200 rounded-md p-2">
                                                        <input type="text" x-model="block.data.cta_url" placeholder="Button URL"
                                                            @input="updatePreview(index, 'cta_url', $event.target.value)"
                                                            class="w-full text-sm border-slate-200 rounded-md p-2">
                                                    </div>
                                                </div>
                                                
                                                <hr class="border-slate-100">
                                                
                                                <!-- Founder -->
                                                <div>
                                                    <div class="flex items-center justify-between mb-2">
                                                        <label class="block text-xs font-bold text-slate-500">Founder Profile</label>
                                                        <div class="flex items-center">
                                                            <input type="checkbox" x-model="block.data.show_founder" 
                                                                   @change="updatePreview(index, 'show_founder', $event.target.checked)"
                                                                   class="rounded border-slate-300 text-brand-accent h-3 w-3 shadow-sm focus:ring-brand-accent mr-1">
                                                            <span class="text-[10px] text-slate-400">Show</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="grid grid-cols-2 gap-2" x-show="block.data.show_founder">
                                                        <div>
                                                            <label class="block text-[10px] font-bold text-slate-400 mb-1">Name</label>
                                                            <input type="text" x-model="block.data.founder_name" 
                                                                @input="updatePreview(index, 'founder_name', $event.target.value)"
                                                                class="w-full text-sm border-slate-200 rounded-md p-2">
                                                        </div>
                                                        <div>
                                                            <label class="block text-[10px] font-bold text-slate-400 mb-1">Role</label>
                                                            <input type="text" x-model="block.data.founder_role" 
                                                                @input="updatePreview(index, 'founder_role', $event.target.value)"
                                                                class="w-full text-sm border-slate-200 rounded-md p-2">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </template>

                                <!-- GENERIC SECTION TITLE FIELDS (Shared by many) -->
                                <template x-if="['exclusive_destinations', 'package_slider', 'testimonials_marquee', 'blog_news'].includes(block.type)">
                                    <div class="space-y-4">
                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Header Settings</label>
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Badge Text</label>
                                                    <input type="text" x-model="block.data.badge_text" 
                                                        @input="updatePreview(index, 'badge_text', $event.target.value)"
                                                        class="w-full text-sm border-slate-200 rounded-md p-2">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Title Prefix (Black)</label>
                                                    <input type="text" x-model="block.data.title_prefix" 
                                                        @input="updatePreview(index, 'title_prefix', $event.target.value)"
                                                        class="w-full text-sm border-slate-200 rounded-md p-2">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Title Suffix (Colored)</label>
                                                    <input type="text" x-model="block.data.title_suffix" 
                                                        @input="updatePreview(index, 'title_suffix', $event.target.value)"
                                                        class="w-full text-sm border-slate-200 rounded-md p-2">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Description (Optional)</label>
                                                    <textarea x-model="block.data.description" rows="2" 
                                                            @input="updatePreview(index, 'description', $event.target.value)"
                                                            class="w-full text-sm border-slate-200 rounded-md p-2"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- EXCLUSIVE DESTINATIONS SPECIFIC -->
                                <template x-if="block.type === 'exclusive_destinations'">
                                    <div class="space-y-4 mt-4">
                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Destinations Selection</label>
                                            <div class="space-y-2 max-h-48 overflow-y-auto bg-white p-2 border border-slate-200 rounded">
                                                @foreach($allDestinations as $dest)
                                                <label class="flex items-center space-x-2 p-1 hover:bg-slate-50 rounded cursor-pointer">
                                                    <input type="checkbox" value="{{ $dest->id }}" x-model="block.data.destination_ids" class="rounded border-slate-300 text-brand-primary focus:ring-brand-primary h-4 w-4">
                                                    <span class="text-sm text-slate-700">{{ $dest->name }}</span>
                                                </label>
                                                @endforeach
                                            </div>
                                            <p class="text-[10px] text-slate-400 mt-1">Select destinations to display. Leave empty to show all/latest.</p>
                                        </div>
                                    </div>
                                </template>

                                <!-- PACKAGE SLIDER SPECIFIC -->
                                <template x-if="block.type === 'package_slider'">
                                    <div class="space-y-4 mt-4">
                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Packages Selection</label>
                                            <div class="space-y-2 max-h-48 overflow-y-auto bg-white p-2 border border-slate-200 rounded">
                                                @foreach($allPackages as $pkg)
                                                <label class="flex items-center space-x-2 p-1 hover:bg-slate-50 rounded cursor-pointer">
                                                    <input type="checkbox" value="{{ $pkg->id }}" x-model="block.data.package_ids" class="rounded border-slate-300 text-brand-primary focus:ring-brand-primary h-4 w-4">
                                                    <span class="text-sm text-slate-700">{{ $pkg->name }}</span>
                                                </label>
                                                @endforeach
                                            </div>
                                            <p class="text-[10px] text-slate-400 mt-1">Select packages to display. Leave empty to show featured.</p>
                                        </div>
                                    </div>
                                </template>

                                <!-- TESTIMONIALS SPECIFIC -->
                                <template x-if="block.type === 'testimonials_marquee'">
                                    <div class="space-y-4 mt-4">
                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Source</label>
                                            <select x-model="block.data.source" class="w-full text-sm border-slate-200 rounded-md p-2 mb-2">
                                                <option value="auto">Auto (From Database)</option>
                                                <option value="manual">Manual Input</option>
                                            </select>
                                            
                                            <div x-show="block.data.source === 'manual'" class="mt-4">
                                                <div class="flex justify-between items-center mb-2">
                                                    <label class="block text-xs font-bold text-slate-500">Manual Testimonials</label>
                                                    <button @click="if(!block.data.manual_testimonials) block.data.manual_testimonials = []; block.data.manual_testimonials.push({name: 'Traveler', role: 'Visitor', text: 'Amazing experience!'})" class="text-xs bg-slate-800 text-white px-2 py-1 rounded font-bold hover:bg-slate-700">+ Add</button>
                                                </div>
                                                
                                                <template x-for="(testimonial, tIndex) in (block.data.manual_testimonials || [])" :key="tIndex">
                                                    <div class="bg-white border border-slate-200 rounded p-2 mb-2 space-y-2 relative group">
                                                         <button @click="block.data.manual_testimonials.splice(tIndex, 1)" class="absolute top-1 right-1 text-slate-300 hover:text-red-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                                        </button>
                                                        <div class="flex gap-2">
                                                            <input type="text" x-model="testimonial.name" placeholder="Name" class="w-1/2 text-xs border-slate-200 rounded p-1">
                                                            <input type="text" x-model="testimonial.role" placeholder="Role/Country" class="w-1/2 text-xs border-slate-200 rounded p-1">
                                                        </div>
                                                        <textarea x-model="testimonial.text" placeholder="Review Text" rows="2" class="w-full text-xs border-slate-200 rounded p-1"></textarea>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- GALLERY SECTION -->
                                <template x-if="block.type === 'gallery'">
                                    <div class="space-y-4">
                                        <!-- Header Settings -->
                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Header Settings</label>
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Badge Text</label>
                                                    <input type="text" x-model="block.data.badge_text" 
                                                        @input="updatePreview(index, 'badge_text', $event.target.value)"
                                                        class="w-full text-sm border-slate-200 rounded-md p-2">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Title Prefix (Black)</label>
                                                    <input type="text" x-model="block.data.title_prefix" 
                                                        @input="updatePreview(index, 'title_prefix', $event.target.value)"
                                                        class="w-full text-sm border-slate-200 rounded-md p-2">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Title Suffix (Colored)</label>
                                                    <input type="text" x-model="block.data.title_suffix" 
                                                        @input="updatePreview(index, 'title_suffix', $event.target.value)"
                                                        class="w-full text-sm border-slate-200 rounded-md p-2">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Description</label>
                                                    <textarea x-model="block.data.description" rows="3" 
                                                            @input="updatePreview(index, 'description', $event.target.value)"
                                                            class="w-full text-sm border-slate-200 rounded-md p-2 font-mono"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Gallery Images -->
                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <div class="flex justify-between items-center mb-2">
                                                <label class="block text-xs font-bold text-slate-500">
                                                    Gallery Images
                                                    (<span x-text="block.data.gallery_images ? block.data.gallery_images.length : 0"></span>)
                                                </label>
                                                <button @click="openMediaLibrary(index, 'gallery_images')" class="text-xs bg-slate-800 text-white px-2 py-1 rounded font-bold hover:bg-slate-700">+ Add Image</button>
                                            </div>

                                            <div class="space-y-2">
                                                <template x-for="(img, gIndex) in (block.data.gallery_images || [])" :key="gIndex">
                                                    <div class="flex items-center gap-2 p-2 bg-white border border-slate-200 rounded shadow-sm group">
                                                        <!-- Icon/Thumb -->
                                                        <div class="w-10 h-10 bg-slate-100 rounded flex items-center justify-center overflow-hidden shrink-0">
                                                            <img :src="img.image" class="w-full h-full object-cover">
                                                        </div>
                                                        
                                                        <!-- Info -->
                                                        <div class="flex-1 min-w-0 space-y-1">
                                                            <input type="text" x-model="img.caption" placeholder="Caption" class="w-full text-[10px] border-slate-100 p-1 rounded" @input="updatePreview(index, 'gallery_images', block.data.gallery_images)">
                                                            <select x-model="img.size" class="w-full text-[10px] border-slate-100 p-1 rounded" @change="updatePreview(index, 'gallery_images', block.data.gallery_images)">
                                                                <option value="small">Small (1x1)</option>
                                                                <option value="large">Large (2x2)</option>
                                                                <option value="tall">Tall (1x2)</option>
                                                                <option value="wide">Wide (2x1)</option>
                                                            </select>
                                                        </div>

                                                        <!-- Actions -->
                                                        <button @click="block.data.gallery_images.splice(gIndex, 1); updatePreview(index, 'gallery_images', block.data.gallery_images)" class="text-slate-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                                        </button>
                                                    </div>
                                                </template>
                                                <div x-show="!block.data.gallery_images || block.data.gallery_images.length === 0" class="text-xs text-center text-slate-400 italic py-2">
                                                    No images yet.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- BLOG SPECIFIC -->
                                <template x-if="block.type === 'blog_news'">
                                     <div class="space-y-4">
                                         <!-- Header handled by Generic -->
                                         
                                         <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Display Settings</label>
                                            
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Number of Posts</label>
                                                    <select x-model="block.data.post_count" 
                                                            @change="updatePreview(index, 'post_count', $event.target.value)"
                                                            class="w-full text-sm border-slate-200 rounded-md p-2">
                                                        <option value="3">3 Posts</option>
                                                        <option value="6">6 Posts</option>
                                                        <option value="9">9 Posts</option>
                                                    </select>
                                                </div>
                                            </div>
                                         </div>

                                         <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Footer Button</label>
                                            
                                            <div class="flex items-center mb-2">
                                                <input type="checkbox" x-model="block.data.show_footer_btn" 
                                                       @change="updatePreview(index, 'show_footer_btn', $event.target.checked)"
                                                       class="rounded border-slate-300 text-brand-primary h-3 w-3 shadow-sm mr-2">
                                                <span class="text-xs text-slate-600">Show View All Button</span>
                                            </div>

                                            <div x-show="block.data.show_footer_btn" class="space-y-3 pl-5 border-l-2 border-slate-200">
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Button Text</label>
                                                    <input type="text" x-model="block.data.button_text" 
                                                        @input="updatePreview(index, 'button_text', $event.target.value)"
                                                        class="w-full text-sm border-slate-200 rounded-md p-2">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 mb-1">Button URL</label>
                                                    <input type="text" x-model="block.data.button_url" 
                                                        @input="updatePreview(index, 'button_url', $event.target.value)"
                                                        class="w-full text-sm border-slate-200 rounded-md p-2" placeholder="/blogs">
                                                </div>
                                            </div>
                                         </div>
                                     </div>
                                </template>


                                <!-- DESTINATIONS GRID -->
                                <template x-if="block.type === 'exclusive_destinations'">
                                    <div class="space-y-4">
                                        <!-- Note: Header settings are handled by GENERIC block above if type is included there. 
                                             Ensure 'destinations_grid' is REMOVED from the generic list or duplicated here if specific controls are needed. 
                                             Currently it IS in the generic list, so we only add the picker here. -->
                                        
                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Select Destinations</label>
                                            <div class="space-y-2 max-h-40 overflow-y-auto custom-scrollbar">
                                                <template x-for="dest in allDestinations" :key="dest.id">
                                                    <label class="flex items-center space-x-2 cursor-pointer hover:bg-slate-100 p-1 rounded">
                                                        <input type="checkbox" :value="dest.id"
                                                               :checked="(block.data.destination_ids || []).includes(dest.id)"
                                                               @change="
                                                                    if(!block.data.destination_ids) block.data.destination_ids = [];
                                                                    if($event.target.checked) {
                                                                        if(!block.data.destination_ids.includes(dest.id)) block.data.destination_ids.push(dest.id);
                                                                    } else {
                                                                        block.data.destination_ids = block.data.destination_ids.filter(id => id != dest.id);
                                                                    }
                                                                    updatePreview(index, 'destination_ids', block.data.destination_ids);
                                                               "
                                                               class="rounded border-slate-300 text-brand-primary">
                                                        <span class="text-xs text-slate-700 font-medium" x-text="dest.name"></span>
                                                    </label>
                                                </template>
                                            </div>
                                            <p class="text-[10px] text-slate-400 mt-2 italic">Select specific destinations to display. If none selected, latest will be shown.</p>
                                        </div>
                                    </div>
                                </template>

                                <!-- PACKAGES SLIDER -->
                                <template x-if="block.type === 'package_slider'">
                                    <div class="space-y-4">
                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Select Packages</label>
                                            <div class="space-y-2 max-h-40 overflow-y-auto custom-scrollbar">
                                                <template x-for="pkg in allPackages" :key="pkg.id">
                                                    <label class="flex items-center space-x-2 cursor-pointer hover:bg-slate-100 p-1 rounded">
                                                        <input type="checkbox" :value="pkg.id"
                                                               :checked="(block.data.package_ids || []).includes(pkg.id)"
                                                               @change="
                                                                    if(!block.data.package_ids) block.data.package_ids = [];
                                                                    if($event.target.checked) {
                                                                        if(!block.data.package_ids.includes(pkg.id)) block.data.package_ids.push(pkg.id);
                                                                    } else {
                                                                        block.data.package_ids = block.data.package_ids.filter(id => id != pkg.id);
                                                                    }
                                                                    updatePreview(index, 'package_ids', block.data.package_ids);
                                                               "
                                                               class="rounded border-slate-300 text-brand-primary">
                                                        <span class="text-xs text-slate-700 font-medium" x-text="pkg.name"></span>
                                                    </label>
                                                </template>
                                            </div>
                                            <p class="text-[10px] text-slate-400 mt-2 italic">Select specific packages. Leave empty for defaults.</p>
                                        </div>
                                    </div>
                                </template>

                                <!-- TESTIMONIALS SPECIFIC -->
                                <template x-if="block.type === 'testimonials_marquee'">
                                    <div class="space-y-4">
                                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                                            <div class="flex items-center justify-between mb-4">
                                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Source</label>
                                                <div class="flex bg-slate-200 rounded p-1">
                                                    <button @click="block.data.source = 'auto'; updatePreview(index, 'source', 'auto')" 
                                                            class="px-3 py-1 text-[10px] font-bold rounded transition"
                                                            :class="(!block.data.source || block.data.source === 'auto') ? 'bg-white shadow text-slate-800' : 'text-slate-500'">
                                                        Auto (DB)
                                                    </button>
                                                    <button @click="block.data.source = 'manual'; updatePreview(index, 'source', 'manual')" 
                                                            class="px-3 py-1 text-[10px] font-bold rounded transition"
                                                            :class="block.data.source === 'manual' ? 'bg-white shadow text-slate-800' : 'text-slate-500'">
                                                        Manual
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Manual List -->
                                            <div x-show="block.data.source === 'manual'" class="space-y-3">
                                                 <div class="flex justify-between items-center">
                                                    <label class="block text-xs font-bold text-slate-500">Testimonials List</label>
                                                    <button @click="
                                                        if(!block.data.manual_testimonials) block.data.manual_testimonials = [];
                                                        block.data.manual_testimonials.push({ name: 'User Name', role: 'Traveler', content: 'Great trip!', rating: 5 });
                                                        updatePreview(index, 'manual_testimonials', block.data.manual_testimonials);
                                                    " class="text-xs bg-slate-800 text-white px-2 py-1 rounded font-bold hover:bg-slate-700">+ Add</button>
                                                </div>
                                                
                                                <div class="space-y-2 max-h-60 overflow-y-auto custom-scrollbar">
                                                    <template x-for="(tm, tmIndex) in (block.data.manual_testimonials || [])" :key="tmIndex">
                                                        <div class="bg-white border border-slate-200 rounded p-2">
                                                            <div class="flex justify-between mb-1">
                                                                <span class="text-[10px] font-bold text-slate-400">#<span x-text="tmIndex + 1"></span></span>
                                                                <button @click="block.data.manual_testimonials.splice(tmIndex, 1); updatePreview(index, 'manual_testimonials', block.data.manual_testimonials)" class="text-red-400 hover:text-red-500">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                                                </button>
                                                            </div>
                                                            <input type="text" x-model="tm.name" placeholder="Name" class="w-full text-xs border-slate-100 mb-1" @input="updatePreview(index, 'manual_testimonials', block.data.manual_testimonials)">
                                                            <input type="text" x-model="tm.role" placeholder="Role/Country" class="w-full text-xs border-slate-100 mb-1" @input="updatePreview(index, 'manual_testimonials', block.data.manual_testimonials)">
                                                            <textarea x-model="tm.content" placeholder="Content" rows="2" class="w-full text-xs border-slate-100 mb-1" @input="updatePreview(index, 'manual_testimonials', block.data.manual_testimonials)"></textarea>
                                                            <div class="flex items-center gap-2">
                                                                <label class="text-[10px] text-slate-400">Stars:</label>
                                                                <input type="number" x-model="tm.rating" min="1" max="5" class="w-12 text-xs border-slate-100" @input="updatePreview(index, 'manual_testimonials', block.data.manual_testimonials)">
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                            </div>
                        </div>
                    </template>
                </div>
                
                <div class="mt-6 text-center">
                    <button class="text-sm font-bold text-blue-600 border border-blue-200 bg-blue-50 px-4 py-2 rounded-full hover:bg-blue-100 transition">
                        + Add New Block
                    </button>
                    <p class="text-xs text-slate-400 mt-2">More blocks coming soon</p>
                </div>
            </div>

        </div>

        <!-- Footer / Actions -->
        <div class="p-6 border-t border-slate-100 bg-white z-20 shadow-[0_-5px_20px_rgba(0,0,0,0.05)]">
            <button class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 transition-all transform active:scale-95" 
                    @click="saveChanges"
                    :disabled="isSaving"
                    :class="{ 'opacity-70 cursor-not-allowed': isSaving }">
                
                <span x-show="!isSaving">Save Changes</span>
                <span x-show="isSaving">Saving...</span>
            </button>
        </div>
    </div>

    <!-- Toggle Sidebar Button (When Closed) -->
    <button x-show="!sidebarOpen" @click="sidebarOpen = true" 
            class="fixed right-0 top-1/2 -translate-y-1/2 bg-white p-3 rounded-l-xl shadow-lg border border-r-0 border-slate-200 z-40 text-blue-600 hover:pl-4 transition-all group">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>
    </button>

    <!-- Media Picker Component -->
    <x-admin.media-picker event-name="editor-media-selected" />

    <!-- Toast Notification -->
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-6 right-6 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-[60] flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <span class="font-bold">Changes saved successfully!</span>
    </div>

    <script>
        function visualEditor() {
            return {
                sidebarOpen: true,
                deviceMode: 'desktop', // desktop, mobile
                activeBlock: 0,
                isSaving: false,
                showToast: false,

                // Data Injected from Controller
                allDestinations: @json($allDestinations ?? []),
                allPackages: @json($allPackages ?? []),
                
                page: {
                    id: {{ $page->id }},
                    title: @json($page->title),
                    content: @json($page->content ?? [])
                }, // Re-mapped to support potential null content
                



                selectBlock(index) {
                    this.activeBlock = index;
                    this.$refs.previewFrame.contentWindow.postMessage({
                        type: 'SCROLL_TO_BLOCK',
                        index: index
                    }, '*');
                },

                updatePreview(index, field, value) {
                    // Send debounce message or direct depending on performance, for now direct
                    // Clone value to avoid DataCloneError with Proxies
                    const safeValue = (typeof value === 'object' && value !== null) 
                        ? JSON.parse(JSON.stringify(value)) 
                        : value;
                    
                    const blockType = this.page.content[index].type;

                    this.$refs.previewFrame.contentWindow.postMessage({
                        type: 'UPDATE_BLOCK_FIELD',
                        index: index,
                        blockType: blockType,
                        field: field,
                        value: safeValue
                    }, '*');
                    console.log(`[Editor Shell] Sent Update: Block=${blockType}[${index}], Field=${field}`, safeValue);
                },

                // Media Picker Logic
                pendingMedia: null,

                openMediaLibrary(index, field) {
                    this.pendingMedia = { index, field };
                    // Dispatch event to open the picker
                    this.$dispatch('open-media-picker', { eventName: 'editor-media-selected' });
                },

                auditContent() {
                    console.group("🔍 Visual Editor Content Audit");
                    console.log("Checking for data mismatches...");
                    
                    this.page.content.forEach((block, index) => {
                        const type = block.type;
                        const d = block.data || {};
                        let issues = [];
                        
                        // Check Header Fields
                        if(['exclusive_destinations', 'package_slider', 'testimonials_marquee', 'blog_news', 'gallery'].includes(type)) {
                            if (!d.badge_text && d.badge) issues.push(`⚠️ Mismatch: Has 'badge' ("${d.badge}") but needs 'badge_text'`);
                            if (!d.title_prefix && !d.title_suffix && d.title) issues.push(`⚠️ Mismatch: Has 'title' ("${d.title}") but needs 'title_prefix'/'title_suffix'`);
                            if (!d.badge_text && !d.badge) issues.push(`⚪ Empty: Badge is empty`);
                        }
                        
                        if(type === 'gallery' && (!d.images || d.images.length === 0)) {
                            issues.push(`⚪ Empty: Gallery has no images`);
                        }

                        if (issues.length > 0) console.warn(`[Block ${index+1}: ${type}]`, issues);
                        else console.log(`[Block ${index+1}: ${type}]`, "✅ Data OK");
                    });
                    console.groupEnd();
                },

                init() {
                    // Ensure data structure is complete
                    this.prepareData();

                    // Debug Audit
                    setTimeout(() => this.auditContent(), 1000);

                    // Listen for selection event
                    window.addEventListener('editor-media-selected', (e) => {
                        this.handleMediaSelection(e.detail);
                    });

                    // Listen for messages from Iframe (Drag & Drop)
                    window.addEventListener('message', (e) => {
                         if (e.data.type === 'UPDATE_SPOT_COORDS') {
                             this.handleSpotUpdate(e.data);
                         }
                    });
                },

                prepareData() {
                    this.page.content.forEach(block => {
                        if (block.type === 'hero_video') {
                            if (!block.data.template) block.data.template = 'default';
                            if (!block.data.spots) block.data.spots = [];
                            if (typeof block.data.button_text === 'undefined') block.data.button_text = 'Start Adventure';
                            if (typeof block.data.button_url === 'undefined') block.data.button_url = '#packages';
                            if (typeof block.data.show_button === 'undefined') block.data.show_button = true;
                            
                            // Initialize Backgrounds Array & Migrate Legacy
                            if (!block.data.backgrounds || !Array.isArray(block.data.backgrounds)) {
                                block.data.backgrounds = [];
                                // If legacy data exists, move it to first slide
                                if (block.data.media_id || block.data.video_url) {
                                    block.data.backgrounds.push({
                                        type: (block.data.video_source === 'media_library' || !block.data.video_url) ? 'media' : 'url',
                                        id: block.data.media_id || null,
                                        url: block.data.video_url || '',
                                        mime_type: 'video/mp4' // Assume video for legacy
                                    });
                                }
                            }
                        }

                        // Initialize Defaults for About Us
                        if (block.type === 'about_us') {
                            if (typeof block.data.badge === 'undefined') block.data.badge = 'About BromoIjen';
                            if (typeof block.data.title === 'undefined') block.data.title = 'Experience The <span class="text-brand-primary">New Adventure</span> With Us';
                            if (typeof block.data.description === 'undefined') block.data.description = 'We organize premium trips to Mount Bromo, Ijen Crater, and other exotic destinations in East Java. Our goal is to provide safe, comfortable, and memorable experiences for every traveler.';
                            
                            // Features Defaults
                            if (typeof block.data.feature_1_title === 'undefined') block.data.feature_1_title = 'Trusted Travel Guide';
                            if (typeof block.data.feature_1_text === 'undefined') block.data.feature_1_text = 'Professional English speaking guides.';
                            
                            if (typeof block.data.feature_2_title === 'undefined') block.data.feature_2_title = 'Instant Booking';
                            if (typeof block.data.feature_2_text === 'undefined') block.data.feature_2_text = 'Easy and secure online booking.';
                            
                            // Footer Defaults
                            if (typeof block.data.show_cta === 'undefined') block.data.show_cta = true;
                            if (typeof block.data.cta_text === 'undefined') block.data.cta_text = 'Discover More';
                            if (typeof block.data.cta_url === 'undefined') block.data.cta_url = '/packages';
                            
                            if (typeof block.data.show_founder === 'undefined') block.data.show_founder = true;
                            if (typeof block.data.founder_name === 'undefined') block.data.founder_name = 'Agus Setiawan';
                            if (typeof block.data.founder_role === 'undefined') block.data.founder_role = 'Founder, BromoIjen';
                        }

                        // Initialize Defaults for Dest/Packages/Testimonials
                        if (block.type === 'exclusive_destinations') {
                            if (!block.data.destination_ids) block.data.destination_ids = [];
                        }
                        if (block.type === 'package_slider') {
                            if (!block.data.package_ids) block.data.package_ids = [];
                            if (typeof block.data.badge_text === 'undefined') block.data.badge_text = 'Popular Tours';
                            // Note: Title defaults contain HTML which is handled by component, but editor inputs plain text usually.
                            // We can prefill a plain version or leave empty if the user hasn't edited it yet.
                            // However, let's prefill meaningful defaults if empty.
                            if (typeof block.data.title_prefix === 'undefined') block.data.title_prefix = 'Feature';
                            if (typeof block.data.title_suffix === 'undefined') block.data.title_suffix = 'Packages';
                        }
                        
                        if (block.type === 'testimonials_marquee') {
                            if (typeof block.data.source === 'undefined') block.data.source = 'auto';
                            if (!block.data.manual_testimonials) block.data.manual_testimonials = [];
                            if (typeof block.data.badge_text === 'undefined') block.data.badge_text = 'Community Love';
                            if (typeof block.data.title_prefix === 'undefined') block.data.title_prefix = 'Trusted by';
                            if (typeof block.data.title_suffix === 'undefined') block.data.title_suffix = 'Adventurers';
                        }

                        if (block.type === 'exclusive_destinations') {
                            if (!block.data.destination_ids) block.data.destination_ids = [];
                            if (typeof block.data.badge_text === 'undefined') block.data.badge_text = 'Choose your next adventure';
                            if (typeof block.data.title_prefix === 'undefined') block.data.title_prefix = 'Exclusive';
                            if (typeof block.data.title_suffix === 'undefined') block.data.title_suffix = 'Destinations';
                            if (typeof block.data.description === 'undefined') block.data.description = '';
                        }
                    });
                },

                handleSpotUpdate(data) {
                    const { blockIndex, spotIndex, x, y } = data;
                    // blockIndex from DOM might be string
                    const idx = parseInt(blockIndex);
                    
                    if (this.page.content[idx] && this.page.content[idx].data.spots[spotIndex]) {
                        this.page.content[idx].data.spots[spotIndex].x = parseFloat(x).toFixed(2);
                        this.page.content[idx].data.spots[spotIndex].y = parseFloat(y).toFixed(2);
                    }
                },

                handleMediaSelection(asset) {
                    if (!this.pendingMedia) return;
                    const { index, field } = this.pendingMedia;

                    if (field === 'gallery_images') {
                        if (!this.page.content[index].data.gallery_images) this.page.content[index].data.gallery_images = [];
                        this.page.content[index].data.gallery_images.push({
                            id: asset.id, 
                            image: asset.url, 
                            caption: asset.alt || 'New Image',
                            size: 'small'
                        });
                        this.updatePreview(index, 'gallery_images', this.page.content[index].data.gallery_images);
                        return; 
                    }

                    if (field === 'backgrounds') {
                        if (!this.page.content[index].data.backgrounds) this.page.content[index].data.backgrounds = [];
                        this.page.content[index].data.backgrounds.push({
                            type: 'media',
                            id: asset.id,
                            url: asset.url,
                            mime_type: asset.mime_type
                        });
                        this.updatePreview(index, 'backgrounds', this.page.content[index].data.backgrounds);
                        return; // Exit early
                    }

                    // Update the data model with the Media ID
                    this.page.content[index].data[field] = asset.id;

                    // Specific handling for Secondary Media
                    if (field === 'secondary_media_id') {
                        this.page.content[index].data['secondary_source_type'] = 'media_library';
                        // Send the URL as 'secondary_image_url' so the component (which listens to this) updates instantly
                        this.updatePreview(index, 'secondary_image_url', asset.url);
                        this.updatePreview(index, 'secondary_source_type', 'media_library');
                        return;
                    }

                    // Specific handling for Main Media (media_id)
                    if (field === 'media_id') {
                         if (this.page.content[index].type === 'hero_video') {
                             this.page.content[index].data['video_source'] = 'media_library';
                             this.updatePreview(index, '_live_media_url', asset.url);
                         } else {
                             // About Us and others
                             this.page.content[index].data['source_type'] = 'media_library';
                             this.page.content[index].data['image_source'] = 'media_library';
                             
                             // Send both potential URL fields to cover image/video types
                             this.updatePreview(index, 'image_url', asset.url);
                             this.updatePreview(index, 'video_url', asset.url);
                             this.updatePreview(index, 'source_type', 'media_library');
                         }
                    } else {
                        // Standard field update
                        this.updatePreview(index, field, asset.id);
                    }
                },    

                async saveChanges() {
                    this.isSaving = true;
                    
                    // Debug Payload
                    const payload = {
                        title: this.page.title,
                        content: this.page.content
                    };
                    console.log('Saving Payload:', JSON.parse(JSON.stringify(payload)));
                    
                    // Specific Debug for Backgrounds
                    const heroBlock = this.page.content.find(b => b.type === 'hero_video');
                    if(heroBlock) {
                        console.log('Hero Backgrounds Saving:', heroBlock.data.backgrounds);
                    }

                    try {
                        const response = await fetch('{{ route('admin.visual-editor.update', $page->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await response.json();

                        console.log('Server Response Debug:', data);

                        if (data.success) {
                            // Show success toast
                            this.showToast = true;
                            setTimeout(() => this.showToast = false, 3000);
                            
                            // Reload iframe after short delay to ensure DB write
                            setTimeout(() => {
                                if (this.$refs.previewFrame && this.$refs.previewFrame.contentWindow) {
                                    this.$refs.previewFrame.contentWindow.location.reload();
                                }
                            }, 500);
                        } else {
                            alert('Error saving changes');
                        }
                    } catch (error) {
                        console.error('Save error:', error);
                        alert('Network error occurred');
                    } finally {
                        this.isSaving = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
