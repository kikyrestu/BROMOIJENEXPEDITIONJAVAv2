<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Grid::make(2)
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Customer Name')
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('country')
                            ->label('Country')
                            ->placeholder('e.g. Australia, Singapore'),
                        \Filament\Forms\Components\TextInput::make('role')
                            ->label('Role / Title')
                            ->placeholder('e.g. Travel Blogger, Tourist from Germany'),
                        
                        \Filament\Forms\Components\Textarea::make('content')
                            ->label('Testimonial Content')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                        
                        \Filament\Forms\Components\TextInput::make('rating')
                            ->label('Rating (1-5)')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->default(5),
                        
                        \Filament\Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'published' => 'Published',
                                'rejected' => 'Rejected',
                            ])
                            ->default('pending')
                            ->required(),

                        \Filament\Forms\Components\FileUpload::make('photo_path')
                            ->label('Customer Photo')
                            ->image()
                            ->directory('testimonials')
                            ->maxSize(5120)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
