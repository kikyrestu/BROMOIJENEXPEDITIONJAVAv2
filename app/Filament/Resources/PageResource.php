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
                    ->afterStateUpdated(fn (string $operation, $state, \Filament\Schemas\Components\Utilities\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

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
                                    ])
                                    ->default('url')
                                    ->reactive(),
                                
                                Forms\Components\TextInput::make('video_url')
                                    ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('video_source') === 'url')
                                    ->helperText('Enter a direct video URL (mp4) or YouTube link.'),
                                
                                Forms\Components\FileUpload::make('video_file')
                                    ->disk('public')
                                    ->directory('hero-videos')
                                    ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime', 'video/x-msvideo'])
                                    ->maxSize(102400)
                                    ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('video_source') === 'upload'),

                                Forms\Components\Toggle::make('show_button')
                                    ->default(true)
                                    ->reactive(),

                                Forms\Components\TextInput::make('button_text')
                                    ->default('Start Adventure')
                                    ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('show_button')),
                                
                                Forms\Components\TextInput::make('button_url')
                                    ->default('#packages')
                                    ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('show_button')),

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
                                Forms\Components\FileUpload::make('main_image')
                                    ->image()
                                    ->directory('about')
                                    ->maxSize(10240)
                                    ->helperText('If seeded from database, this might appear empty. Upload a new image to replace.'),
                                Forms\Components\FileUpload::make('secondary_image')
                                    ->image()
                                    ->directory('about')
                                    ->maxSize(10240)
                                    ->helperText('If seeded from database, this might appear empty. Upload a new image to replace.'),
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
                                Forms\Components\TextInput::make('badge_text')->default('Choose your next adventure'),
                                Forms\Components\TextInput::make('title_prefix')->default('Exclusive')->label('Title Prefix'),
                                Forms\Components\TextInput::make('title_suffix')->default('Destinations')->label('Title Suffix'),
                                Forms\Components\Placeholder::make('dynamic_content_note')
                                    ->label('Dynamic Content')
                                    ->content('This section automatically displays "Featured" destinations from the Destinations menu.'),
                            ]),

                        // 4. Gallery Block
                        Forms\Components\Builder\Block::make('gallery')
                            ->label('Gallery Section')
                            ->schema([
                                Forms\Components\TextInput::make('badge_text')->default('Our Memories'),
                                Forms\Components\TextInput::make('title_prefix')->default('Capture The')->label('Title Prefix'),
                                Forms\Components\TextInput::make('title_suffix')->default('Moments')->label('Title Suffix'),
                                Forms\Components\Textarea::make('description')->rows(2),
                                Forms\Components\Repeater::make('images')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->image()
                                            ->required()
                                            ->directory('gallery')
                                            ->maxSize(10240)
                                            ->helperText('Seeded URLs will not show preview. Upload to change.'),
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
                                Forms\Components\TextInput::make('badge_text')->default('Popular Tours'),
                                Forms\Components\TextInput::make('title_prefix')->default('Feature')->label('Title Prefix'),
                                Forms\Components\TextInput::make('title_suffix')->default('Packages')->label('Title Suffix'),
                                Forms\Components\Select::make('package_ids')
                                    ->label('Select Packages')
                                    ->multiple()
                                    ->options(\App\Models\Package::pluck('name', 'id'))
                                    ->searchable()
                                    ->helperText('Leave empty to automatically display the latest 6 packages.'),
                            ]),

                        // 6. Testimonials Marquee
                        Forms\Components\Builder\Block::make('testimonials_marquee')
                            ->label('Testimonials Marquee')
                            ->schema([
                                Forms\Components\TextInput::make('badge_text')->default('Community Love'),
                                Forms\Components\TextInput::make('title_prefix')->default('Trusted by')->label('Title Prefix'),
                                Forms\Components\TextInput::make('title_suffix')->default('Adventurers')->label('Title Suffix'),
                                Forms\Components\Repeater::make('testimonials')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')->required(),
                                        Forms\Components\TextInput::make('role')->label('Role / Location'),
                                        Forms\Components\Textarea::make('content')->rows(3),
                                        Forms\Components\FileUpload::make('avatar')
                                            ->avatar()
                                            ->disk('public')
                                            ->directory('testimonials')
                                            ->maxSize(2048)
                                            ->helperText('Seeded URLs will not show preview. Upload to change.'),
                                        Forms\Components\TextInput::make('rating')->numeric()->default(5)->minValue(1)->maxValue(5),
                                    ])
                                    ->collapsed()
                                    ->label('Testimonial Items'),
                            ]),

                        // 7. Blog & News
                        Forms\Components\Builder\Block::make('blog_news')
                            ->label('Blog & News Section')
                            ->schema([
                                Forms\Components\TextInput::make('badge_text')->default('Blog & News'),
                                Forms\Components\TextInput::make('title_prefix')->default('Explore Blogs')->label('Title Prefix'),
                                Forms\Components\TextInput::make('title_suffix')->default('And News')->label('Title Suffix'),
                                Forms\Components\Toggle::make('auto_fetch')
                                    ->label('Auto-fetch Latest Posts')
                                    ->default(true)
                                    ->reactive(),
                                Forms\Components\Placeholder::make('auto_fetch_help')
                                    ->label('Note')
                                    ->content('When "Auto-fetch" is enabled, posts are pulled from the Blogs menu automatically.')
                                    ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('auto_fetch')),
                                Forms\Components\Repeater::make('posts')
                                    ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => !$get('auto_fetch'))
                                    ->schema([
                                        Forms\Components\TextInput::make('title')->required(),
                                        Forms\Components\TextInput::make('category'),
                                        Forms\Components\FileUpload::make('image')->image()->directory('blog-covers')->maxSize(10240),
                                        Forms\Components\Textarea::make('excerpt'),
                                    ]),
                            ]),

                        // 8. Hotspots (Interactive Map)
                        Forms\Components\Builder\Block::make('hotspots')
                            ->label('Hotspots (Interactive Map)')
                            ->schema([
                                Forms\Components\Select::make('image_source')
                                    ->options([
                                        'upload' => 'Upload Image',
                                        'url' => 'Image URL',
                                        'media_library' => 'Media Library',
                                    ])
                                    ->default('upload')
                                    ->reactive(),
                                Forms\Components\FileUpload::make('image_file')
                                    ->image()
                                    ->directory('hotspots')
                                    ->maxSize(10240)
                                    ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('image_source') === 'upload'),
                                Forms\Components\TextInput::make('image_url')
                                    ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('image_source') === 'url'),
                                Forms\Components\Select::make('media_id')
                                    ->relationship('media', 'name') // Assuming Media model relationship exists or generic select
                                    // If no relationship, uses generic select. For now simpler:
                                    // ->options(\App\Models\Media::pluck('name', 'id'))
                                    ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('image_source') === 'media_library')
                                    ->helperText('Select from Media Library (Not fully implemented in this form, using placeholder logic)'),

                                Forms\Components\Repeater::make('spots')
                                    ->schema([
                                        Forms\Components\TextInput::make('x')->numeric()->label('X Position (%)')->required(),
                                        Forms\Components\TextInput::make('y')->numeric()->label('Y Position (%)')->required(),
                                        Forms\Components\Select::make('destination_id')
                                            ->options(\App\Models\Destination::pluck('name', 'id'))
                                            ->searchable()
                                            ->label('Select Destination'),
                                        Forms\Components\TextInput::make('tooltip_override')->label('Custom Label (Optional)'),
                                    ])
                                    ->grid(2)
                                    ->defaultItems(1),
                            ]),

                        // 9. Text Section
                        Forms\Components\Builder\Block::make('text_section')
                            ->label('Text Section (Prose)')
                            ->schema([
                                Forms\Components\RichEditor::make('body')
                                    ->label('Content')
                                    ->required(),
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
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
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
