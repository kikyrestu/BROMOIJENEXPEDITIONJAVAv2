<?php

namespace App\Filament\Resources\Banners\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class BannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image'),
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->description(fn ($record) => $record->slug)
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('type')
                    ->badge(),
                \Filament\Tables\Columns\TextColumn::make('active_placements_count')
                    ->label('Locations')

                    ->formatStateUsing(function ($state, $record) {
                        if (!$record->placements) return 'None';
                        $count = count($record->placements);
                        $first = $record->placements[0]['location'] ?? '';
                        return $count > 1 ? "$first +".($count-1) : $first;
                    }),
                \Filament\Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                \Filament\Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
