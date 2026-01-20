<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    use \Pboivin\FilamentPeek\Pages\Concerns\HasBuilderPreview;

    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getBuilderPreviewView(string $builderName): ?string
    {
        return 'home';
    }

    protected static function getBuilderEditorSchema(string $builderName): array
    {
        return PageResource::form(
            \Filament\Forms\Form::make(new static)
                ->columns(1)
                ->schema([])
        )->getComponents();
    }

    protected function getBuilderPreviewData(string $builderName): array
    {
        // Must match HomeController logic to support the view
        $destinations = \App\Models\Destination::where('is_featured', true)->take(3)->get();
        $packages = \App\Models\Package::with('destination')->take(6)->get();
        $latest_posts = \App\Models\Blog::where('status', 'published')->latest()->take(3)->get();
        $testimonials = \App\Models\Testimonial::where('status', 'published')->latest()->take(6)->get();

        // MERGE LIVE FORM DATA into the Page object
        // This ensures the preview shows UNSAVED changes (Live Edit)
        $data = $this->form->getState();
        $page = $this->record;
        
        if (isset($data['content'])) {
            $page->content = $data['content'];
        }
        
        // Also update title/slug if relevant for preview
        if (isset($data['title'])) $page->title = $data['title'];

        return [
            'page' => $page, 
            'destinations' => $destinations,
            'packages' => $packages,
            'latest_posts' => $latest_posts,
            'testimonials' => $testimonials,
        ];
    }
}
