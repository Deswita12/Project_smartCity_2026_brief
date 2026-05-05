<?php

namespace App\Filament\Widgets;

use App\Models\Indicator;
use App\Models\Opd;
use App\Models\Respondent;
use App\Models\Submission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $year = now()->year;

        $totalIndikator   = Indicator::where('status', 'active')->count();
        $aktifIndikator   = Indicator::where('status', 'active')->count();
        $nonaktifIndikator = Indicator::where('status', 'inactive')->count();

        $totalOpd     = Opd::where('status', 'aktif')->count();
        $sudahSubmit  = Submission::where('year', $year)->distinct('opd_id')->count();

        $rataRataNilai = Submission::where('year', $year)
            ->where('status', 'approved')
            ->avg('final_score') ?? 0;

        $totalResponden = Respondent::whereYear('created_at', $year)->count();
        $bulanIni       = Respondent::whereYear('created_at', $year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $totalSelesai  = Submission::where('year', $year)
            ->where('status', 'approved')
            ->count();

        $totalTarget = $totalIndikator * $totalOpd;

        $persenSelesai = $totalTarget > 0
        ? round(($totalSelesai / $totalTarget) * 100)
        : 0;
        // $persenSelesai = $totalIndikator > 0
        //     ? round(($totalSelesai / ($totalIndikator * $totalOpd)) * 100)
        //     : 0;

        return [
            Stat::make('Total Indikator', $totalIndikator)
                ->description("Aktif: {$aktifIndikator} | Nonaktif: {$nonaktifIndikator}")
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->color('info'),

            Stat::make('Total OPD', $totalOpd)
                ->description("Sudah submit: {$sudahSubmit}")
                ->descriptionIcon('heroicon-o-building-office-2')
                ->color('warning'),

            Stat::make('Rata-rata Nilai', number_format($rataRataNilai, 2))
                ->description('Skala 1–5')
                ->descriptionIcon('heroicon-o-star')
                ->color('success'),

            Stat::make('Total Responden', number_format($totalResponden))
                ->description("Bulan ini: +{$bulanIni}")
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make('% Indikator Selesai', "{$persenSelesai}%")
                ->description("{$totalSelesai} dari " . ($totalIndikator * $totalOpd) . " selesai")
                ->descriptionIcon('heroicon-o-check-circle')
                ->color($persenSelesai >= 70 ? 'success' : 'warning'),
        ];
    }
}