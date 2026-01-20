<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Models\Package;
use App\Models\Destination;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Traits\HasSeoForm;
use BackedEnum;

class PackageResource extends Resource
{
    use HasSeoForm;

    protected static ?string $model = Package::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-briefcase';

    protected static string | \UnitEnum | null $navigationGroup = 'Travel Management';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && ($user->role === 'admin' || $user->can('view_packages'));
    }


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Tabs::make('Package Details')
                    ->tabs([
                        // 1. Basic Info & Media
                        \Filament\Schemas\Components\Tabs\Tab::make('Overview')
                            ->schema([
                                \Filament\Schemas\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\FileUpload::make('thumbnail')
                                            ->image()
                                            ->directory('packages/thumbnails')
                                            ->columnSpan(1),
                                        Forms\Components\FileUpload::make('gallery')
                                            ->multiple()
                                            ->image()
                                            ->directory('packages/gallery')
                                            ->columnSpan(1),
                                    ]),
                                
                                \Filament\Schemas\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Package Title')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->unique(Package::class, 'slug', ignoreRecord: true),
                                        
                                        Forms\Components\Select::make('destination_id')
                                            ->label('Main Region')
                                            ->relationship('destination', 'name')
                                            ->searchable()
                                            ->required(),
                                        Forms\Components\TextInput::make('location')
                                            ->placeholder('e.g. East Java, Indonesia'),
                                        
                                        Forms\Components\Select::make('category')
                                            ->options([
                                                'Adventure' => 'Adventure',
                                                'Cultural' => 'Cultural',
                                                'Photography' => 'Photography',
                                                'Family' => 'Family',
                                                'Luxury' => 'Luxury',
                                            ])
                                            ->default('Adventure')
                                            ->required(),
                                        Forms\Components\Select::make('status')
                                            ->options([
                                                'draft' => 'Draft',
                                                'published' => 'Published',
                                            ])
                                            ->default('published')
                                            ->required(),
                                        
                                        Forms\Components\TextInput::make('rating')
                                            ->numeric()
                                            ->default(5.00)
                                            ->step(0.01)
                                            ->maxValue(5),
                                        Forms\Components\TextInput::make('review_count')
                                            ->numeric()
                                            ->default(0),
                                    ]),
                                
                                Forms\Components\Textarea::make('map_embed_url')
                                    ->label('Google Maps Embed URL')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ]),

                        // 2. Pricing & Details
                        \Filament\Schemas\Components\Tabs\Tab::make('Pricing & Details')
                            ->schema([
                                \Filament\Schemas\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('price_start_from')
                                            ->label('Price (IDR)')
                                            ->numeric()
                                            ->prefix('IDR')
                                            ->required(),
                                        Forms\Components\TextInput::make('max_participants')
                                            ->numeric()
                                            ->label('Max People'),
                                        Forms\Components\Toggle::make('is_exclusive')
                                            ->label('Exclusive Package')
                                            ->inline(false),
                                    ]),
                                
                                \Filament\Schemas\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('duration_days')->numeric()->required(),
                                        Forms\Components\TextInput::make('duration_nights')->numeric()->required(),
                                        Forms\Components\DatePicker::make('departure_date'),
                                        Forms\Components\DatePicker::make('return_date'),
                                    ]),
                            ]),

                        // 3. Content
                        \Filament\Schemas\Components\Tabs\Tab::make('Content')
                            ->schema([
                                Forms\Components\Textarea::make('short_description')->rows(3)->columnSpanFull(),
                                Forms\Components\RichEditor::make('long_description')->columnSpanFull(),
                                
                                Forms\Components\TagsInput::make('destinations_list')
                                    ->label('Destinations (List)')
                                    ->placeholder('Add destination name')
                                    ->helperText('List specific spots visited, e.g. "Penanjakan", "Kawah Bromo". result is JSON.'),
                                
                                Forms\Components\Repeater::make('highlights')
                                    ->schema([
                                        Forms\Components\TextInput::make('text')->required(),
                                    ])
                                    ->simple(
                                        Forms\Components\TextInput::make('text')->required()
                                    )
                                    ->label('Highlights'),
                            ]),

                        // 4. Itinerary
                        \Filament\Schemas\Components\Tabs\Tab::make('Itinerary')
                            ->schema([
                                Forms\Components\Repeater::make('itinerary')
                                    ->schema([
                                        Forms\Components\TextInput::make('day')
                                            ->label('Day / Time')
                                            ->required()
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('title')
                                            ->label('Activity Title')
                                            ->required()
                                            ->columnSpan(3),
                                        Forms\Components\Textarea::make('description')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(4)
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                            ]),

                        // 5. Inclusions & FAQs
                        \Filament\Schemas\Components\Tabs\Tab::make('Inclusions & FAQs')
                            ->schema([
                                Forms\Components\RichEditor::make('inclusions')->label('Includes'),
                                Forms\Components\RichEditor::make('exclusions')->label('Excludes'),
                                
                                Forms\Components\Repeater::make('faqs')
                                    ->schema([
                                        Forms\Components\TextInput::make('question')->required(),
                                        Forms\Components\Textarea::make('answer')->required(),
                                    ])
                                    ->label('FAQs')
                                    ->collapsed(),
                                
                                Forms\Components\Textarea::make('wa_template_message')
                                    ->label('WhatsApp Message Template')
                                    ->rows(2),
                            ]),

                        // 6. SEO
                        \Filament\Schemas\Components\Tabs\Tab::make('SEO')
                            ->schema(static::getSeoFormSchema()),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('destination.name')->sortable(),
                Tables\Columns\TextColumn::make('price_start_from')->money('IDR'),
                Tables\Columns\TextColumn::make('duration_days')->label('Days'),
                Tables\Columns\IconColumn::make('is_exclusive')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('destination')
                    ->relationship('destination', 'name'),
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
            \App\Filament\Resources\PackageResource\RelationManagers\InquiryLogsRelationManager::class,
            \App\Filament\Resources\PackageResource\RelationManagers\PriceAdjustmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
