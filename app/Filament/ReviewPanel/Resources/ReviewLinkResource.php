<?php

namespace App\Filament\ReviewPanel\Resources;

use App\Models\ReviewToken;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class ReviewLinkResource extends Resource
{
    protected static ?string $model = ReviewToken::class;
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;
    protected static ?string $navigationLabel = 'Review Links';
    protected static ?string $modelLabel = 'Review Link';
    protected static ?string $pluralModelLabel = 'Review Links';
    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->default('—')
                    ->searchable(),
                Tables\Columns\TextColumn::make('token')
                    ->label('Token')
                    ->formatStateUsing(fn (string $state) => substr($state, 0, 12) . '...')
                    ->copyable()
                    ->copyableState(fn ($record) => $record->review_url),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        if ($record->used_at) return 'Used';
                        if ($record->expires_at->isPast()) return 'Expired';
                        return 'Active';
                    })
                    ->colors([
                        'success' => 'Active',
                        'danger' => 'Expired',
                        'warning' => 'Used',
                    ]),
                Tables\Columns\TextColumn::make('testimonial.name')
                    ->label('Reviewed By')
                    ->default('—'),
                Tables\Columns\TextColumn::make('testimonial.rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => $state ? str_repeat('★', $state) : '—')
                    ->color(fn ($state) => match (true) {
                        !$state => 'gray',
                        $state >= 4 => 'success',
                        $state >= 3 => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'used' => 'Used',
                        'expired' => 'Expired',
                    ])
                    ->query(function ($query, array $data) {
                        return match ($data['value'] ?? null) {
                            'active' => $query->whereNull('used_at')->where('expires_at', '>', now()),
                            'used' => $query->whereNotNull('used_at'),
                            'expired' => $query->whereNull('used_at')->where('expires_at', '<=', now()),
                            default => $query,
                        };
                    }),
            ])
            ->actions([
                Action::make('copy_link')
                    ->label('Copy Link')
                    ->icon('heroicon-o-clipboard-document')
                    ->color('primary')
                    ->action(function ($record) {
                        Notification::make()
                            ->title('Link Copied!')
                            ->body($record->review_url)
                            ->success()
                            ->persistent()
                            ->send();
                    }),
                DeleteAction::make(),
            ])
            ->headerActions([
                Action::make('generate_link')
                    ->label('Generate New Link')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->form([
                        Forms\Components\TextInput::make('label')
                            ->label('Label (optional)')
                            ->placeholder('e.g. Customer name or group')
                            ->maxLength(255),
                        Forms\Components\Select::make('expiry_days')
                            ->label('Link Valid For')
                            ->options([
                                7 => '7 Days',
                                14 => '14 Days',
                                30 => '30 Days',
                                60 => '60 Days',
                                90 => '90 Days',
                            ])
                            ->default(30),
                    ])
                    ->action(function (array $data) {
                        $token = ReviewToken::generate(
                            createdBy: auth()->id(),
                            label: $data['label'] ?? null,
                            expiryDays: (int) $data['expiry_days'],
                        );

                        Notification::make()
                            ->title('Review Link Generated!')
                            ->body("Copy and share this link:\n\n**{$token->review_url}**")
                            ->success()
                            ->persistent()
                            ->send();
                    }),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\ReviewPanel\Resources\ReviewLinkResource\Pages\ListReviewLinks::route('/'),
        ];
    }
}
