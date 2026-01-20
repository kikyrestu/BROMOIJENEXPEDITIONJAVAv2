<div class="w-full h-full relative" style="min-height: 150px;">
    @php
        $filePath = $record->file_path;
        $url = \Illuminate\Support\Facades\Storage::url($filePath);
        $type = $record->type ?? 'image';
    @endphp

    @if($type === 'video')
        <video 
            src="{{ $url }}" 
            class="w-full h-full object-cover aspect-square rounded-t-xl" 
            controls 
            preload="metadata"
            style="max-height: 250px;"
        >
            Your browser does not support the video tag.
        </video>
        <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
            Video
        </div>
    @elseif($type === 'image')
        <img 
            src="{{ $url }}" 
            alt="{{ $record->alt_text ?? $record->name }}" 
            class="w-full h-full object-cover aspect-square rounded-t-xl" 
            loading="lazy" 
        />
    @else
        <div class="w-full h-full aspect-square bg-slate-100 flex flex-col items-center justify-center rounded-t-xl text-slate-400 p-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mb-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <span class="text-xs text-center break-all">{{ $record->name }}</span>
        </div>
    @endif
</div>
