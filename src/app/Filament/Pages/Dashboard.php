<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ActivityLogWidget;
use App\Filament\Widgets\IndikatorLemahWidget;
use App\Filament\Widgets\NilaiPerDimensiChart;
use App\Filament\Widgets\RankingOpdWidget;
use App\Filament\Widgets\StatusSubmissionChart;
use App\Filament\Widgets\StatsOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string $view = 'filament.pages.dashboard';

    public int $selectedYear;
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?int $navigationSort = 0;
    protected static string $routePath = '/';

    public function mount(): void
    {
        $this->selectedYear = now()->year;
    }

    public function updatedSelectedYear(): void
    {
        $this->dispatch('yearChanged', year: $this->selectedYear);
    }

    public function getAvailableYears(): array
    {
        return range(now()->year, now()->year - 4);
    }
    
    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            NilaiPerDimensiChart::class,
            StatusSubmissionChart::class,
            RankingOpdWidget::class,
            IndikatorLemahWidget::class,
            ActivityLogWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return [
            'sm'  => 1,
            'md'  => 2,
            'xl'  => 3,
        ];
    }

}