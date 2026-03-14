<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tag';

    protected static string | \UnitEnum | null $navigationGroup = 'Travel Management';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && ($user->role === 'admin' || $user->can('view_packages'));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(Category::class, 'slug', ignoreRecord: true),
                        Forms\Components\Select::make('type')
                            ->options([
                                'package' => 'Package',
                                'blog' => 'Blog',
                            ])
                            ->default('package')
                            ->required(),
                        Forms\Components\TextInput::make('icon')
                            ->label('Icon (emoji or HTML)')
                            ->placeholder('e.g. 🏔️ or <svg>...</svg>'),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('show_in_navbar')
                            ->label('Show in Navbar')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'package' => 'success',
                        'blog' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('packages_count')
                    ->label('Packages')
                    ->counts('packages'),
                Tables\Columns\IconColumn::make('show_in_navbar')->boolean()->label('In Navbar'),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'package' => 'Package',
                        'blog' => 'Blog',
                    ]),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
