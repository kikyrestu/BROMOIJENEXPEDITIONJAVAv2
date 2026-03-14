<?php

namespace App\Filament\ReviewPanel\Resources;

use App\Models\Testimonial;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Storage;
use Filament\Support\Icons\Heroicon;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;
    protected static ?string $navigationLabel = 'Reviews';
    protected static ?string $modelLabel = 'Review';
    protected static ?string $pluralModelLabel = 'Reviews';
    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('display_photo_url')
                    ->label('')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&background=10b981&color=fff'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->description(fn ($record) => $record->country ?: $record->role)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn (int $state) => str_repeat('★', $state) . str_repeat('☆', 5 - $state))
                    ->color(fn (int $state) => match (true) {
                        $state >= 4 => 'success',
                        $state >= 3 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('content')
                    ->label('Comment')
                    ->limit(80)
                    ->wrap()
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => fn ($state) => in_array($state, ['approved', 'published']),
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'published' => 'Published',
                        'rejected' => 'Rejected',
                    ]),
                SelectFilter::make('rating')
                    ->options([
                        '5' => '5 Stars',
                        '4' => '4 Stars',
                        '3' => '3 Stars',
                        '2' => '2 Stars',
                        '1' => '1 Star',
                    ]),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => !in_array($record->status, ['approved', 'published']))
                    ->action(fn ($record) => $record->update(['status' => 'approved'])),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status !== 'rejected')
                    ->action(fn ($record) => $record->update(['status' => 'rejected'])),
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('approve_selected')
                    ->label('Approve Selected')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn ($records) => $records->each->update(['status' => 'approved'])),
                BulkAction::make('reject_selected')
                    ->label('Reject Selected')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn ($records) => $records->each->update(['status' => 'rejected'])),
                DeleteBulkAction::make(),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')->disabled(),
            Forms\Components\TextInput::make('country')->disabled(),
            Forms\Components\TextInput::make('rating')->disabled(),
            Forms\Components\Textarea::make('content')->disabled()->columnSpanFull(),
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'published' => 'Published',
                    'rejected' => 'Rejected',
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\ReviewPanel\Resources\TestimonialResource\Pages\ListTestimonials::route('/'),
        ];
    }
}
