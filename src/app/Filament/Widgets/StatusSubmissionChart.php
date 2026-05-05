<?php

namespace App\Filament\Widgets;

use App\Models\Submission;
use Filament\Widgets\ChartWidget;

class StatusSubmissionChart extends ChartWidget
{
    protected static ?string $heading = 'Status Submission';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $year = now()->year;

        $approved  = Submission::where('year', $year)->where('status', 'approved')->count();
        $review    = Submission::where('year', $year)->where('status', 'review')->count();
        $revisi    = Submission::where('year', $year)->where('status', 'revisi')->count();
        $submitted = Submission::where('year', $year)->where('status', 'submitted')->count();
        $draft     = Submission::where('year', $year)->where('status', 'draft')->count();

        return [
            'datasets' => [
                [
                    'data'            => [$approved, $review, $revisi, $submitted, $draft],
                    'backgroundColor' => [
                        '#22C55E', // approved - green
                        '#3B82F6', // review - blue
                        '#F59E0B', // revisi - yellow
                        '#8B5CF6', // submitted - purple
                        '#6B7280', // draft - gray
                    ],
                ],
            ],
            'labels' => ['Approved', 'Review', 'Revisi', 'Submitted', 'Draft'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}