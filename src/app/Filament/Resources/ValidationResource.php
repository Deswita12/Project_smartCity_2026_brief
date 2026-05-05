<?php

namespace App\Filament\Resources;


use App\Filament\Resources\ValidationResource\Pages;
use App\Filament\Resources\ValidationResource\RelationManagers;
use App\Models\Validation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Submission;

class ValidationResource extends Resource
{
    protected static ?string $model = Validation::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Validasi';
    protected static ?string $navigationGroup = 'Evaluasi';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Validasi';
    protected static ?string $pluralModelLabel = 'Validasi';

    // Hanya tampil submission yang status review
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Submission::query()
            ->with(['opd', 'indicator.dimension', 'evidences'])
            ->whereIn('status', ['submitted', 'review']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Detail Indikator')->schema([

                Forms\Components\Placeholder::make('indicator_name')
                    ->label('Indikator')
                    ->content(fn ($record) => $record?->indicator?->name ?? '-'),

                Forms\Components\Placeholder::make('dimension_name')
                    ->label('Dimensi')
                    ->content(fn ($record) => $record?->indicator?->dimension?->name ?? '-'),

                Forms\Components\Placeholder::make('opd_name')
                    ->label('OPD')
                    ->content(fn ($record) => $record?->opd?->name ?? '-'),

                Forms\Components\Placeholder::make('survey_score')
                    ->label('Nilai Survei')
                    ->content(fn ($record) => $record?->survey_score ?? '-'),

            ])->columns(2),

            Forms\Components\Section::make('Jawaban & Bukti OPD')->schema([

                Forms\Components\Placeholder::make('answer_text')
                    ->label('Jawaban OPD')
                    ->content(fn ($record) => $record?->answer_text ?? '-')
                    ->columnSpanFull(),

            ]),

            Forms\Components\Section::make('Keputusan Validasi')->schema([

                Forms\Components\Select::make('status')
                    ->label('Keputusan')
                    ->options([
                        'approved' => 'Approve',
                        'revisi'   => 'Minta Revisi',
                        'tolak'    => 'Tolak',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('notes')
                    ->label('Catatan untuk OPD')
                    ->columnSpanFull(),

            ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('indicator.dimension.name')
                    ->label('Dimensi')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('indicator.name')
                    ->label('Indikator')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('opd.name')
                    ->label('OPD')
                    ->searchable(),

                Tables\Columns\TextColumn::make('survey_score')
                    ->label('Nilai Survei')
                    ->numeric(decimalPlaces: 2),

                Tables\Columns\TextColumn::make('evidences_count')
                    ->label('Bukti')
                    ->counts('evidences')
                    ->badge()
                    ->color('info'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'submitted',
                        'info'    => 'review',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'submitted' => 'Submitted',
                        'review'    => 'Review',
                    ]),

                Tables\Filters\SelectFilter::make('opd_id')
                    ->label('OPD')
                    ->relationship('opd', 'name')
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Submission $record) {
                        $record->update(['status' => 'approved']);

                        Validation::create([
                            'submission_id' => $record->id,
                            'admin_id'      => auth()->id(),
                            'status'        => 'approved',
                            'notes'         => null,
                        ]);

                        Notification::make()
                            ->title('Berhasil di-approve')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('revisi')
                    ->label('Minta Revisi')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->form([
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Revisi')
                            ->required(),
                    ])
                    ->action(function (Submission $record, array $data) {
                        $record->update(['status' => 'revisi']);

                        Validation::create([
                            'submission_id' => $record->id,
                            'admin_id'      => auth()->id(),
                            'status'        => 'revisi',
                            'notes'         => $data['notes'],
                        ]);

                        Notification::make()
                            ->title('Revisi diminta')
                            ->warning()
                            ->send();
                    }),

                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListValidations::route('/'),
            // 'view'  => Pages\ViewValidation::route('/{record}'),
        ];
    }
}