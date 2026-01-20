<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\Builder::make('content')
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

                        // 2. Exclusive Destinations
                        Forms\Components\Builder\Block::make('exclusive_destinations')
                            ->label('Exclusive Destinations Section'),

                        // 3. Package Slider
                        Forms\Components\Builder\Block::make('package_slider')
                            ->label('Package Slider Section'),
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
