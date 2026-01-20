<?php

namespace App\Filament\Forms\Components;

use App\Models\Media;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\IconPosition;

class MediaPicker
{
    public static function makeSimple(string $name, ?string $label = null, ?string $directory = null): Group
    {
        $label = $label ?? ucfirst($name);
        
        return Group::make()
            ->schema([
                Radio::make("{$name}_mode")
                    ->label('Media Source')
                    ->options([
                        'upload' => '📤 Upload New',
                        'library' => '📚 From Library',
                    ])
                    ->default('upload')
                    ->inline()
                    ->live(),

                FileUpload::make($name)
                    ->label($label)
                    ->image()
                    ->disk('public')
                    ->directory($directory ?? $name)
                    ->visible(fn (callable $get) => $get("{$name}_mode") === 'upload'),

                Select::make("{$name}_media_id")
                    ->label("Select {$label} from Library")
                    ->options(function () {
                        return Media::where('type', 'image')
                            ->latest()
                            ->get()
                            ->mapWithKeys(function ($media) {
                                // Format: "Image Name - 2024-01-18"
                                $date = $media->created_at->format('Y-m-d');
                                $size = round($media->size / 1024, 1) . ' KB';
                                return [
                                    $media->id => "{$media->name} ({$size}) - {$date}"
                                ];
                            });
                    })
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->allowHtml()
                    ->getSearchResultsUsing(function (string $search) {
                        return Media::where('type', 'image')
                            ->where(function ($query) use ($search) {
                                $query->where('name', 'like', "%{$search}%")
                                    ->orWhere('alt_text', 'like', "%{$search}%");
                            })
                            ->latest()
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($media) {
                                $date = $media->created_at->format('Y-m-d');
                                $size = round($media->size / 1024, 1) . ' KB';
                                return [
                                    $media->id => "{$media->name} ({$size}) - {$date}"
                                ];
                            });
                    })
                    ->getOptionLabelUsing(function ($value) {
                        $media = Media::find($value);
                        if (!$media) return $value;
                        $date = $media->created_at->format('Y-m-d');
                        $size = round($media->size / 1024, 1) . ' KB';
                        return "{$media->name} ({$size}) - {$date}";
                    })
                    ->visible(fn (callable $get) => $get("{$name}_mode") === 'library')
                    ->helperText('💡 Click to browse and search media library')
                    ->placeholder('Click to select from media library'),
            ])
            ->columnSpanFull();
    }
}
