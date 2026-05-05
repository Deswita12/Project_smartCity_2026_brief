<x-filament-panels::page>

    {{-- ── Header ── --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl font-semibold tracking-tight
                       text-gray-900 dark:text-white">
                Dashboard
            </h1>
            <p class="text-sm text-gray-400 mt-0.5">
                Smart City Tangerang &mdash; {{ $this->selectedYear }}
            </p>
        </div>

        {{-- Filter tahun (Livewire reactive) --}}
        <select
            wire:model.live="selectedYear"
            class="text-sm rounded-lg border border-gray-300 dark:border-gray-600
                   bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100
                   px-3 py-1.5 focus:outline-none focus:ring-2
                   focus:ring-slate-400 shadow-sm">
            @foreach($this->getAvailableYears() as $yr)
                <option value="{{ $yr }}">{{ $yr }}</option>
            @endforeach
        </select>
    </div>

    {{-- ── Row 1: KPI cards (full width) ── --}}
    @livewire(\App\Filament\Widgets\StatsOverviewWidget::class,
        ['year' => $this->selectedYear],
        key('stats-'.$this->selectedYear))

    {{-- ── Row 2: Bar chart 2/3 + Donut 1/3 ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mt-5">
        <div class="lg:col-span-2">
            @livewire(\App\Filament\Widgets\NilaiPerDimensiChart::class,
                ['year' => $this->selectedYear],
                key('bar-'.$this->selectedYear))
        </div>
        <div>
            @livewire(\App\Filament\Widgets\StatusSubmissionChart::class,
                ['year' => $this->selectedYear],
                key('donut-'.$this->selectedYear))
        </div>
    </div>

    {{-- ── Row 3: Ranking OPD + Indikator Lemah ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mt-5">
        @livewire(\App\Filament\Widgets\RankingOpdWidget::class,
            ['year' => $this->selectedYear],
            key('rank-'.$this->selectedYear))
        @livewire(\App\Filament\Widgets\IndikatorLemahWidget::class,
            ['year' => $this->selectedYear],
            key('lemah-'.$this->selectedYear))
    </div>

    {{-- ── Row 4: Activity log (full width) ── --}}
    <div class="mt-5">
        @livewire(\App\Filament\Widgets\ActivityLogWidget::class,
            key('log'))
    </div>

</x-filament-panels::page>