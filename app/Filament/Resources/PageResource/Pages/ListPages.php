<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use App\Models\Page;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('homeWarning')
                ->label('Warning: Do not delete page with slug "home". It is required for the public homepage.')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger')
                ->disabled()
                ->visible(fn () => Page::query()->where('slug', 'home')->exists()),

            Actions\CreateAction::make(),
        ];
    }
}
