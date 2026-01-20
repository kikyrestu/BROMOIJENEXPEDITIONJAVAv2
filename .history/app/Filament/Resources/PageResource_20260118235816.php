<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\Builder::make('content')
                    ->label('Page Content (Visual Editor)')
                    ->blocks([
                        // 1. Hero Video Block
                        Forms\Components\Builder\Block::make('hero_video')
                            ->schema([
                                Forms\Components\TextInput::make('heading')
                                    ->label('Heading')
                                    ->default('Explore Bromo & Ijen'),
                                Forms\Components\Textarea::make('subheading')
                                    ->label('Subheading')
                                    ->rows(2),
                                
                                Forms\Components\Select::make('video_source')
                                    ->options([
                                        'url' => 'URL (YouTube/Direct)',
                                        'upload' => 'Upload File',
                                        // 'media_library' => 'Media Library', 
                                    ])
                                    ->default('url')
                                    ->reactive(),
                                
                                Forms\Components\TextInput::make('video_url')
                                    ->visible(fn (Forms\Get $get) => $get('video_source') === 'url'),
                                
                                Forms\Components\FileUpload::make('video_file')
                                    ->disk('public')
                                    ->directory('hero-videos')
                                    ->visible(fn (Forms\Get $get) => $get('video_source') === 'upload'),

                                Forms\Components\Toggle::make('show_button')
                                    ->default(true)
                                    ->reactive(),

                                Forms\Components\TextInput::make('button_text')
                                    ->default('Start Adventure')
                                    ->visible(fn (Forms\Get $get) => $get('show_button')),
                                
                                Forms\Components\TextInput::make('button_url')
                                    ->default('#packages')
                                    ->visible(fn (Forms\Get $get) => $get('show_button')),

                                // Backgrounds Repeater (Optional, for slider)
                                Forms\Components\Repeater::make('backgrounds')
                                    ->schema([
                                        Forms\Components\TextInput::make('url')->label('Image/Video URL'),
                                        Forms\Components\Select::make('type')->options(['image'=>'Image','video'=>'Video'])->default('image'),
                                    ])
                                    ->collapsed()
                                    ->label('Background Slides (Optional)'),
                            ]),

                        // 2. About Us Block
                        Forms\Components\Builder\Block::make('about_us')
                            ->label('About Us Section')
                            ->schema([
                                Forms\Components\TextInput::make('badge')->default('About BromoIjen'),
                                Forms\Components\TextInput::make('title')->default('Experience The New Adventure With Us'),
                                Forms\Components\Textarea::make('description')->rows(3),
                                Forms\Components\FileUpload::make('main_image')->image()->directory('about'),
                                Forms\Components\FileUpload::make('secondary_image')->image()->directory('about'),
                                Forms\Components\Repeater::make('features')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')->required(),
                                        Forms\Components\TextInput::make('description'),
                                    ])
                                    ->defaultItems(2),
                            ]),

                        // 3. Exclusive Destinations
                        Forms\Components\Builder\Block::make('exclusive_destinations')
                            ->label('Exclusive Destinations Section')
                            ->schema([
                                Forms\Components\TextInput::make('badge')->default('Choose your next adventure'),
                                Forms\Components\TextInput::make('title')->default('Exclusive <span class="relative inline-block text-brand-primary">Destinations</span>'),
                            ]),

                        // 4. Gallery Block
                        Forms\Components\Builder\Block::make('gallery')
                            ->label('Gallery Section')
                            ->schema([
                                Forms\Components\TextInput::make('badge')->default('Our Memories'),
                                Forms\Components\TextInput::make('title')->default('Capture The Moments'),
                                Forms\Components\Textarea::make('description')->rows(2),
                                Forms\Components\Repeater::make('images')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')->image()->required(),
                                        Forms\Components\TextInput::make('caption'),
                                        Forms\Components\Select::make('size')
                                            ->options([
                                                'small' => 'Small Square',
                                                'large' => 'Large Square',
                                                'wide' => 'Wide Rectangle',
                                                'tall' => 'Tall Rectangle',
                                            ])->default('small'),
                                    ])
                                    ->grid(2),
                            ]),

                        // 5. Package Slider
                        Forms\Components\Builder\Block::make('package_slider')
                            ->label('Package Slider Section')
                            ->schema([
                                Forms\Components\TextInput::make('badge')->default('Popular Tours'),
                                Forms\Components\TextInput::make('title')->default('Feature <span class="text-brand-primary font-hand italic">Packages</span>'),
                            ]),

                        // 6. Testimonials Marquee
                        Forms\Components\Builder\Block::make('testimonials_marquee')
                            ->label('Testimonials Marquee')
                            ->schema([
                                Forms\Components\TextInput::make('badge')->default('Community Love'),
                                Forms\Components\TextInput::make('title')->default('Trusted by <span class="text-brand-primary">Adventurers</span>'),
                                Forms\Components\Repeater::make('testimonials')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')->required(),
                                        Forms\Components\TextInput::make('role')->label('Role / Location'),
                                        Forms\Components\Textarea::make('content')->rows(3),
                                        Forms\Components\FileUpload::make('avatar')->avatar()->disk('public')->directory('testimonials'),
                                        Forms\Components\TextInput::make('rating')->numeric()->default(5)->minValue(1)->maxValue(5),
                                    ])
                                    ->collapsed()
                                    ->label('Testimonial Items'),
                            ]),

                        // 7. Blog & News
                        Forms\Components\Builder\Block::make('blog_news')
                            ->label('Blog & News Section')
                            ->schema([
                                Forms\Components\TextInput::make('badge')->default('Blog & News'),
                                Forms\Components\TextInput::make('title')->default('Explore Blogs <span class="text-brand-primary font-hand italic">And News</span>'),
                                Forms\Components\Toggle::make('auto_fetch')
                                    ->label('Auto-fetch Latest Posts')
                                    ->default(true)
                                    ->reactive(),
                                Forms\Components\Repeater::make('posts')
                                    ->visible(fn (Forms\Get $get) => !$get('auto_fetch'))
                                    ->schema([
                                        Forms\Components\TextInput::make('title')->required(),
                                        Forms\Components\TextInput::make('category'),
                                        Forms\Components\FileUpload::make('image')->image()->directory('blog-covers'),
                                        Forms\Components\Textarea::make('excerpt'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('slug')->searchable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
