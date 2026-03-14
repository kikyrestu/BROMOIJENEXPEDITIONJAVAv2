<?php

namespace App\Filament\ReviewPanel\Widgets;

use App\Models\Testimonial;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentReviewsTable extends BaseWidget
{
    protected static ?string $heading = 'Recent Reviews';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Testimonial::query()->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn (int $state) => str_repeat('★', $state) . str_repeat('☆', 5 - $state))
                    ->color(fn (int $state) => match(true) {
                        $state >= 4 => 'success',
                        $state >= 3 => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('content')
                    ->label('Comment')
                    ->limit(50)
                    ->wrap(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => fn ($state) => in_array($state, ['approved', 'published']),
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
