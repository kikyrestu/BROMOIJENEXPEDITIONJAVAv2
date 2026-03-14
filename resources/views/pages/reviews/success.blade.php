<?php
    $seo = new \App\Models\SeoMetadata([
        'meta_title' => 'Review Submitted',
        'meta_description' => 'Thank you for your review!',
    ]);
?>

<x-app-layout :seo="$seo">
    <div class="min-h-screen bg-slate-50 flex items-center justify-center py-20 px-6">
        <div class="bg-white p-10 md:p-16 rounded-3xl shadow-xl max-w-lg w-full text-center">
            
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner shadow-green-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-3xl font-extrabold text-brand-dark mb-4">Thank You!</h1>
            
            <p class="text-slate-500 text-lg mb-8 leading-relaxed">
                Your review has been successfully submitted. We appreciate you taking the time to share your experience with us and future travelers!
            </p>

            <a href="{{ route('home') }}" class="inline-block bg-brand-primary text-white font-bold py-3 px-8 rounded-full shadow-lg shadow-brand-primary/30 hover:bg-brand-accent hover:-translate-y-1 transition-all duration-300">
                Return to Homepage
            </a>
            
        </div>
    </div>
</x-app-layout>
