<?php

namespace App\Filament\Widgets;

use App\Models\Opd;
use App\Models\Submission;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RankingOpdWidget extends BaseWidget
{
    protected static ?string $heading = 'Ranking OPD';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $year = now()->year;

        return $table
            ->query(
                Opd::query()
                    ->withCount([
                        'submissions as file_count' => fn (Builder $q) =>
                            $q->where('year', $year),
                    ])
                    ->withAvg(
                        ['submissions as avg_score' => fn (Builder $q) =>
                            $q->where('year', $year)->where('status', 'approved')
                        ],
                        'final_score'
                    )
                    ->orderByDesc('file_count')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('rank')
                    ->label('Rank')
                    ->rowIndex()
                    ->width(60),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama OPD'),

                Tables\Columns\TextColumn::make('avg_score')
                    ->label('Nilai')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) : '-')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 4.0 => 'success',
                        $state >= 3.0 => 'warning',
                        default       => 'danger',
                    }),

                Tables\Columns\TextColumn::make('file_count')
                    ->label('Total File')
                    ->badge()
                    ->color('info'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'aktif',
                        'danger'  => 'nonaktif',
                    ]),
            ])
            ->paginated(false);
    }
}