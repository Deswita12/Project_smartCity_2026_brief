<?php

namespace App\Filament\Opd\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Opd\Widgets\ActivityLogWidget;
use App\Filament\Opd\Widgets\IndikatorLemahWidget;
use App\Filament\Opd\Widgets\NilaiPerDimensiChart;
use App\Filament\Opd\Widgets\RankingOpdWidget;
use App\Filament\Opd\Widgets\StatusSubmissionChart;
use App\Filament\Opd\Widgets\StatsOverviewWidget;
use App\Models\Indicator;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use App\Enums\Role;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon  = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title           = 'Dashboard';
    protected static ?int    $navigationSort  = 1;
    protected static string  $routePath       = '/';
    protected static string  $view            = 'filament.opd.pages.dashboard';
    
 
    // Cek hanya OPD yang bisa akses
    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()->role === Role::OPD;
    }
 
    public function getDashboardData(): array
    {
        $opd  = Auth::user()->opd;
        $year = now()->year;
 
        if (!$opd) return [];
 
        $indicators = Indicator::where('status', 'active')
                               ->where('year', $year)->get();
 
        $submissions = Submission::where('opd_id', $opd->id)
                                 ->where('year', $year)
                                 ->with(['indicator.dimension', 'evidences'])
                                 ->get();
 
        $approved  = $submissions->where('status', 'approved')->count();
        $review    = $submissions->where('status', 'review')->count();
        $revisi    = $submissions->where('status', 'revisi')->count();
        $belumDisi = $indicators->count() - $submissions->count();
        $total     = $indicators->count();
        $persen    = $total > 0 ? round(($approved / $total) * 100) : 0;
 
        return [
            'opd'          => $opd,
            'year'         => $year,
            'total'        => $total,
            'sudahDisi'    => $submissions->count(),
            'belumDisi'    => max(0, $belumDisi),
            'approved'     => $approved,
            'review'       => $review,
            'revisi'       => $revisi,
            'persen'       => $persen,
            'deadline'     => '30 November ' . $year,
            'indicators'   => $indicators,
            'submissions'  => $submissions->keyBy('indicator_id'),
        ];
    }
}