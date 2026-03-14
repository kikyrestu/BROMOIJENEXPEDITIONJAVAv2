<?php

namespace App\Filament\Resources\NavigationMenus\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NavigationMenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                \Filament\Forms\Components\MorphToSelect::make('navigable')
                    ->label('Link Source')
                    ->types([
                        \Filament\Forms\Components\MorphToSelect\Type::make(\App\Models\Package::class)->titleAttribute('name'),
                        \Filament\Forms\Components\MorphToSelect\Type::make(\App\Models\Blog::class)->titleAttribute('title'),
                        \Filament\Forms\Components\MorphToSelect\Type::make(\App\Models\Destination::class)->titleAttribute('name'),
                    ])
                    ->searchable()
                    ->preload(),
                TextInput::make('url')
                    ->label('Custom URL (Optional)')
                    ->helperText('Leave empty if using Link Source above.')
                    ->url(),
                Select::make('parent_id')
                    ->label('Parent Menu')
                    ->options(\App\Models\NavigationMenu::pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
                Select::make('target')
                    ->options(['_self' => ' self', '_blank' => ' blank'])
                    ->default('_self')
                    ->required(),
                Select::make('auto_load')
                    ->label('Auto-Load Children')
                    ->options([
                        'none' => 'None (Manual Only)',
                        'destination_packages' => 'Destinations → Packages (WordPress style)',
                    ])
                    ->default('none')
                    ->helperText('When set, this menu will auto-populate children from the selected source. Manual children will appear first.')
                    ->reactive(),
            ]);
    }
}
