<?php

namespace App\Filament\Opd\Pages;

use Filament\Pages\Page;

class RiwayatSubmission extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Riwayat Submission';
    protected static ?string $title = 'Riwayat Submission';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.opd.pages.riwayat-submission';
}