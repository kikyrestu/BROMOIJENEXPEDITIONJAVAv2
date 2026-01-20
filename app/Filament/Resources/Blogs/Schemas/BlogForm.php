<?php

namespace App\Filament\Resources\Blogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('author_id')
                    ->relationship('author', 'name')
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name'),
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('body')
                    ->columnSpanFull(),
                TextInput::make('thumbnail_path'),
                TextInput::make('view_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('published_at'),
            ]);
    }
}
