<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Filament\Resources\SurveyResource\RelationManagers;
use App\Models\Survey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Tables\Filters\SelectFilter;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Kuesioner Survei';
    protected static ?string $navigationGroup = 'Survei Publik';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Kuesioner';
    protected static ?string $pluralModelLabel = 'Kuesioner';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Survei Publik')
                ->description('Survei ini akan diisi oleh masyarakat umum, bukan OPD.')
                ->schema([

                    Forms\Components\TextInput::make('name')
                        ->label('Nama Survei')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('year')
                        ->label('Tahun')
                        ->numeric()
                        ->required()
                        ->default(now()->year),

                    Forms\Components\DatePicker::make('period_start')
                        ->label('Periode Mulai')
                        ->required(),

                    Forms\Components\DatePicker::make('period_end')
                        ->label('Periode Selesai')
                        ->required()
                        ->after('period_start'),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft'   => 'Draft',
                            'active'   => 'Aktif',
                            'done' => 'Selesai',
                        ])
                        ->default('draft')
                        ->required(),

                    Forms\Components\TextInput::make('link_token')
                        ->label('Token Link Publik')
                        ->default(fn () => Str::random(32))
                        ->disabled()
                        ->dehydrated()
                        ->helperText('Link ini disebarkan ke masyarakat untuk mengisi survei'),

                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->columnSpanFull(),

                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Survei')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),

                Tables\Columns\TextColumn::make('period_start')
                    ->label('Periode')
                    ->formatStateUsing(fn ($record) =>
                        $record->period_start->format('d/m/Y') . ' – ' .
                        $record->period_end->format('d/m/Y')
                    ),

                Tables\Columns\TextColumn::make('questions_count')
                    ->label('Pertanyaan')
                    ->counts('questions')
                    ->badge()
                    ->color('info'),

                // Responden = yang mengisi survei publik
                Tables\Columns\TextColumn::make('respondents_count')
                    ->label('Responden')
                    ->getStateUsing(fn ($record) =>
                        \App\Models\Response::whereHas('question', fn ($q) =>
                            $q->where('survey_id', $record->id)
                        )
                        ->distinct('respondent_id')
                        ->count()
                    )
                    ->badge()
                    ->color('success'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'draft',
                        'success'   => 'active',
                        'info'      => 'done',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft'   => 'Draft',
                        'active'   => 'Aktif',
                        'done' => 'Selesai',
                    ]),

                Tables\Filters\SelectFilter::make('year')
                    ->label('Tahun')
                    ->options(
                        Survey::query()
                            ->distinct()
                            ->pluck('year', 'year')
                            ->toArray()
                    ),
            ])
            ->actions([
                // Salin link publik untuk disebarkan
                Tables\Actions\Action::make('copy_link')
                    ->label('Salin Link')
                    ->icon('heroicon-o-link')
                    ->color('info')
                    ->url(
                        fn ($record) => url('/survey/' . $record->link_token),
                        shouldOpenInNewTab: true
                    )
                    ->visible(fn ($record) => $record->status === 'active'),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index'  => Pages\ListSurveys::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'edit'   => Pages\EditSurvey::route('/{record}/edit'),
        ];
    }
}