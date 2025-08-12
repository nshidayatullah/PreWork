<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';
    protected static ?string $navigationLabel = 'Data Karyawan';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Karyawan';
    protected static ?string $navigationGroup = 'Karyawan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Karyawan')
                    ->schema([
                        Forms\Components\TextInput::make('Name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('NRP')
                            ->label('NRP')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20),

                        Forms\Components\TextInput::make('Position')
                            ->label('Jabatan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('Departement')
                            ->label('Departemen')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('Company')
                            ->label('Perusahaan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Toggle::make('Status')
                            ->label('Status Aktif')
                            ->onColor('success')
                            ->offColor('danger')
                            ->default(true),
                    ])
                    ->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('NRP')
                    ->label('NRP')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('Position')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('Departement')
                    ->label('Departemen')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('Company')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('Status')
                    ->label('Aktif'),
            ])
            ->defaultSort('name')
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
