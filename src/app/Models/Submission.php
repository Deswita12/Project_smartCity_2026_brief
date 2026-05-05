<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Submission extends Model {
    use HasFactory;

    protected $fillable = [
        'opd_id', 'indicator_id', 'year', 'answer',
        'additional_notes', 'survey_score', 'final_score',
        'status', 'submitted_at'
    ];

    protected $casts = ['submitted_at' => 'datetime'];

    public function opd() { return $this->belongsTo(Opd::class); }
    public function indicator() { return $this->belongsTo(Indicator::class); }
    public function evidences() { return $this->hasMany(Evidence::class); }
    public function validations() { return $this->hasMany(Validation::class); }

    // Hitung survey_score otomatis dari responses
    public function calculateSurveyScore(): void {
        $avg = Response::whereHas('question', fn($q) =>
            $q->where('indicator_id', $this->indicator_id)
        )->avg('value');
        $this->update(['survey_score' => round($avg ?? 0, 2)]);
    }
}
