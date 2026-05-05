<x-filament-panels::page class="!p-0 !max-w-full">
@php $d = $this->getDashboardData(); $opd = $d['opd'] ?? null; @endphp

<style>
/* ── Base ── */
.opd-page { background: #F8F9FA; min-height: 100vh; }

/* ── Welcome banner ── */
.opd-banner {
    background: linear-gradient(135deg, #1a56db 0%, #4F6EF7 60%, #6366f1 100%);
    border-radius: 14px;
    padding: 24px 28px;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
}
.opd-banner::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
}
.opd-banner::after {
    content: '';
    position: absolute;
    bottom: -60px; right: 80px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
}
.banner-title   { font-size: 18px; font-weight: 600; color: #fff; }
.banner-sub     { font-size: 13px; color: rgba(255,255,255,0.75); margin-top: 2px; }
.banner-period  { font-size: 12px; color: rgba(255,255,255,0.6); margin-top: 6px; }
.banner-status  {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,0.15); border-radius: 20px;
    padding: 3px 10px; font-size: 12px; color: #fff;
    backdrop-filter: blur(4px);
}
.banner-status.active { background: rgba(16,185,129,0.25); }

/* ── Stat cards ── */
.stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 20px; }
.stat-card {
    background: #fff;
    border: 1px solid #E9ECEF;
    border-radius: 12px;
    padding: 16px 18px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.stat-icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 12px;
}
.stat-label { font-size: 11px; font-weight: 600; color: #6C757D; text-transform: uppercase; letter-spacing: .05em; }
.stat-value { font-size: 26px; font-weight: 700; color: #1A1D23; line-height: 1.1; margin: 3px 0; }
.stat-desc  { font-size: 11.5px; color: #9CA3AF; }

/* ── Card ── */
.sct-card {
    background: #fff;
    border: 1px solid #E9ECEF;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    margin-bottom: 16px;
}
.card-title {
    font-size: 14px; font-weight: 600; color: #1A1D23;
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 16px;
}

/* ── Indicator table ── */
.ind-table { width: 100%; border-collapse: collapse; }
.ind-table th {
    font-size: 11px; font-weight: 600; color: #6C757D;
    text-transform: uppercase; letter-spacing: .05em;
    padding: 8px 10px; border-bottom: 1px solid #F3F4F6;
    text-align: left;
}
.ind-table td { padding: 10px 10px; border-bottom: 1px solid #F9FAFB; font-size: 13px; color: #374151; }
.ind-table tr:last-child td { border-bottom: none; }
.ind-table tr:hover td { background: #FAFAFA; }

/* ── Badge ── */
.badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 11px; font-weight: 500; border-radius: 20px; padding: 2px 9px;
}
.badge-approved { background: #D1FAE5; color: #065F46; }
.badge-review   { background: #DBEAFE; color: #1E40AF; }
.badge-revisi   { background: #FEE2E2; color: #991B1B; }
.badge-draft    { background: #F3F4F6; color: #374151; }
.badge-belum    { background: #FEF3C7; color: #92400E; }

/* ── Dim badge ── */
.dim-badge {
    display: inline-block; font-size: 10.5px; font-weight: 500;
    border-radius: 5px; padding: 2px 7px;
}

/* ── Progress bar ── */
.prog-wrap { background: #F3F4F6; border-radius: 999px; height: 6px; width: 100%; }
.prog-bar  { height: 6px; border-radius: 999px; background: #4F6EF7; transition: width .4s; }

/* ── Action button ── */
.btn-isi {
    display: inline-flex; align-items: center; gap-4px;
    font-size: 12px; font-weight: 500;
    background: #4F6EF7; color: #fff;
    border: none; border-radius: 7px;
    padding: 5px 12px; cursor: pointer;
    text-decoration: none; transition: background .15s;
    white-space: nowrap;
}
.btn-isi:hover { background: #3B5BDB; }
.btn-isi.outline {
    background: transparent; color: #4F6EF7;
    border: 1px solid #4F6EF7;
}
.btn-isi.outline:hover { background: #EEF2FF; }

/* ── Progress summary ── */
.prog-summary {
    background: #F8F9FA; border-radius: 10px; padding: 14px 16px;
    display: flex; justify-content: space-between; align-items: center;
}
.prog-pct { font-size: 32px; font-weight: 700; color: #1A1D23; }
.prog-legend { display: flex; flex-direction: column; gap: 5px; }
.prog-legend-item { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #374151; }
.prog-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

/* ── Deadline ── */
.deadline-box {
    margin-top: 10px;
    background: #FFFBEB;
    border: 1px solid #FDE68A;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 12px; color: #92400E;
    display: flex; align-items: center; gap: 6px;
}
</style>

<div class="opd-page">

{{-- ── Welcome Banner ── --}}
@if($opd)
<div class="opd-banner">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:10px; position:relative; z-index:1;">
        <div>
            <div class="banner-status active">
                <span style="width:6px;height:6px;border-radius:50%;background:#34d399;display:inline-block;"></span>
                Aktif
            </div>
            " style="margin-top:8px;">
                Sel<div class="banner-titleamat datang, {{ $opd->name }}
            </div>
            <div class="banner-sub">Portal OPD — Survei Smart City</div>
            <div class="banner-period">Periode: 1 Jan {{ $d['year'] }} — 30 Nov {{ $d['year'] }}</div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:11px;color:rgba(255,255,255,0.6);margin-bottom:4px;">TENTANG SURVEI</div>
            <div style="font-size:12px;color:rgba(255,255,255,0.85);max-width:280px;line-height:1.5;">
                Survei Smart City merupakan bagian dari evaluasi implementasi kota cerdas.
                Melalui pengisian indikator, OPD memberikan gambaran nyata terkait inovasi
                dan kualitas layanan publik.
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── Stat Cards ── --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#EEF2FF;">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4F6EF7"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"/></svg>
        </div>
        <div class="stat-label">Total Indikator</div>
        <div class="stat-value">{{ $d['total'] ?? 0 }}</div>
        <div class="stat-desc">Tanggung jawab OPD</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#D1FAE5;">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#059669"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
        </div>
        <div class="stat-label">Sudah Diisi</div>
        <div class="stat-value">{{ $d['sudahDisi'] ?? 0 }}</div>
        <div class="stat-desc">{{ $d['approved'] ?? 0 }} Approved · {{ $d['review'] ?? 0 }} Review</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FEF3C7;">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#D97706"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
        </div>
        <div class="stat-label">Belum Diisi</div>
        <div class="stat-value">{{ $d['belumDisi'] ?? 0 }}</div>
        <div class="stat-desc">Segera lengkapi</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FEE2E2;">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#DC2626"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
        </div>
        <div class="stat-label">Perlu Revisi</div>
        <div class="stat-value">{{ $d['revisi'] ?? 0 }}</div>
        <div class="stat-desc">Tidak ada revisi</div>
    </div>
</div>

{{-- ── Row: Status tabel + Progress ── --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;margin-bottom:16px;">

    {{-- Status per Indikator --}}
    <div class="sct-card" style="margin-bottom:0;">
        <div class="card-title">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4F6EF7"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
            Status Pengisian Per Indikator
        </div>
        <table class="ind-table">
            <thead>
                <tr>
                    <th>Indikator</th>
                    <th>Dimensi</th>
                    <th>Status</th>
                    <th>Nilai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($d['indicators'] ?? [] as $ind)
                @php
                    $sub = ($d['submissions'] ?? collect())->get($ind->id);
                    $status = $sub ? $sub->status : 'belum';
                    $nilai  = $sub ? number_format($sub->survey_score ?? 0, 1) : '—';
                    $dimColor = $ind->dimension->color ?? '#4F6EF7';
                @endphp
                <tr>
                    <td style="font-weight:500;max-width:200px;">{{ \Illuminate\Support\Str::limit($ind->name, 32) }}</td>
                    <td>
                        <span class="dim-badge" style="background:{{ $dimColor }}18;color:{{ $dimColor }};">
                            {{ $ind->dimension->name ?? '—' }}
                        </span>
                    </td>
                    <td>
                        @if($status === 'approved')
                            <span class="badge badge-approved">✓ Approved</span>
                        @elseif($status === 'review')
                            <span class="badge badge-review">⏳ Review</span>
                        @elseif($status === 'revisi')
                            <span class="badge badge-revisi">↩ Revisi</span>
                        @elseif($status === 'draft')
                            <span class="badge badge-draft">Draft</span>
                        @else
                            <span class="badge badge-belum">Belum Diisi</span>
                        @endif
                    </td>
                    <td style="font-weight:600;color:{{ $status === 'approved' ? '#059669' : '#374151' }};">
                        {{ $nilai }}
                    </td>
                    <td>
                        @if($status === 'belum' || $status === 'revisi')
                            <a href="{{ route('filament.opd.pages.isi-evaluasi', ['indicator' => $ind->id]) }}"
                               class="btn-isi" style="font-size:11px;padding:4px 10px;">
                                {{ $status === 'revisi' ? 'Revisi' : 'Isi Sekarang' }}
                            </a>
                        @else
                            <a href="{{ route('filament.opd.pages.isi-evaluasi', ['indicator' => $ind->id]) }}"
                               class="btn-isi outline" style="font-size:11px;padding:4px 10px;">
                                Lihat
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:#9CA3AF;padding:24px;">Tidak ada indikator untuk tahun ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Progress Pengisian --}}
    <div class="sct-card" style="margin-bottom:0;">
        <div class="card-title">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4F6EF7"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
            Progres Pengisian
        </div>

        <div class="prog-summary">
            <div>
                <div style="font-size:11px;color:#6C757D;margin-bottom:4px;">TOTAL PROGRESS</div>
                <div class="prog-pct">{{ $d['persen'] ?? 0 }}%</div>
                <div style="margin-top:8px;">
                    <div class="prog-wrap" style="width:120px;">
                        <div class="prog-bar" style="width:{{ $d['persen'] ?? 0 }}%;background:{{ ($d['persen'] ?? 0) >= 75 ? '#10b981' : '#4F6EF7' }};"></div>
                    </div>
                </div>
            </div>
            <div class="prog-legend">
                <div class="prog-legend-item">
                    <div class="prog-dot" style="background:#10b981;"></div>
                    Approved · {{ $d['approved'] ?? 0 }}
                </div>
                <div class="prog-legend-item">
                    <div class="prog-dot" style="background:#3b82f6;"></div>
                    Review · {{ $d['review'] ?? 0 }}
                </div>
                <div class="prog-legend-item">
                    <div class="prog-dot" style="background:#9CA3AF;"></div>
                    Belum Diisi · {{ $d['belumDisi'] ?? 0 }}
                </div>
                <div class="prog-legend-item">
                    <div class="prog-dot" style="background:#ef4444;"></div>
                    Revisi · {{ $d['revisi'] ?? 0 }}
                </div>
            </div>
        </div>

        <div class="deadline-box">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#D97706"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            <span><strong>Deadline:</strong> {{ $d['deadline'] ?? '-' }}</span>
        </div>

        <div style="margin-top:14px;">
            <a href="{{ route('filament.opd.pages.isi-evaluasi') }}"
               class="btn-isi" style="width:100%;justify-content:center;">
                Isi Evaluasi Sekarang →
            </a>
        </div>
    </div>
</div>

</div>
</x-filament-panels::page>