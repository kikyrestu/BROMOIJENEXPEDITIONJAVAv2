<?php
    $seo = new \App\Models\SeoMetadata([
        'meta_title' => 'Leave a Review',
        'meta_description' => 'Thank you for choosing Bromo Ijen Expedition. Please share your experience with us!',
    ]);
?>

<x-app-layout :seo="$seo">
    <div class="min-h-screen bg-slate-50 pt-32 pb-20">
        <div class="container mx-auto px-6 max-w-3xl">
            
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="bg-brand-dark px-8 py-10 text-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                    <div class="relative z-10">
                        <span class="inline-block bg-white/20 text-white text-sm font-bold px-4 py-1 rounded-full mb-4">Guest Review</span>
                        <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">Share Your Experience</h1>
                        <p class="text-slate-300">We'd love to hear about your journey with us.</p>
                    </div>
                </div>

                <div class="p-8 md:p-12">
                    @if ($errors->any())
                        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-8 text-sm">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('client.review.store', ['token' => $token]) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        
                        {{-- Photo Upload --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Your Photo <span class="text-slate-400 font-normal">(Optional)</span></label>
                            <div class="flex items-center space-x-6">
                                <div id="photo-preview" class="w-24 h-24 rounded-full bg-slate-100 border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden shrink-0">
                                    <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <label class="cursor-pointer bg-white px-4 py-2 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                        <span>Upload a file</span>
                                        <input id="photo-upload" name="photo" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)">
                                    </label>
                                    <p class="text-xs text-slate-500 mt-2">PNG, JPG up to 10MB</p>
                                </div>
                            </div>
                        </div>

                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Your Name</label>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:bg-white transition-all" 
                                placeholder="John Doe">
                        </div>

                        {{-- Country --}}
                        <div>
                            <label for="country" class="block text-sm font-bold text-slate-700 mb-2">Your Country</label>
                            <input type="text" name="country" id="country" required value="{{ old('country') }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:bg-white transition-all" 
                                placeholder="Australia">
                        </div>

                        {{-- Rating --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">How many stars?</label>
                            <div class="flex items-center gap-2" id="star-rating" x-data="{ rating: {{ old('rating', 5) }} }">
                                <input type="hidden" name="rating" id="rating-input" x-model="rating">
                                <template x-for="i in 5">
                                    <button type="button" 
                                            @click="rating = i" 
                                            class="focus:outline-none transition-transform hover:scale-110">
                                        <svg class="w-10 h-10 transition-colors" 
                                             :class="i <= rating ? 'text-yellow-400 fill-current' : 'text-slate-200 fill-current'" 
                                             viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div>
                            <label for="content" class="block text-sm font-bold text-slate-700 mb-2">Your Review</label>
                            <textarea name="content" id="content" rows="5" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:bg-white transition-all resize-y" 
                                placeholder="Tell us what you loved about the tour!">{{ old('content') }}</textarea>
                        </div>

                        <button type="submit" 
                                class="w-full bg-brand-primary text-white font-bold text-lg py-4 rounded-xl shadow-lg shadow-brand-primary/30 hover:bg-brand-accent hover:-translate-y-1 transition-all duration-300">
                            Submit Review
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-8 text-center text-slate-500 text-sm">
                &copy; {{ date('Y') }} Bromo Ijen Expedition. All rights reserved.
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('photo-preview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded-full" />`;
                    preview.classList.remove('border-dashed');
                    preview.classList.add('border-solid', 'border-brand-primary');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush
</x-app-layout>
