<?php

namespace App\Filament\Resources\NavigationMenus;

use App\Filament\Resources\NavigationMenus\Pages\CreateNavigationMenu;
use App\Filament\Resources\NavigationMenus\Pages\EditNavigationMenu;
use App\Filament\Resources\NavigationMenus\Pages\ListNavigationMenus;
use App\Filament\Resources\NavigationMenus\Schemas\NavigationMenuForm;
use App\Filament\Resources\NavigationMenus\Tables\NavigationMenusTable;
use App\Models\NavigationMenu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\NavigationMenus\RelationManagers;
use Filament\Tables\Table;

class NavigationMenuResource extends Resource
{
    protected static ?string $model = NavigationMenu::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bars-3';

    protected static string | \UnitEnum | null $navigationGroup = 'Site Appearance';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && ($user->role === 'admin' || $user->can('view_navigation_menus'));
    }

    public static function form(Schema $schema): Schema
    {
        return NavigationMenuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NavigationMenusTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ChildrenRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNavigationMenus::route('/'),
            'create' => CreateNavigationMenu::route('/create'),
            'edit' => EditNavigationMenu::route('/{record}/edit'),
        ];
    }
}
