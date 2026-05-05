<?php

namespace App\Filament\Opd\Pages;

use Filament\Pages\Page;

class IsiEvaluasi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'Isi Evaluasi';
    protected static ?string $title = 'Isi Evaluasi';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.opd.pages.isi-evaluasi';
    
    public int $step = 1;

    public $indicators;
    public $selectedId;

    public $answer;
    public $additionalNotes;

    public $submission;

    public function mount()
    {
        $this->indicators = collect([]); 
    }
    public function selectIndicator() {}
    public function goStep() {}
    public function saveDraft() {}
    public function submitEvaluasi() {} 
}