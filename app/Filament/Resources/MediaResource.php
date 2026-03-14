<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages;
use App\Models\Media;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Actions\Action;

use Filament\Schemas\Schema;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-photo';
    protected static string | \UnitEnum | null $navigationGroup = 'System';
    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && ($user->role === 'admin' || $user->can('view_media'));
    }


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('file_path')
                    ->label('File')
                    ->disk('public') // Force public disk
                    ->required()
                    ->directory('media')
                    ->preserveFilenames()
                    ->maxSize(102400) // 100MB
                    ->acceptedFileTypes(['image/*', 'video/*', 'application/pdf'])
                    ->imagePreviewHeight('250')
                    ->loadingIndicatorPosition('left')
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadButtonPosition('left')
                    ->uploadProgressIndicatorPosition('left')
                    ->columnSpanFull(),
                    
                TextInput::make('name')
                    ->label('Name/Alt Text')
                    ->placeholder('Enter a descriptive name')
                    ->columnSpanFull(),
                    
                Select::make('type')
                    ->options([
                        'image' => 'Image',
                        'video' => 'Video', 
                        'document' => 'Document'
                    ])
                    ->default('image')
                    ->required(),

                TextInput::make('alt_text')
                     ->label('Alt Text (SEO)')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    ViewColumn::make('file_path')
                        ->view('filament.tables.columns.media-preview')
                        ->extraAttributes(['class' => 'w-full']),
                    
                    Stack::make([
                        TextColumn::make('name')
                            ->weight('bold')
                            ->searchable(),
                        TextColumn::make('type')
                            ->badge()
                            ->color('gray'),
                        TextColumn::make('url_copy')
                            ->default('Copy Link')
                            ->icon('heroicon-m-clipboard')
                            ->color('primary')
                            ->copyable()
                            ->copyMessage('URL copied to clipboard')
                            ->copyableState(fn (Media $record) => \Illuminate\Support\Facades\Storage::disk('public')->url($record->file_path))
                            ->size('xs')
                            ->extraAttributes(['class' => 'cursor-pointer hover:underline']),
                    ])->space(1)->extraAttributes(['class' => 'p-4']),
                ])->space(0),
            ])
            ->contentGrid([
                'md' => 3,
                'xl' => 4,
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
                \Filament\Actions\DeleteAction::make(),
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
            'index' => Pages\ListMedia::route('/'),
            'create' => Pages\CreateMedia::route('/create'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
        ];
    }
}
