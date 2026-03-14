<?php

namespace App\Filament\Resources\Blogs;

use App\Filament\Resources\Blogs\Pages\CreateBlog;
use App\Filament\Resources\Blogs\Pages\EditBlog;
use App\Filament\Resources\Blogs\Pages\ListBlogs;
use App\Models\Blog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class BlogResource extends Resource
{
    use \App\Filament\Traits\HasSeoForm;

    protected static ?string $model = Blog::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static \UnitEnum | string | null $navigationGroup = 'Publication';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && ($user->role === 'admin' || $user->can('view_blogs'));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Tabs::make('Blog Editor')
                    ->tabs([
                        \Filament\Schemas\Components\Tabs\Tab::make('Write')
                            ->schema([
                                \Filament\Schemas\Components\Grid::make(3)
                                    ->schema([
                                        // Main Content (Left, 2 cols)
                                        \Filament\Schemas\Components\Group::make()
                                            ->columnSpan(2)
                                            ->schema([
                                                \Filament\Schemas\Components\Section::make('Article')
                                                    ->schema([
                                                        \Filament\Forms\Components\TextInput::make('title')
                                                            ->required()
                                                            ->live(onBlur: true)
                                                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                                                        \Filament\Forms\Components\TextInput::make('slug')
                                                            ->required()
                                                            ->unique(Blog::class, 'slug', ignoreRecord: true),
                                                        
                                                        \Filament\Forms\Components\Textarea::make('excerpt')
                                                            ->label('Short Description / Excerpt')
                                                            ->rows(3)
                                                            ->maxLength(500)
                                                            ->columnSpanFull(),
                                                        
                                                        \Filament\Forms\Components\RichEditor::make('body')
                                                            ->label('Full Article Content')
                                                            ->columnSpanFull()
                                                            ->fileAttachmentsDirectory('blog-content'),
                                                    ]),
                                            ]),

                                        // Sidebar (Right, 1 col)
                                        \Filament\Schemas\Components\Group::make()
                                            ->columnSpan(1)
                                            ->schema([
                                                \Filament\Schemas\Components\Section::make('Status & Visibility')
                                                    ->schema([
                                                        \Filament\Forms\Components\Select::make('status')
                                                            ->options([
                                                                'draft' => 'Draft',
                                                                'published' => 'Published',
                                                            ])
                                                            ->default('draft')
                                                            ->required(),
                                                        \Filament\Forms\Components\Toggle::make('is_featured')
                                                            ->label('Featured Post'),
                                                        \Filament\Forms\Components\DateTimePicker::make('published_at')
                                                            ->label('Publish Date')
                                                            ->default(now()),
                                                    ]),

                                                \Filament\Schemas\Components\Section::make('Metadata')
                                                    ->schema([
                                                        \Filament\Forms\Components\FileUpload::make('thumbnail_path')
                                                            ->label('Featured Image')
                                                            ->image()
                                                            ->disk('public')
                                                            ->directory('blog-thumbnails')
                                                            ->imageResizeMode('cover')
                                                            ->imageCropAspectRatio('2:1'),
                                                        
                                                        \Filament\Forms\Components\Select::make('category_id')
                                                            ->label('Category')
                                                            ->relationship('category', 'name', fn ($query) => $query->where('type', 'blog'))
                                                            ->searchable()
                                                            ->preload()
                                                            ->required()
                                                            ->createOptionForm([
                                                                \Filament\Forms\Components\TextInput::make('name')
                                                                    ->required()
                                                                    ->live(onBlur: true)
                                                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                                                                \Filament\Forms\Components\TextInput::make('slug')->required(),
                                                                \Filament\Forms\Components\Hidden::make('type')->default('blog'),
                                                            ]),
                                                        
                                                        \Filament\Forms\Components\TagsInput::make('tags')
                                                            ->placeholder('Add tags...'),
                                                    ]),

                                                \Filament\Schemas\Components\Section::make('Author Info')
                                                    ->schema([
                                                        \Filament\Forms\Components\Select::make('author_id')
                                                            ->label('Author Account')
                                                            ->relationship('author', 'name')
                                                            ->default(auth()->id())
                                                            ->searchable()
                                                            ->required(),
                                                        \Filament\Forms\Components\TextInput::make('author_name')
                                                            ->label('Display Name Override')
                                                            ->placeholder('e.g. Guest Writer'),
                                                        \Filament\Forms\Components\TextInput::make('read_time')
                                                            ->label('Read Time')
                                                            ->placeholder('e.g. 5 min read'),
                                                    ]),
                                            ]),
                                    ]),
                            ]),
                        \Filament\Schemas\Components\Tabs\Tab::make('SEO')
                            ->schema(static::getSeoFormSchema()),
                    ])
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('thumbnail_path')->disk('public')->label('Image'),
                \Filament\Tables\Columns\TextColumn::make('title')->searchable()->sortable()->limit(30),
                \Filament\Tables\Columns\TextColumn::make('category.name')->label('Category')->badge()->color('info'),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                    ]),
                \Filament\Tables\Columns\IconColumn::make('is_featured')->boolean(),
                \Filament\Tables\Columns\TextColumn::make('author.name')->label('Author')->limit(20),
                \Filament\Tables\Columns\TextColumn::make('published_at')->date()->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),
                \Filament\Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name', fn ($query) => $query->where('type', 'blog')),
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
            'index' => ListBlogs::route('/'),
            'create' => CreateBlog::route('/create'),
            'edit' => EditBlog::route('/{record}/edit'),
        ];
    }
}
