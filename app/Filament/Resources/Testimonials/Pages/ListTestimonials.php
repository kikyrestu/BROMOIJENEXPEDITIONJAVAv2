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
                ->action(function ($livewire) {
                    $token = ReviewToken::generate(
                        createdBy: auth()->id(),
                        label: 'Generated from Admin',
                    );

                    $safeUrl = \Illuminate\Support\Js::from($token->review_url);
                    $livewire->js("navigator.clipboard.writeText({$safeUrl})");

                    \Filament\Notifications\Notification::make()
                        ->title('Review Link Generated & Copied!')
                        ->body("Link copied to clipboard (valid for 30 days):\n\n**{$token->review_url}**")
                        ->success()
                        ->persistent()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
