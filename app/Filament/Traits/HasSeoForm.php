<?php

namespace App\Filament\Traits;

use App\Models\SeoMetadata;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;

trait HasSeoForm
{
    public static function getSeoFormSchema(): array
    {
        return [
            Section::make('SEO Metadata')
                ->relationship('seo')
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Meta Title')
                        ->minLength(10)
                        ->maxLength(60)
                        ->hint(fn ($state) => strlen($state) . '/60 chars')
                        ->live(onBlur: true)
                        ->required(),
                    Textarea::make('meta_description')
                        ->label('Meta Description')
                        ->minLength(50)
                        ->maxLength(160)
                        ->hint(fn ($state) => strlen($state) . '/160 chars')
                        ->live(onBlur: true)
                        ->rows(3),
                    TextInput::make('meta_keywords')
                        ->label('Meta Keywords')
                        ->placeholder('comma, separated, keywords'),
                    TextInput::make('canonical_url')
                        ->label('Canonical URL')
                        ->url(),
                    FileUpload::make('og_image')
                        ->label('Open Graph Image')
                        ->image()
                        ->directory('seo-images')
                        // ->optimize('webp'), // Disabled for local hardening (proc_open unavailable)
                        ,
                ])
                ->collapsible()
                ->collapsed(),
        ];
    }
}
