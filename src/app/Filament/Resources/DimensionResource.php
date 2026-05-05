<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DimensionResource\Pages;
use App\Filament\Resources\DimensionResource\RelationManagers;
use App\Models\Dimension;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DimensionResource extends Resource
{
    protected static ?string $model = Dimension::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Dimensi';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Dimensi';
    protected static ?string $pluralModelLabel = 'Dimensi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Nama Dimensi')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('code')
                    ->label('Kode')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),

                Forms\Components\ColorPicker::make('color')
                    ->label('Warna')
                    ->default('#3B82F6'),

                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),

            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->width(50),

                Tables\Columns\ColorColumn::make('color')
                    ->label('Warna'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Dimensi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('indicators_count')
                    ->label('Indikator')
                    ->counts('indicators')
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
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
            'index'  => Pages\ListDimensions::route('/'),
            'create' => Pages\CreateDimension::route('/create'),
            'edit'   => Pages\EditDimension::route('/{record}/edit'),
        ];
    }
}
