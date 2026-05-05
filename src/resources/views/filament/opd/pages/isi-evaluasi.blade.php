<x-filament-panels::page>

<style>
/* ── Base ── */
.ev-wrap { background: #F8F9FA; }

/* ── Wizard stepper ── */
.stepper {
    display: flex; align-items: center; gap: 0;
    background: #fff; border: 1px solid #E9ECEF;
    border-radius: 12px; padding: 16px 20px;
    margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}
.step-item { display: flex; align-items: center; gap: 10px; flex: 1; }
.step-circle {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 600; flex-shrink: 0;
    transition: all .2s;
}
.step-circle.done   { background: #D1FAE5; color: #059669; }
.step-circle.active { background: #4F6EF7; color: #fff; }
.step-circle.pending{ background: #F3F4F6; color: #9CA3AF; }
.step-label { font-size: 12px; font-weight: 500; }
.step-label.active  { color: #1A1D23; }
.step-label.pending { color: #9CA3AF; }
.step-label.done    { color: #059669; }
.step-connector { flex: 1; height: 1px; background: #E9ECEF; margin: 0 8px; max-width: 40px; }
.step-connector.done { background: #10b981; }

/* ── Card ── */
.ev-card {
    background: #fff; border: 1px solid #E9ECEF;
    border-radius: 12px; padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    margin-bottom: 16px;
}
.ev-card-title {
    font-size: 14px; font-weight: 600; color: #1A1D23;
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 16px; padding-bottom: 12px;
    border-bottom: 1px solid #F3F4F6;
}

/* ── Indicator list (step 1) ── */
.ind-list { display: flex; flex-direction: column; gap: 8px; }
.ind-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 14px; border: 1px solid #E9ECEF; border-radius: 10px;
    cursor: pointer; transition: all .15s; background: #fff;
}
.ind-item:hover { border-color: #4F6EF7; background: #F5F7FF; }
.ind-item.selected { border-color: #4F6EF7; background: #EEF2FF; }
.ind-item.done { border-color: #D1FAE5; background: #F0FDF4; }
.ind-name { font-size: 13px; font-weight: 500; color: #1A1D23; }
.ind-dim  { font-size: 11px; color: #6C757D; margin-top: 2px; }

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

/* ── Form elements ── */
.ev-label {
    display: block; font-size: 12px; font-weight: 500;
    color: #374151; margin-bottom: 6px; letter-spacing: .02em;
}
.ev-label span { color: #EF4444; }
.ev-textarea {
    width: 100%; border: 1px solid #DEE2E6; border-radius: 8px;
    padding: 10px 14px; font-size: 13.5px; font-family: inherit;
    color: #1A1D23; resize: vertical; outline: none;
    transition: border-color .15s; background: #fff;
    min-height: 120px;
}
.ev-textarea:focus { border-color: #4F6EF7; box-shadow: 0 0 0 3px rgba(79,110,247,0.08); }

/* ── Upload area ── */
.upload-area {
    border: 2px dashed #DEE2E6; border-radius: 10px;
    padding: 32px; text-align: center; cursor: pointer;
    transition: all .2s; background: #FAFAFA;
}
.upload-area:hover { border-color: #4F6EF7; background: #F5F7FF; }
.upload-area svg   { margin: 0 auto 10px; opacity: .4; }
.upload-title { font-size: 14px; font-weight: 500; color: #374151; }
.upload-sub   { font-size: 12px; color: #9CA3AF; margin-top: 4px; }

/* ── File chip ── */
.file-chip {
    display: flex; align-items: center; justify-content: space-between;
    padding: 8px 12px; background: #F8F9FA;
    border: 1px solid #E9ECEF; border-radius: 8px;
    margin-top: 8px;
}
.file-chip-name { font-size: 12.5px; color: #374151; display: flex; align-items: center; gap: 7px; }
.file-chip-size { font-size: 11px; color: #9CA3AF; }

/* ── Desc box (indikator info) ── */
.desc-box {
    background: #F5F7FF; border: 1px solid #E0E7FF;
    border-radius: 10px; padding: 14px 16px; margin-bottom: 16px;
}
.desc-box-label { font-size: 11px; font-weight: 600; color: #6C757D; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px; }
.desc-box-text  { font-size: 13px; color: #374151; line-height: 1.6; }

/* ── Buttons ── */
.btn { display: inline-flex; align-items: center; gap: 6px; border-radius: 8px; padding: 8px 16px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all .15s; border: none; font-family: inherit; }
.btn-primary   { background: #4F6EF7; color: #fff; }
.btn-primary:hover { background: #3B5BDB; }
.btn-outline   { background: transparent; color: #4F6EF7; border: 1px solid #4F6EF7; }
.btn-outline:hover { background: #EEF2FF; }
.btn-ghost     { background: transparent; color: #6C757D; border: 1px solid #DEE2E6; }
.btn-ghost:hover { background: #F3F4F6; }
.btn-success   { background: #10B981; color: #fff; }
.btn-success:hover { background: #059669; }

/* ── Success screen ── */
.success-box {
    text-align: center; padding: 48px 32px;
    background: #fff; border: 1px solid #E9ECEF;
    border-radius: 16px;
}
.success-icon {
    width: 64px; height: 64px; border-radius: 50%;
    background: #D1FAE5; display: flex; align-items: center;
    justify-content: center; margin: 0 auto 16px;
}

/* ── Prev file list ── */
.prev-file { display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #F3F4F6; }
.prev-file:last-child { border-bottom: none; }
</style>

<div class="ev-wrap">

{{-- ── Stepper ── --}}
<div class="stepper">
    @php
        $steps = ['Pilih Indikator','Isi Jawaban','Upload Bukti','Submit'];
    @endphp
    @foreach($steps as $i => $label)
        @php $n = $i + 1; @endphp
        <div class="step-item">
            <div class="step-circle {{ $this->step > $n ? 'done' : ($this->step === $n ? 'active' : 'pending') }}">
                @if($this->step > $n)✓@else{{ $n }}@endif
            </div>
            <div class="step-label {{ $this->step > $n ? 'done' : ($this->step === $n ? 'active' : 'pending') }}">
                {{ $label }}
            </div>
        </div>
        @if($i < 3)
            <div class="step-connector {{ $this->step > $n ? 'done' : '' }}"></div>
        @endif
    @endforeach
</div>

{{-- ════════════════════════════════════ --}}
{{-- STEP 1: Pilih Indikator             --}}
{{-- ════════════════════════════════════ --}}
@if($this->step === 1)
<div class="ev-card">
    <div class="ev-card-title">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4F6EF7"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
        Pilih Indikator yang Akan Diisi
    </div>

    <div class="ind-list">
    @foreach($this->indicators as $ind)
        @php $sub = $ind->submission; $status = $sub ? $sub->status : 'belum'; @endphp
        <div class="ind-item {{ $status === 'approved' ? 'done' : '' }} {{ $this->selectedId === $ind->id ? 'selected' : '' }}"
             wire:click="selectIndicator({{ $ind->id }})">
            <div>
                <div class="ind-name">{{ $ind->name }}</div>
                <div class="ind-dim">
                    {{ $ind->dimension->name ?? '—' }}
                    @if($ind->iso_standard) · {{ $ind->iso_standard }} @endif
                    · Bobot {{ $ind->weight }}
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:8px;">
                @if($status === 'approved')
                    <span class="badge badge-approved">✓ Approved</span>
                @elseif($status === 'review')
                    <span class="badge badge-review">Review</span>
                @elseif($status === 'revisi')
                    <span class="badge badge-revisi">↩ Revisi</span>
                @elseif($status === 'draft')
                    <span class="badge badge-draft">Draft</span>
                @else
                    <span class="badge badge-belum">Belum Diisi</span>
                @endif
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" style="color:#9CA3AF;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </div>
        </div>
    @endforeach
    </div>
</div>

{{-- ════════════════════════════════════ --}}
{{-- STEP 2: Isi Jawaban                 --}}
{{-- ════════════════════════════════════ --}}
@elseif($this->step === 2)
@php $ind = $this->selectedIndicator; @endphp

<div class="ev-card">
    <div class="ev-card-title">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4F6EF7"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
        Isi Jawaban — {{ $ind?->name }}
    </div>

    @if($ind)
    {{-- Info indikator --}}
    <div class="desc-box">
        <div class="desc-box-label">Deskripsi Indikator</div>
        <div class="desc-box-text">{{ $ind->description ?? 'Tidak ada deskripsi.' }}</div>
    </div>

    @if($this->submission?->status === 'revisi')
    @php $lastVal = $this->submission->validations()->latest()->first(); @endphp
    @if($lastVal?->notes)
    <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:12px 14px;margin-bottom:14px;">
        <div style="font-size:11px;font-weight:600;color:#991B1B;margin-bottom:4px;">CATATAN REVISI DARI ADMIN</div>
        <div style="font-size:13px;color:#7F1D1D;">{{ $lastVal->notes }}</div>
    </div>
    @endif
    @endif

    {{-- File tahun sebelumnya --}}
    @if($this->submission?->evidences?->count())
    <div style="margin-bottom:14px;">
        <div class="ev-label">File Tahun Sebelumnya</div>
        @foreach($this->submission->evidences as $ev)
        <div class="prev-file">
            <div class="file-chip-name">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4F6EF7"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                {{ $ev->file_name }}
            </div>
            <span class="file-chip-size">{{ number_format(($ev->file_size ?? 0)/1024/1024, 1) }} MB</span>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Form jawaban --}}
    <div style="margin-bottom:14px;">
        <label class="ev-label">Jawaban / Deskripsi Implementasi <span>*</span></label>
        <textarea
            wire:model.live="answer"
            class="ev-textarea"
            rows="5"
            placeholder="Jelaskan implementasi dan kondisi terkini dari indikator ini di instansi Anda...">{{ $this->answer }}</textarea>
    </div>

    <div style="margin-bottom:20px;">
        <label class="ev-label">Catatan Tambahan <span style="color:#9CA3AF;font-weight:400;">(opsional)</span></label>
        <textarea
            wire:model.live="additionalNotes"
            class="ev-textarea"
            rows="3"
            placeholder="Catatan, kendala, atau rencana ke depan...">{{ $this->additionalNotes }}</textarea>
    </div>
    @endif

    <div style="display:flex;gap:8px;justify-content:space-between;">
        <button wire:click="goStep(1)" class="btn btn-ghost">← Kembali</button>
        <div style="display:flex;gap:8px;">
            <button wire:click="saveDraft" class="btn btn-outline" {{ !$this->answer ? 'disabled' : '' }}>
                Simpan Draft
            </button>
            <button wire:click="goStep(3)" class="btn btn-primary" {{ !$this->answer ? 'disabled' : '' }}>
                Upload Bukti →
            </button>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════ --}}
{{-- STEP 3: Upload Bukti                --}}
{{-- ════════════════════════════════════ --}}
@elseif($this->step === 3)

<div class="ev-card">
    <div class="ev-card-title">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4F6EF7"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
        Upload Bukti Dukung
    </div>

    {{-- Upload component Filament / native --}}
    <div>
        {{ $this->form }}
    </div>

    {{-- Divider --}}
    <div style="height:1px;background:#F3F4F6;margin:16px 0;"></div>

    <div style="font-size:12px;color:#9CA3AF;margin-bottom:16px;">
        Format yang diterima: PDF, JPG, PNG — maks. 10MB per file
    </div>

    <div style="display:flex;gap:8px;justify-content:space-between;">
        <button wire:click="goStep(2)" class="btn btn-ghost">← Kembali</button>
        <button wire:click="submitEvaluasi" class="btn btn-success">
            Submit Evaluasi →
        </button>
    </div>
</div>

{{-- ════════════════════════════════════ --}}
{{-- STEP 4: Sukses                      --}}
{{-- ════════════════════════════════════ --}}
@elseif($this->step === 4)

<div class="success-box">
    <div class="success-icon">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#059669"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
    </div>
    <h2 style="font-size:18px;font-weight:600;color:#1A1D23;margin-bottom:8px;">Terima Kasih!</h2>
    <p style="font-size:13.5px;color:#6C757D;max-width:360px;margin:0 auto 20px;line-height:1.6;">
        Jawaban Anda telah berhasil dikirim dan akan digunakan untuk meningkatkan kualitas layanan Smart City Kota Tangerang.
    </p>
    <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
        <button wire:click="$set('step', 1)" class="btn btn-outline">
            Isi Indikator Lain
        </button>
        <a href="{{ route('filament.opd.pages.riwayat-submission') }}" class="btn btn-primary">
            Lihat Riwayat →
        </a>
    </div>
</div>

@endif

</div>

</x-filament-panels::page>