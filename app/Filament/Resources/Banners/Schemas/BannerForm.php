<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Tabs::make('Banner Details')
                    ->tabs([
                        \Filament\Schemas\Components\Tabs\Tab::make('General Info')
                            ->schema([
                                \Filament\Schemas\Components\Grid::make(2)
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('name')
                                            ->label('Internal Name')
                                            ->required(),
                                        \Filament\Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->unique(ignoreRecord: true),
                                        
                                        \Filament\Forms\Components\TextInput::make('heading')
                                            ->columnSpanFull(),
                                        \Filament\Forms\Components\TextInput::make('subheading')
                                            ->columnSpanFull(),
                                        \Filament\Forms\Components\Textarea::make('description')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        
                                        \Filament\Forms\Components\TextInput::make('cta_text')
                                            ->label('CTA Label'),
                                        \Filament\Forms\Components\TextInput::make('cta_url')
                                            ->label('CTA URL'),
                                        
                                        \Filament\Forms\Components\Select::make('type')
                                            ->options([
                                                'image' => 'Image Banner',
                                                'html' => 'Custom HTML',
                                            ])
                                            ->default('image')
                                            ->live(),
                                        \Filament\Forms\Components\Toggle::make('is_active')
                                            ->default(true),
                                    ]),
                            ]),
                        
                        \Filament\Schemas\Components\Tabs\Tab::make('Visuals')
                            ->schema([
                                \Filament\Forms\Components\FileUpload::make('image_path')
                                    ->image()
                                    ->directory('banners')
                                    ->maxSize(10240)
                                    ->visible(fn ($get) => $get('type') === 'image'),
                                
                                \Filament\Schemas\Components\Grid::make(2)
                                    ->schema([
                                        \Filament\Forms\Components\ColorPicker::make('bg_color')
                                            ->label('Background Color'),
                                        \Filament\Forms\Components\ColorPicker::make('overlay_color')
                                            ->label('Overlay Color')
                                            ->rgba(),
                                    ])
                                    ->visible(fn ($get) => $get('type') === 'image'),
                                
                                \Filament\Forms\Components\Textarea::make('html_content')
                                    ->rows(10)
                                    ->visible(fn ($get) => $get('type') === 'html'),
                            ]),
                            
                        \Filament\Schemas\Components\Tabs\Tab::make('Placement & Schedule')
                            ->schema([
                                \Filament\Schemas\Components\Grid::make(2)
                                    ->schema([
                                        \Filament\Forms\Components\DateTimePicker::make('start_date'),
                                        \Filament\Forms\Components\DateTimePicker::make('end_date'),
                                    ]),
                                
                                \Filament\Forms\Components\Repeater::make('placements')
                                    ->schema([
                                        \Filament\Forms\Components\Select::make('location')
                                            ->options([
                                                'landing_hero' => 'Landing - Hero Section',
                                                'landing_above_packages' => 'Landing - Above Packages',
                                                'landing_below_packages' => 'Landing - Below Packages',
                                                'landing_below_testimonials' => 'Landing - Below Testimonials',
                                                'landing_footer' => 'Landing - Footer Promo',
                                                'blog_hero' => 'Blog - Hero',
                                                'blog_sidebar' => 'Blog - Sidebar',
                                                'blog_footer' => 'Blog - Footer',
                                            ])
                                            ->required(),
                                        \Filament\Forms\Components\TextInput::make('priority')
                                            ->numeric()
                                            ->default(1),
                                        \Filament\Forms\Components\Toggle::make('is_active')
                                            ->default(true),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(1),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }
}
