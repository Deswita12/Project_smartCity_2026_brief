<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubDimensionResource\Pages;
use App\Filament\Resources\SubDimensionResource\RelationManagers;
use App\Models\SubDimension;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubDimensionResource extends Resource
{
    protected static ?string $model = SubDimension::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Sub Dimensi';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Sub Dimensi';
    protected static ?string $pluralModelLabel = 'Sub Dimensi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([

                Forms\Components\Select::make('dimension_id')
                    ->label('Dimensi')
                    ->relationship('dimension', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('name')
                    ->label('Nama Sub Dimensi')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),

                // Forms\Components\TextInput::make('code')
                //     ->label('Kode')
                //     ->required()
                //     ->dehydrated(true),

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
                Tables\Columns\TextColumn::make('dimension.name')
                    ->label('Dimensi')
                    ->badge()
                    ->color(fn ($record) => 'info')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Sub Dimensi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('indicators_count')
                    ->label('Indikator')
                    ->counts('indicators')
                    ->badge()
                    ->color('success'),
            ])
            ->defaultSort('order')
            ->filters([
                Tables\Filters\SelectFilter::make('dimension_id')
                    ->label('Dimensi')
                    ->relationship('dimension', 'name')
                    ->preload(),
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
            'index'  => Pages\ListSubDimensions::route('/'),
            'create' => Pages\CreateSubDimension::route('/create'),
            'edit'   => Pages\EditSubDimension::route('/{record}/edit'),
        ];
    }
}