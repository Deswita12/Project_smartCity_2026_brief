<?php

namespace App\Filament\Widgets;

use App\Models\Submission;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;

class IndikatorLemahWidget extends BaseWidget
{
    protected static ?string $heading = 'Indikator Lemah';
    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        $year = now()->year;

        return $table
            ->query(
                Submission::query()
                    ->with(['indicator.dimension'])
                    ->where('year', $year)
                    ->where('status', 'approved')
                    ->selectRaw('indicator_id, AVG(final_score) as avg_score')
                    ->groupBy('indicator_id')
                    ->orderBy('avg_score')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('indicator.dimension.name')
                    ->label('Dimensi')
                    ->badge(),

                Tables\Columns\TextColumn::make('indicator.name')
                    ->label('Indikator')
                    ->wrap(),

                Tables\Columns\TextColumn::make('avg_score')
                    ->label('Nilai')
                    ->formatStateUsing(fn ($state) => number_format($state, 2))
                    ->badge()
                    ->color('danger'),
            ])
            ->paginated(false);
    }
}