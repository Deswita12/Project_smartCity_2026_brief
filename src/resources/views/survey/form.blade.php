{{-- resources/views/survey/form.blade.php --}}
{{-- Route: GET /survey/{token}  → SurveyController@show --}}
{{-- Route: POST /survey/{token} → SurveyController@submit --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survei Smart City — {{ $survey->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:       #F8F9FA;
            --white:    #FFFFFF;
            --border:   #E9ECEF;
            --text:     #1A1D23;
            --muted:    #6C757D;
            --subtle:   #9CA3AF;
            --blue:     #4F6EF7;
            --blue-lt:  #EEF2FF;
            --green:    #10B981;
            --green-lt: #D1FAE5;
            --radius:   12px;
        }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 0 0 48px;
        }

        /* ── Top bar ── */
        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 56px;
            position: sticky; top: 0; z-index: 10;
        }
        .topbar-brand {
            display: flex; align-items: center; gap: 10px;
            font-weight: 600; font-size: 14px; color: var(--text);
        }
        .topbar-brand-icon {
            width: 30px; height: 30px;
            background: var(--blue);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }
        .topbar-tag {
            font-size: 11px; color: var(--muted);
            background: var(--bg); border: 1px solid var(--border);
            border-radius: 20px; padding: 2px 10px;
        }

        /* ── Progress bar ── */
        .progress-bar-wrap {
            height: 3px; background: var(--border);
            position: sticky; top: 56px; z-index: 9;
        }
        .progress-bar-fill {
            height: 3px; background: var(--blue);
            transition: width .4s ease;
        }

        /* ── Container ── */
        .container { max-width: 680px; margin: 0 auto; padding: 32px 20px 0; }

        /* ── Dimension tabs ── */
        .dim-tabs {
            display: flex; gap: 4px; flex-wrap: wrap;
            margin-bottom: 24px;
        }
        .dim-tab {
            font-size: 12px; font-weight: 500;
            padding: 5px 12px; border-radius: 20px;
            border: 1px solid var(--border);
            background: var(--white); color: var(--muted);
            cursor: pointer; transition: all .15s;
            white-space: nowrap;
        }
        .dim-tab.active {
            background: var(--blue); color: #fff;
            border-color: var(--blue);
        }
        .dim-tab.done {
            background: var(--green-lt); color: #065F46;
            border-color: #A7F3D0;
        }

        /* ── Section heading ── */
        .section-head {
            margin-bottom: 20px;
        }
        .section-head h2 {
            font-size: 17px; font-weight: 600; color: var(--text);
        }
        .section-head p {
            font-size: 13px; color: var(--muted); margin-top: 4px; line-height: 1.5;
        }

        /* ── Question card ── */
        .q-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 18px 20px;
            margin-bottom: 12px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }
        .q-num {
            font-size: 11px; font-weight: 600; color: var(--muted);
            margin-bottom: 6px; text-transform: uppercase; letter-spacing: .05em;
        }
        .q-text {
            font-size: 14px; color: var(--text); line-height: 1.6;
            margin-bottom: 14px;
        }

        /* ── Scale options ── */
        .scale-wrap {
            display: flex; gap: 8px; flex-wrap: wrap;
        }
        .scale-option { position: relative; }
        .scale-option input[type="radio"] {
            position: absolute; opacity: 0; width: 0; height: 0;
        }
        .scale-label {
            display: flex; flex-direction: column; align-items: center;
            width: 56px; padding: 10px 4px 8px;
            border: 1.5px solid var(--border); border-radius: 10px;
            cursor: pointer; transition: all .15s;
            background: var(--white);
        }
        .scale-label:hover { border-color: var(--blue); background: var(--blue-lt); }
        .scale-option input:checked + .scale-label {
            border-color: var(--blue);
            background: var(--blue);
            color: #fff;
        }
        .scale-num {
            font-size: 18px; font-weight: 600; line-height: 1;
            color: inherit;
        }
        .scale-desc {
            font-size: 9.5px; text-align: center; margin-top: 4px;
            color: var(--muted); line-height: 1.3;
        }
        .scale-option input:checked + .scale-label .scale-desc {
            color: rgba(255,255,255,0.8);
        }
        .scale-option input:checked + .scale-label .scale-num {
            color: #fff;
        }

        /* ── Respondent info ── */
        .respondent-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 24px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }
        .respondent-card h3 {
            font-size: 13px; font-weight: 600; color: var(--text);
            margin-bottom: 12px;
        }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .form-group { margin-bottom: 0; }
        .form-label {
            display: block; font-size: 11.5px; font-weight: 500;
            color: var(--muted); margin-bottom: 5px;
        }
        .form-input {
            width: 100%; border: 1px solid var(--border);
            border-radius: 8px; padding: 9px 12px;
            font-size: 13.5px; font-family: inherit;
            color: var(--text); outline: none;
            transition: border-color .15s;
        }
        .form-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(79,110,247,0.08); }

        /* ── Navigation ── */
        .nav-bar {
            position: sticky; bottom: 0;
            background: var(--white); border-top: 1px solid var(--border);
            padding: 12px 20px;
            display: flex; justify-content: space-between; align-items: center;
            margin: 24px -20px 0;
        }
        .nav-info { font-size: 12px; color: var(--muted); }
        .nav-btns { display: flex; gap: 8px; }
        .btn { display: inline-flex; align-items: center; gap: 5px; border: none; border-radius: 8px; padding: 9px 18px; font-size: 13px; font-weight: 500; cursor: pointer; font-family: inherit; transition: all .15s; }
        .btn-prev { background: var(--bg); color: var(--text); border: 1px solid var(--border); }
        .btn-prev:hover { background: #F3F4F6; }
        .btn-next { background: var(--blue); color: #fff; }
        .btn-next:hover { background: #3B5BDB; }
        .btn-submit { background: var(--green); color: #fff; }
        .btn-submit:hover { background: #059669; }

        /* ── Success ── */
        .success-page {
            max-width: 480px; margin: 80px auto 0;
            text-align: center; padding: 0 20px;
        }
        .success-icon {
            width: 72px; height: 72px; border-radius: 50%;
            background: var(--green-lt);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
        }
        .success-title { font-size: 22px; font-weight: 600; color: var(--text); }
        .success-sub   { font-size: 14px; color: var(--muted); margin-top: 8px; line-height: 1.6; }
        .response-code {
            margin: 24px auto;
            background: var(--white); border: 1px solid var(--border);
            border-radius: 10px; padding: 16px 24px; display: inline-block;
        }
        .code-label { font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: .08em; }
        .code-value { font-size: 22px; font-weight: 700; color: var(--blue); letter-spacing: .08em; margin-top: 4px; }
        .btn-retake { margin-top: 8px; background: transparent; border: 1px solid var(--border); color: var(--text); }
        .btn-retake:hover { background: var(--bg); }

        /* ── Error ── */
        .error-msg { background: #FEF2F2; border: 1px solid #FECACA; border-radius: 8px; padding: 8px 12px; font-size: 12px; color: #991B1B; margin-top: 8px; display: none; }
        .error-msg.show { display: block; }

        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr; }
            .scale-label { width: 50px; }
        }
    </style>
</head>
<body>

{{-- Top Bar --}}
<div class="topbar">
    <div class="topbar-brand">
        <div class="topbar-brand-icon">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"/></svg>
        </div>
        Smart City Survey
    </div>
    <span class="topbar-tag">Survei anonim · Tidak perlu login</span>
</div>

{{-- Progress bar --}}
<div class="progress-bar-wrap">
    <div class="progress-bar-fill" id="progressFill" style="width:0%"></div>
</div>

@if(session('success'))
{{-- ══ HALAMAN SUKSES ══ --}}
<div class="success-page">
    <div class="success-icon">
        <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#059669" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
    </div>
    <div class="success-title">Terima Kasih!</div>
    <div class="success-sub">
        Jawaban Anda telah berhasil dikirim dan akan digunakan untuk meningkatkan kualitas layanan Smart City Kota Tangerang.
    </div>
    <div class="response-code">
        <div class="code-label">Kode Respons Anda</div>
        <div class="code-value">{{ session('response_code') }}</div>
    </div>
    <div>
        <a href="{{ url()->current() }}" class="btn btn-retake">Isi Ulang Survei</a>
    </div>
</div>

@else
{{-- ══ FORM SURVEI ══ --}}
<form method="POST" action="{{ route('survey.submit', $survey->link_token) }}" id="surveyForm">
@csrf

<div class="container">

    {{-- Respondent info --}}
    <div class="respondent-card">
        <h3>Identitas Responden</h3>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="respondent_name" class="form-input"
                       placeholder="Nama Anda" required
                       value="{{ old('respondent_name') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Email (opsional)</label>
                <input type="email" name="respondent_email" class="form-input"
                       placeholder="email@contoh.com"
                       value="{{ old('respondent_email') }}">
            </div>
        </div>
    </div>

    {{-- Dimension tabs --}}
    <div class="dim-tabs" id="dimTabs">
        @foreach($dimensions as $i => $dim)
        <div class="dim-tab {{ $i === 0 ? 'active' : '' }}"
             data-dim="{{ $dim->id }}"
             onclick="showDim({{ $dim->id }}, this)">
            {{ $dim->name }}
        </div>
        @endforeach
    </div>

    {{-- Questions per dimension --}}
    @foreach($dimensions as $i => $dim)
    <div class="dim-section" id="dim-{{ $dim->id }}" style="{{ $i > 0 ? 'display:none;' : '' }}">
        <div class="section-head">
            <h2>{{ $dim->name }}</h2>
            <p>{{ $dim->description ?? 'Penilaian terhadap ' . strtolower($dim->name) . ' di Kota Tangerang.' }}</p>
        </div>

        @php $qNum = $questions->where('dimension_id', $dim->id)->sortBy('order'); $qIdx = 1; @endphp
        @foreach($qNum as $q)
        <div class="q-card">
            <div class="q-num">Pertanyaan {{ $qIdx++ }}</div>
            <div class="q-text">{{ $q->question_text }}</div>

            <div class="scale-wrap">
                @php
                    $labels = ['Tidak Setuju','Kurang Setuju','Netral','Setuju','Sangat Setuju'];
                @endphp
                @for($v = 1; $v <= 5; $v++)
                <div class="scale-option">
                    <input type="radio"
                           name="responses[{{ $q->id }}]"
                           id="q{{ $q->id }}_v{{ $v }}"
                           value="{{ $v }}"
                           {{ old("responses.{$q->id}") == $v ? 'checked' : '' }}
                           required>
                    <label class="scale-label" for="q{{ $q->id }}_v{{ $v }}">
                        <span class="scale-num">{{ $v }}</span>
                        <span class="scale-desc">{{ $labels[$v-1] }}</span>
                    </label>
                </div>
                @endfor
            </div>
        </div>
        @endforeach

        {{-- Nav --}}
        <div class="nav-bar">
            <div class="nav-info">
                Dimensi {{ $i+1 }} dari {{ $dimensions->count() }}
            </div>
            <div class="nav-btns">
                @if($i > 0)
                <button type="button" class="btn btn-prev"
                        onclick="prevDim({{ $dim->id }}, {{ $dimensions[$i-1]->id }})">
                    ← {{ $dimensions[$i-1]->name }}
                </button>
                @endif
                @if($i < $dimensions->count() - 1)
                <button type="button" class="btn btn-next"
                        onclick="nextDim({{ $dim->id }}, {{ $dimensions[$i+1]->id }})">
                    Lanjut: {{ $dimensions[$i+1]->name }} →
                </button>
                @else
                <button type="submit" class="btn btn-submit">
                    Kirim Survei ✓
                </button>
                @endif
            </div>
        </div>
    </div>
    @endforeach

</div>
</form>
@endif

<script>
const dims    = {{ $dimensions->pluck('id')->toJson() }};
const total   = dims.length;
let current   = dims[0];

function updateProgress() {
    const idx = dims.indexOf(current);
    const pct = Math.round(((idx + 1) / total) * 100);
    document.getElementById('progressFill').style.width = pct + '%';
}

function showDim(id, tabEl) {
    // Hide all
    document.querySelectorAll('.dim-section').forEach(s => s.style.display = 'none');
    document.querySelectorAll('.dim-tab').forEach(t => t.classList.remove('active'));
    // Show target
    document.getElementById('dim-' + id).style.display = 'block';
    tabEl.classList.add('active');
    current = id;
    updateProgress();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function nextDim(currentId, nextId) {
    // Validate all questions in current section answered
    const section = document.getElementById('dim-' + currentId);
    const radios = section.querySelectorAll('input[type="radio"]');
    const names = new Set([...radios].map(r => r.name));
    let allAnswered = true;
    names.forEach(name => {
        const checked = section.querySelector(`input[name="${name}"]:checked`);
        if (!checked) allAnswered = false;
    });
    if (!allAnswered) {
        alert('Harap jawab semua pertanyaan sebelum melanjutkan.');
        return;
    }
    // Mark current tab as done
    const curTab = document.querySelector(`[data-dim="${currentId}"]`);
    if (curTab) { curTab.classList.remove('active'); curTab.classList.add('done'); }

    const nextTab = document.querySelector(`[data-dim="${nextId}"]`);
    showDim(nextId, nextTab);
}

function prevDim(currentId, prevId) {
    const prevTab = document.querySelector(`[data-dim="${prevId}"]`);
    showDim(prevId, prevTab);
}

updateProgress();
</script>
</body>
</html>