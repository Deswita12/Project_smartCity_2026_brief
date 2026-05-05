<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationLabel = 'Pertanyaan Survei';
    protected static ?string $navigationGroup = 'Survei Publik';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Pertanyaan';
    protected static ?string $pluralModelLabel = 'Pertanyaan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Pertanyaan untuk Responden Publik')
                ->description('Pertanyaan ini akan muncul di form survei yang diisi masyarakat umum.')
                ->schema([

                    Forms\Components\Select::make('survey_id')
                        ->label('Survei')
                        ->relationship('survey', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('dimension_id')
                        ->label('Dimensi')
                        ->relationship('dimension', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn ($set) => $set('indicator_id', null)),

                    Forms\Components\Select::make('indicator_id')
                        ->label('Indikator Terkait')
                        ->options(fn (Get $get) =>
                            \App\Models\Indicator::where('dimension_id', $get('dimension_id'))
                                ->where('status', 'active')
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->nullable()
                        ->helperText('Opsional — untuk menghubungkan jawaban responden ke indikator'),

                    Forms\Components\Select::make('type')
                        ->label('Format Jawaban')
                        ->options([
                            'scale'    => 'Skala 1–5 (Likert)',
                            'text' => 'Jawaban Ringkas',
                        ])
                        ->default('scale')
                        ->required(),

                    Forms\Components\TextInput::make('order')
                        ->label('Urutan Tampil')
                        ->numeric()
                        ->default(0),

                    Forms\Components\Textarea::make('question_text')
                        ->label('Teks Pertanyaan')
                        ->required()
                        ->columnSpanFull()
                        ->placeholder('Contoh: Seberapa mudah Anda mengakses informasi publik?'),

                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('No')
                    ->sortable()
                    ->width(50),

                Tables\Columns\TextColumn::make('survey.name')
                    ->label('Survei')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('dimension.name')
                    ->label('Dimensi')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('question_text')
                    ->label('Pertanyaan')
                    ->limit(60)
                    ->wrap(),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Format')
                    ->badge()
                    ->colors([
                        'info'    => 'scale',
                        'warning' => 'text',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'scale'    => 'Skala 1-5',
                        'text' => 'Jawaban Ringkas',
                        default        => $state,
                    }),

                // Berapa responden yang sudah jawab pertanyaan ini
                Tables\Columns\TextColumn::make('responses_count')
                    ->label('Dijawab')
                    ->counts('responses')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('responses_avg_value')
                    ->label('Rata-rata')
                    ->getStateUsing(fn ($record) =>
                        number_format($record->responses()->avg('value') ?? 0, 2)
                    )
                    ->badge()
                    ->color('info'),
            ])
            ->defaultSort('order')
            ->reorderable('order') // drag & drop urutan pertanyaan
            ->filters([
                Tables\Filters\SelectFilter::make('survey_id')
                    ->label('Survei')
                    ->relationship('survey', 'name')
                    ->preload(),

                Tables\Filters\SelectFilter::make('dimension_id')
                    ->label('Dimensi')
                    ->relationship('dimension', 'name')
                    ->preload(),

                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'scale'    => 'Skala 1-5',
                        'text' => 'Jawaban Ringkas',
                    ]),
            ])
            ->actions([
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
            'index'  => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit'   => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}