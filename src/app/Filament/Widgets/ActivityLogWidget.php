<?php

namespace App\Filament\Widgets;

use App\Models\AuditLog;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;

class ActivityLogWidget extends BaseWidget
{
    protected static ?string $heading = 'Aktivitas Terbaru';
    protected static ?int $sort = 6;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AuditLog::query()
                    ->with('user')
                    ->latest('created_at')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->placeholder('System'),

                Tables\Columns\TextColumn::make('action')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'approved' => 'success',
                        'revisi'   => 'warning',
                        'created'  => 'info',
                        'deleted'  => 'danger',
                        default    => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->wrap()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}