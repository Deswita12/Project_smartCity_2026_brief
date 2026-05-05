<x-filament-panels::page>

<style>
.rw-card { background:#fff;border:1px solid #E9ECEF;border-radius:12px;padding:20px;box-shadow:0 1px 3px rgba(0,0,0,0.04); }
.rw-table { width:100%;border-collapse:collapse; }
.rw-table th { font-size:11px;font-weight:600;color:#6C757D;text-transform:uppercase;letter-spacing:.05em;padding:8px 12px;border-bottom:2px solid #F3F4F6;text-align:left;background:#FAFAFA; }
.rw-table td { padding:12px 12px;border-bottom:1px solid #F9FAFB;font-size:13px;color:#374151;vertical-align:middle; }
.rw-table tr:last-child td { border-bottom:none; }
.rw-table tr:hover td { background:#FAFCFF; }
.badge { display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:500;border-radius:20px;padding:2px 9px; }
.badge-approved { background:#D1FAE5;color:#065F46; }
.badge-review   { background:#DBEAFE;color:#1E40AF; }
.badge-revisi   { background:#FEE2E2;color:#991B1B; }
.badge-draft    { background:#F3F4F6;color:#374151; }
.badge-submitted{ background:#E0E7FF;color:#3730A3; }
.btn-sm { display:inline-flex;align-items:center;font-size:12px;font-weight:500;border-radius:6px;padding:4px 10px;cursor:pointer;border:none;font-family:inherit;text-decoration:none;transition:all .15s; }
.btn-view   { background:#EEF2FF;color:#4F6EF7; }
.btn-view:hover { background:#E0E7FF; }
.btn-revisi { background:#FEE2E2;color:#DC2626; }
.btn-revisi:hover { background:#FEE; }
.dim-badge  { display:inline-block;font-size:10.5px;font-weight:500;border-radius:5px;padding:2px 7px; }
.note-box   { background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:8px 10px;font-size:12px;color:#7F1D1D;margin-top:4px; }
.empty-state { text-align:center;padding:48px;color:#9CA3AF; }
</style>

<div class="rw-card">
    <div style="font-size:14px;font-weight:600;color:#1A1D23;display:flex;align-items:center;gap:8px;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #F3F4F6;">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4F6EF7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
        Riwayat Submission
    </div>

    @if($this->submissions->count())
    <table class="rw-table">
        <thead>
            <tr>
                <th>Indikator</th>
                <th>Dimensi</th>
                <th>Tgl Submit</th>
                <th>Nilai Survey</th>
                <th>Status</th>
                <th>Catatan Admin</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($this->submissions as $sub)
            @php
                $lastVal = $sub->validations->sortByDesc('created_at')->first();
                $dimColor = $sub->indicator->dimension->color ?? '#4F6EF7';
            @endphp
            <tr>
                <td style="font-weight:500;max-width:200px;">
                    {{ Str::limit($sub->indicator->name ?? '—', 36) }}
                </td>
                <td>
                    <span class="dim-badge" style="background:{{ $dimColor }}18;color:{{ $dimColor }};">
                        {{ $sub->indicator->dimension->name ?? '—' }}
                    </span>
                </td>
                <td style="white-space:nowrap;color:#6C757D;font-size:12px;">
                    {{ $sub->submitted_at ? $sub->submitted_at->format('d M Y') : '—' }}
                </td>
                <td style="font-weight:600;color:{{ $sub->survey_score >= 4 ? '#059669' : ($sub->survey_score >= 3 ? '#D97706' : '#DC2626') }};">
                    {{ $sub->survey_score ? number_format($sub->survey_score, 1) : '—' }}
                </td>
                <td>
                    @php $s = $sub->status; @endphp
                    @if($s === 'approved')
                        <span class="badge badge-approved">✓ Approved</span>
                    @elseif($s === 'review')
                        <span class="badge badge-review">⏳ Review</span>
                    @elseif($s === 'revisi')
                        <span class="badge badge-revisi">↩ Revisi</span>
                    @elseif($s === 'submitted')
                        <span class="badge badge-submitted">Terkirim</span>
                    @else
                        <span class="badge badge-draft">Draft</span>
                    @endif
                </td>
                <td style="max-width:180px;">
                    @if($lastVal?->notes)
                        <div class="note-box">{{ Str::limit($lastVal->notes, 60) }}</div>
                    @else
                        <span style="color:#D1D5DB;font-size:12px;">—</span>
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:5px;">
                        <a href="{{ route('filament.opd.pages.isi-evaluasi', ['indicator' => $sub->indicator_id]) }}"
                           class="btn-sm btn-view">Lihat</a>
                        @if($s === 'revisi')
                        <a href="{{ route('filament.opd.pages.isi-evaluasi', ['indicator' => $sub->indicator_id]) }}"
                           class="btn-sm btn-revisi">Revisi</a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"/></svg>
        <p>Belum ada submission.</p>
        <a href="{{ route('filament.opd.pages.isi-evaluasi') }}" style="color:#4F6EF7;font-size:13px;margin-top:8px;display:inline-block;">Mulai isi evaluasi →</a>
    </div>
    @endif
</div>

</x-filament-panels::page>