<?php

namespace App\Filament\Resources\Galleries;

use App\Filament\Resources\Galleries\Pages\CreateGallery;
use App\Filament\Resources\Galleries\Pages\EditGallery;
use App\Filament\Resources\Galleries\Pages\ListGalleries;
use App\Models\Gallery;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-photo';

    protected static \UnitEnum | string | null $navigationGroup = 'Site Appearance';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && ($user->role === 'admin' || $user->can('view_galleries'));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Forms\Components\TextInput::make('title')
                    ->maxLength(255),
                \Filament\Forms\Components\FileUpload::make('image_path')
                    ->image()
                    ->disk('public')
                    ->directory('gallery-images')
                    ->required()
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('category')
                    ->options([
                        'nature' => 'Nature',
                        'group' => 'Group',
                        'transport' => 'Transport',
                    ])
                    ->required(),
                \Filament\Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('image_path')->label('Image'),
                \Filament\Tables\Columns\TextColumn::make('title')->searchable(),
                \Filament\Tables\Columns\TextColumn::make('category')->badge()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('sort_order')->numeric()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'nature' => 'Nature',
                        'group' => 'Group',
                        'transport' => 'Transport',
                    ]),
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
            'index' => ListGalleries::route('/'),
            'create' => CreateGallery::route('/create'),
            'edit' => EditGallery::route('/{record}/edit'),
        ];
    }
}
