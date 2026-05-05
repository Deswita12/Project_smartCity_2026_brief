<?php

namespace App\Filament\Widgets;

use App\Models\Dimension;
use App\Models\Submission;
use Filament\Widgets\ChartWidget;

class NilaiPerDimensiChart extends ChartWidget
{
    protected static ?string $heading = 'Nilai Per Dimensi Smart City';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $year = now()->year;

        $dimensions = Dimension::where('is_active', true)
            ->orderBy('order')
            ->get();

        $labels = [];
        $values = [];
        $colors = [];

        foreach ($dimensions as $dimension) {
            $avg = Submission::where('year', $year)
                ->where('status', 'approved')
                ->whereHas('indicator', fn ($q) =>
                    $q->where('dimension_id', $dimension->id)
                )
                ->avg('final_score') ?? 0;

            $labels[] = $dimension->name;
            $values[] = round($avg, 2);
            $colors[] = $dimension->color ?? '#3B82F6';
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Nilai',
                    'data'            => $values,
                    'backgroundColor' => $colors,
                    'borderColor'     => $colors,
                    'borderWidth'     => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'min'  => 0,
                    'max'  => 5,
                    'ticks' => ['stepSize' => 1],
                ],
            ],
            'plugins' => [
                'legend' => ['display' => false],
            ],
        ];
    }
}