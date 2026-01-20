@props(['id' => 'mediaPicker', 'eventName' => 'media-selected'])

<div x-data="mediaPicker()" 
     x-show="isOpen" 
     @open-media-picker.window="open($event.detail)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
     style="display: none;">

    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl h-[80vh] flex flex-col overflow-hidden" @click.outside="close()">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-lg font-bold text-slate-800">Media Library</h3>
            <button @click="close()" class="text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Toolbar -->
        <div class="px-6 py-3 border-b border-slate-200 flex items-center justify-between bg-white">
            <div class="flex gap-4">
                <button @click="tab = 'library'" :class="{'text-blue-600 font-bold border-b-2 border-blue-600': tab === 'library', 'text-slate-500 hover:text-slate-700': tab !== 'library'} " class="pb-3 text-sm transition">
                    Library
                </button>
                <button @click="tab = 'upload'" :class="{'text-blue-600 font-bold border-b-2 border-blue-600': tab === 'upload', 'text-slate-500 hover:text-slate-700': tab !== 'upload'} " class="pb-3 text-sm transition">
                    Upload New
                </button>
            </div>
            <div x-show="tab === 'library'">
                <input type="text" x-model="search" placeholder="Search files..." class="text-sm border-slate-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto p-6 bg-slate-100">
            
            <!-- Library Tab -->
            <div x-show="tab === 'library'">
                <div x-show="loading" class="flex justify-center py-20">
                    <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <div x-show="!loading && assets.length === 0" class="text-center py-20 text-slate-500">
                    No media found. Upload something!
                </div>

                <div x-show="!loading && assets.length > 0" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    <template x-for="asset in filteredAssets" :key="asset.path">
                        <div @click="selectAsset(asset)" 
                             class="group relative aspect-square bg-white rounded-lg border cursor-pointer overflow-hidden transition-all hover:shadow-md"
                             :class="{'ring-2 ring-blue-500 border-transparent': selectedAsset && selectedAsset.path === asset.path, 'border-slate-200': !selectedAsset || selectedAsset.path !== asset.path}">
                            
                            <template x-if="asset.type.includes('image')">
                                <img :src="asset.url" class="w-full h-full object-cover">
                            </template>
                            
                            <template x-if="asset.type.includes('video')">
                                <div class="w-full h-full bg-slate-900 group-hover:bg-black transition relative">
                                    <video :src="asset.url" 
                                           class="w-full h-full object-cover pointer-events-none" 
                                           muted playsinline preload="metadata"
                                           onmouseover="this.play()" 
                                           onmouseout="this.pause(); this.currentTime = 0;">
                                    </video>
                                    
                                    <!-- Play Icon Overlay (Fades out on hover) -->
                                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none group-hover:opacity-0 transition">
                                        <div class="bg-black/30 rounded-full p-2 backdrop-blur-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-medium p-2 text-center">
                                <span x-text="asset.name"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Upload Tab -->
            <div x-show="tab === 'upload'" class="h-full flex flex-col items-center justify-center border-2 border-dashed border-slate-300 rounded-xl bg-white p-10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-slate-300 mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3.75m-3-3.75l-3 3.75M12 9.75V4.5m0 2.25l6 6 6-6M6 4.5v15" />
                </svg>
                <p class="text-lg font-medium text-slate-600 mb-2">Drag and drop your files here</p>
                <p class="text-sm text-slate-400 mb-6">or click to browse</p>
                
                <input type="file" x-ref="fileInput" @change="handleUpload" class="hidden" accept="image/*,video/*">
                <button @click="$refs.fileInput.click()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition">
                    Select Files
                </button>
                
                <div x-show="uploading" class="mt-4 w-full max-w-md">
                    <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 transition-all duration-300" style="width: 100%"></div>
                    </div>
                    <p class="text-xs text-center text-slate-500 mt-1">Uploading...</p>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-end gap-3">
            <button @click="close()" class="px-4 py-2 text-slate-600 hover:text-slate-800 font-medium">Cancel</button>
            <button @click="confirmSelection()" 
                    :disabled="!selectedAsset"
                    :class="{'opacity-50 cursor-not-allowed': !selectedAsset}"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition shadow-sm">
                Insert Selected Media
            </button>
        </div>

    </div>

    <script>
        function mediaPicker() {
            return {
                isOpen: false,
                tab: 'library',
                loading: false,
                uploading: false,
                assets: [],
                search: '',
                selectedAsset: null,
                callbackEvent: '{{ $eventName }}',

                get filteredAssets() {
                    if (!this.search) return this.assets;
                    return this.assets.filter(a => a.name.toLowerCase().includes(this.search.toLowerCase()));
                },

                open(options = {}) {
                    this.isOpen = true;
                    this.callbackEvent = options.eventName || '{{ $eventName }}';
                    this.fetchAssets();
                },

                close() {
                    this.isOpen = false;
                    this.selectedAsset = null;
                },

                async fetchAssets() {
                    this.loading = true;
                    try {
                        const res = await fetch('{{ route("admin.api.media-library.index") }}');
                        const json = await res.json();
                        this.assets = json.data;
                    } catch (e) {
                        console.error('Failed to load media', e);
                    } finally {
                        this.loading = false;
                    }
                },

                selectAsset(asset) {
                    this.selectedAsset = asset;
                },

                async handleUpload(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    this.uploading = true;
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    try {
                        const res = await fetch('{{ route("admin.api.media-library.store") }}', {
                            method: 'POST',
                            body: formData
                        });
                        if (res.ok) {
                            const newAsset = await res.json();
                            this.assets.unshift(newAsset);
                            this.selectedAsset = newAsset;
                            this.tab = 'library';
                        }
                    } catch (e) {
                        console.error('Upload failed', e);
                        alert('Upload failed');
                    } finally {
                        this.uploading = false;
                        e.target.value = ''; // Reset input
                    }
                },

                confirmSelection() {
                    if (!this.selectedAsset) return;
                    
                    // Dispatch event with selected asset data
                    this.$dispatch(this.callbackEvent, this.selectedAsset);
                    this.close();
                }
            }
        }
    </script>
</div>
