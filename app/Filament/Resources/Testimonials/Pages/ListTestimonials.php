<?php

namespace App\Filament\Resources\Testimonials\Pages;

use App\Filament\Resources\Testimonials\TestimonialResource;
use App\Models\ReviewToken;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;

class ListTestimonials extends ListRecords
{
    protected static string $resource = TestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateReviewLink')
                ->label('Share Review Link')
                ->icon('heroicon-o-link')
                ->color(Color::Emerald)
                ->action(function () {
                    $token = ReviewToken::generate(
                        createdBy: auth()->id(),
                        label: 'Generated from Admin',
                    );

                    \Filament\Notifications\Notification::make()
                        ->title('Review Link Generated (Single-Use)')
                        ->body("Copy this link and send it to your client (valid for 30 days):\n\n**{$token->review_url}**")
                        ->success()
                        ->persistent()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
