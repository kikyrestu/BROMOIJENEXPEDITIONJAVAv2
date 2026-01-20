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
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InquiryLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'InquiryLogs';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('ip_address'),
                Textarea::make('user_agent')
                    ->columnSpanFull(),
                TextInput::make('utm_source'),
                TextInput::make('utm_medium'),
                TextInput::make('utm_campaign'),
                Textarea::make('referer_url')
                    ->columnSpanFull(),
                DateTimePicker::make('clicked_at')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('ip_address')
                    ->searchable(),
                TextColumn::make('utm_source')
                    ->searchable(),
                TextColumn::make('utm_medium')
                    ->searchable(),
                TextColumn::make('utm_campaign')
                    ->searchable(),
                TextColumn::make('clicked_at')
                    ->label('Click Time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }
}
