<?php

namespace App\Filament\Resources\PackageResource\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PriceAdjustmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'priceAdjustments';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                \Filament\Forms\Components\Grid::make(2)
                    ->schema([
                        \Filament\Forms\Components\DatePicker::make('start_date')
                            ->required(),
                        \Filament\Forms\Components\DatePicker::make('end_date')
                            ->required(),
                        \Filament\Forms\Components\Select::make('adjustment_type')
                            ->options([
                                'percentage' => 'Percentage (%)',
                                'fixed' => 'Fixed Amount (IDR)',
                            ])
                            ->required(),
                        TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->prefix(fn (\Filament\Schemas\Components\Utilities\Get $get) => $get('adjustment_type') === 'fixed' ? 'IDR' : '%'),
                        \Filament\Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('adjustment_type')
                    ->badge()
                    ->colors([
                        'primary' => 'fixed',
                        'warning' => 'percentage',
                    ]),
                TextColumn::make('amount')->numeric(),
                TextColumn::make('start_date')->date()->sortable(),
                TextColumn::make('end_date')->date()->sortable(),
                \Filament\Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
