<?php
// ══════════════════════════════════════════════
// app/Http/Controllers/SurveyController.php
// ══════════════════════════════════════════════

namespace App\Http\Controllers;

use App\Models\Dimension;
use App\Models\Question;
use App\Models\Respondent;
use App\Models\Response;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SurveyController extends Controller
{
    public function show(string $token)
    {
        $survey = Survey::where('link_token', $token)
                        ->where('status', 'active')
                        ->firstOrFail();

        // Cek apakah masih dalam periode
        if (now()->gt($survey->end_date)) {
            abort(410, 'Survei ini sudah ditutup.');
        }

        $dimensions = Dimension::where('is_active', true)
                               ->orderBy('order')->get();

        $questions  = Question::where('survey_id', $survey->id)
                              ->with('dimension')
                              ->orderBy('order')
                              ->get();

        return view('survey.form', compact('survey', 'dimensions', 'questions'));
    }

    public function submit(Request $request, string $token)
    {
        $survey = Survey::where('link_token', $token)
                        ->where('status', 'active')
                        ->firstOrFail();

        $request->validate([
            'respondent_name' => 'required|string|max:200',
            'respondent_email'=> 'nullable|email',
            'responses'       => 'required|array',
            'responses.*'     => 'required|integer|min:1|max:5',
        ]);

        // Buat respondent
        $respondent = Respondent::create([
            'name'          => $request->respondent_name,
            'email'         => $request->respondent_email,
            'survey_id'     => $survey->id,
            'response_code' => 'SCT-' . now()->year . '-' . strtoupper(Str::random(4)),
            'ip_address'    => $request->ip(),
        ]);

        // Simpan setiap jawaban
        foreach ($request->responses as $questionId => $value) {
            Response::create([
                'respondent_id' => $respondent->id,
                'question_id'   => $questionId,
                'value'         => $value,
            ]);
        }

        // Hitung ulang survey_score per submission yang terdampak
        // (bisa dijadikan job/queue untuk production)
        $affectedIndicators = Question::whereIn('id', array_keys($request->responses))
                                      ->pluck('indicator_id')
                                      ->filter()
                                      ->unique();

        foreach ($affectedIndicators as $indicatorId) {
            \App\Models\Submission::where('indicator_id', $indicatorId)->each(
                fn($sub) => $sub->calculateSurveyScore()
            );
        }

        return redirect()
            ->back()
            ->with('success', true)
            ->with('response_code', $respondent->response_code);
    }
}


// ══════════════════════════════════════════════
// routes/web.php — tambahkan ini
// ══════════════════════════════════════════════
/*
use App\Http\Controllers\SurveyController;

Route::get('/survey/{token}',  [SurveyController::class, 'show'])->name('survey.show');
Route::post('/survey/{token}', [SurveyController::class, 'submit'])->name('survey.submit');
*/


// ══════════════════════════════════════════════
// bootstrap/providers.php — daftarkan OpdPanelProvider
// ══════════════════════════════════════════════
/*
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\Filament\OpdPanelProvider::class,  // ← tambah ini
];
*/


// ══════════════════════════════════════════════
// Struktur folder yang perlu dibuat
// ══════════════════════════════════════════════
/*
app/
├── Filament/
│   ├── Admin/          (panel admin — sudah ada)
│   └── Opd/
│       └── Pages/
│           ├── Dashboard.php
│           ├── IsiEvaluasi.php
│           └── RiwayatSubmission.php
├── Http/Controllers/
│   └── SurveyController.php
├── Providers/Filament/
│   ├── AdminPanelProvider.php
│   └── OpdPanelProvider.php

resources/views/
├── filament/
│   ├── pages/           (admin views)
│   │   └── dashboard.blade.php
│   └── opd/
│       └── pages/
│           ├── dashboard.blade.php
│           ├── isi-evaluasi.blade.php
│           └── riwayat-submission.blade.php
└── survey/
    └── form.blade.php
*/