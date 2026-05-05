<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndicatorResource\Pages;
use App\Filament\Resources\IndicatorResource\RelationManagers;
use App\Models\Indicator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use App\Models\SubDimension;

class IndicatorResource extends Resource
{
    protected static ?string $model = Indicator::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Indikator';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Indikator';
    protected static ?string $pluralModelLabel = 'Indikator';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Indikator')->schema([

                Forms\Components\Select::make('dimension_id')
                    ->label('Dimensi')
                    ->relationship('dimension', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live() // trigger update sub dimensi
                    ->afterStateUpdated(fn ($set) => $set('sub_dimension_id', null)),

                Forms\Components\Select::make('sub_dimension_id')
                    ->label('Sub Dimensi')
                    ->options(fn (Get $get) =>
                        SubDimension::where('dimension_id', $get('dimension_id'))
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->nullable(),

                Forms\Components\TextInput::make('name')
                    ->label('Nama Indikator')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('code')
                    ->label('Kode Indikator')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),

                Forms\Components\TextInput::make('iso_standard')
                    ->label('Standar ISO')
                    ->placeholder('contoh: ISO 37122:4.1')
                    ->maxLength(100),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),

            ])->columns(2),

            Forms\Components\Section::make('Pengaturan')->schema([

                Forms\Components\Select::make('task_owner_id')
                    ->label('Task Owner (OPD)')
                    ->relationship('taskOwner', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                // Forms\Components\TextInput::make('weight')
                //     ->label('Bobot')
                //     ->numeric()
                //     ->default(1.00)
                //     ->minValue(0)
                //     ->maxValue(10),

                Forms\Components\TextInput::make('year')
                    ->label('Tahun')
                    ->numeric()
                    ->default(now()->year),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'active'   => 'Aktif',
                        'inactive' => 'Nonaktif',
                    ])
                    ->default('active')
                    ->required(),

            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Indikator')
                    ->searchable()
                    ->wrap()
                    ->sortable(),

                Tables\Columns\TextColumn::make('dimension.name')
                    ->label('Dimensi')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('subDimension.name')
                    ->label('Sub Dimensi')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('taskOwner.name')
                    ->label('Task Owner')
                    ->placeholder('-')
                    ->searchable(),

                // Tables\Columns\TextColumn::make('weight')
                //     ->label('Bobot')
                //     ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger'  => 'inactive',
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'active' ? 'Aktif' : 'Nonaktif'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('dimension_id')
                    ->label('Dimensi')
                    ->relationship('dimension', 'name')
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active'   => 'Aktif',
                        'inactive' => 'Nonaktif',
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
            'index'  => Pages\ListIndicators::route('/'),
            'create' => Pages\CreateIndicator::route('/create'),
            'edit'   => Pages\EditIndicator::route('/{record}/edit'),
        ];
    }
}