<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OpdResource\Pages;
use App\Filament\Resources\OpdResource\RelationManagers;
use App\Models\Opd;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class OpdResource extends Resource
{
    protected static ?string $model = Opd::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Manajemen OPD';
    protected static ?string $navigationGroup = 'Manajemen';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'OPD';
    protected static ?string $pluralModelLabel = 'OPD';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi OPD')->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Nama OPD')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('code')
                    ->label('Kode OPD')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),

                // Forms\Components\TextInput::make('user.email')
                //     ->label('Email')
                //     ->email()
                //     ->nullable(),
                

                // Forms\Components\TextInput::make('phone')
                //     ->label('Telepon')
                //     ->nullable(),

                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'aktif',
                        'inactive' => 'nonaktif',
                    ])
                    ->default('active')
                    ->required(),

                // Forms\Components\DatePicker::make('joined_at')
                //     ->label('Tanggal Bergabung')
                //     ->default(now()),

                

            ])->columns(2),

            Forms\Components\Section::make('Akun Login OPD')
                ->description('Username dan password untuk login ke portal OPD')
                ->schema([

                    Forms\Components\TextInput::make('user.name')
                        ->label('Nama Pengguna')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('user.email')
                        ->label('Email Login')
                        ->email()
                        ->required()
                        ->unique('users', 'email', ignoreRecord: true),

                    Forms\Components\TextInput::make('user.password')
                        ->label('Password Awal')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation) => $operation === 'create'),

                ])
                ->columns(2)
                ->visibleOn('create', 'edit'), // password section hanya saat create
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama OPD')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('user.password')
                    ->label('Password')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('submissions_count')
                    ->label('File')
                    ->counts('submissions')
                    ->badge()
                    ->color('info'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger'  => 'inactive',
                    ]),

                // Tables\Columns\TextColumn::make('joined_at')
                //     ->label('Bergabung')
                //     ->date('d M Y'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active'    => 'Aktif',
                        'inactive' => 'Nonaktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index'  => Pages\ListOpds::route('/'),
            'create' => Pages\CreateOpd::route('/create'),
            'edit'   => Pages\EditOpd::route('/{record}/edit'),
            // 'view' => Pages\ViewOpd::route('/{record}'),
        ];
    }

    
}