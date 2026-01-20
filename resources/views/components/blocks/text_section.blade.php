@props(['data'])

<section class="py-16 md:py-24 container mx-auto px-6 md:px-12 lg:px-20 prose prose-lg prose-slate max-w-none">
    {!! $data['body'] ?? '' !!}
</section>
