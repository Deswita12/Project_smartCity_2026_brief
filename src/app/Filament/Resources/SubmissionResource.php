<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubmissionResource\Pages;
use App\Filament\Resources\SubmissionResource\RelationManagers;
use App\Models\Submission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class SubmissionResource extends Resource
{
    protected static ?string $model = Submission::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Evaluasi';
    protected static ?string $navigationGroup = 'Evaluasi';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Evaluasi';
    protected static ?string $pluralModelLabel = 'Evaluasi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Detail Evaluasi')->schema([

                Forms\Components\Select::make('opd_id')
                    ->label('OPD')
                    ->relationship('opd', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('indicator_id')
                    ->label('Indikator')
                    ->relationship('indicator', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('year')
                    ->label('Tahun')
                    ->numeric()
                    ->default(now()->year)
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft'     => 'Draft',
                        'submitted' => 'Submitted',
                        'review'    => 'Review',
                        'revisi'    => 'Revisi',
                        'approved'  => 'Approved',
                        'finished'  => 'Finished',
                    ])
                    ->default('draft')
                    ->required(),

                Forms\Components\TextInput::make('survey_score')
                    ->label('Nilai Survei')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),

                Forms\Components\TextInput::make('final_score')
                    ->label('Nilai Final')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),

                Forms\Components\Textarea::make('answer_text')
                    ->label('Jawaban OPD')
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('notes')
                    ->label('Catatan Tambahan')
                    ->columnSpanFull(),

            ])->columns(2),
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
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('survey_score')
                    ->label('Nilai Survei')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                Tables\Columns\TextColumn::make('evidences_count')
                    ->label('Bukti')
                    ->counts('evidences')
                    ->badge()
                    ->color('info'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning'   => 'submitted',
                        'info'      => 'review',
                        'danger'    => 'revisi',
                        'success'   => 'approved',
                    ]),

                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft'     => 'Draft',
                        'submitted' => 'Submitted',
                        'review'    => 'Review',
                        'revisi'    => 'Revisi',
                        'approved'  => 'Approved',
                    ]),

                SelectFilter::make('dimension')
                    ->label('Dimensi')
                    ->relationship('indicator.dimension', 'name'),

                SelectFilter::make('opd_id')
                    ->label('OPD')
                    ->relationship('opd', 'name')
                    ->preload(),

                Tables\Filters\SelectFilter::make('year')
                    ->label('Tahun')
                    ->options(
                        Submission::query()
                            ->distinct()
                            ->pluck('year', 'year')
                            ->toArray()
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSubmissions::route('/'),
            'create' => Pages\CreateSubmission::route('/create'),
            'edit'   => Pages\EditSubmission::route('/{record}/edit'),
            // 'view'   => Pages\ViewSubmission::route('/{record}'),
        ];
    }
}