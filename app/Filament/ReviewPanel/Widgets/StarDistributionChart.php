<?php

namespace App\Filament\ReviewPanel\Widgets;

use App\Models\Testimonial;
use Filament\Widgets\ChartWidget;

class StarDistributionChart extends ChartWidget
{
    protected ?string $heading = 'Rating Distribution';
    protected ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $distribution[$i . ' ★'] = Testimonial::where('rating', $i)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Reviews',
                    'data' => array_values($distribution),
                    'backgroundColor' => ['#10b981', '#34d399', '#fbbf24', '#f97316', '#ef4444'],
                ],
            ],
            'labels' => array_keys($distribution),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'x' => ['ticks' => ['stepSize' => 1]],
            ],
        ];
    }
}
