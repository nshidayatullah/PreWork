<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\BloodPressure;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Roster;
use App\Models\Employee;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\BloodPressureResource\Pages;
use App\Filament\Resources\BloodPressureResource\Widgets\BloodPresureOverview;

class BloodPressureResource extends Resource
{
    protected static ?string $model = BloodPressure::class;
    protected static ?string $navigationIcon = 'heroicon-s-heart';
    protected static ?string $navigationLabel = 'Tekanan Darah';
    protected static ?string $modelLabel = 'Tekanan Darah';
    protected static ?string $navigationGroup = 'Pemeriksaan';
    protected static ?string $pluralModelLabel = 'Data Tekanan Darah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Tekanan Darah')
                    ->schema([
                        // Grid: Karyawan & Tanggal
                        Forms\Components\Grid::make([
                            'default' => 1,
                            'sm' => 2,
                        ])->schema([
                            Forms\Components\Select::make('employee_id')
                                ->label('Karyawan')
                                ->relationship(
                                    name: 'employee',
                                    titleAttribute: 'Name',
                                    modifyQueryUsing: function ($query) {
                                        $today = Carbon::today()->format('Y-m-d');
                                        $employeeIds = Roster::whereDate('date', $today)
                                            ->whereIn('shift', ['shift 1', 'shift 2'])
                                            ->pluck('employee_id');

                                        return $query->whereIn('id', $employeeIds)
                                            ->where('Status', true);
                                    }
                                )
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state) {
                                    if ($state) {
                                        $existingRecord = BloodPressure::where('employee_id', $state)
                                            ->whereDate('date', today())
                                            ->first();

                                        if ($existingRecord) {
                                            \Filament\Notifications\Notification::make()
                                                ->title('Peringatan!')
                                                ->body("Karyawan sudah memiliki data hari ini: {$existingRecord->sistole}/{$existingRecord->diastole} mmHg")
                                                ->warning()
                                                ->duration(5000)
                                                ->send();
                                        }
                                    }
                                }),

                            Forms\Components\DatePicker::make('date')
                                ->label('Tanggal')
                                ->default(today())
                                ->required()
                                ->maxDate(today()),
                        ]),

                        // Grid: Sistole, Diastole, Keterangan
                        Forms\Components\Grid::make([
                            'default' => 1,
                            'sm' => 4,
                        ])->schema([
                            Forms\Components\TextInput::make('sistole')
                                ->label('Sistole')
                                ->numeric()
                                ->required()
                                ->minValue(70)
                                ->maxValue(250)
                                ->placeholder('120')
                                ->extraAttributes(['style' => 'max-width: 100px;']),

                            Forms\Components\TextInput::make('diastole')
                                ->label('Diastole')
                                ->numeric()
                                ->required()
                                ->minValue(40)
                                ->maxValue(150)
                                ->placeholder('80')
                                ->extraAttributes(['style' => 'max-width: 100px;']),

                            Forms\Components\Radio::make('status')
                                ->label('Keterangan')
                                ->options([
                                    'Fit To Work' => 'Fit To Work',
                                    'Fit With Medical Therapy' => 'Fit With Medical Therapy',
                                    'Unfit' => 'Unfit',
                                    'Observasi' => 'Observasi',
                                ])
                                ->required()
                                ->columns(2)
                                ->columnSpan(2),
                        ]),

                        // Obat/Terapi
                        Forms\Components\TagsInput::make('medications')
                            ->label('Terapi/Obat')
                            ->placeholder('Ketik nama obat dan tekan Enter')
                            ->helperText('Masukkan nama obat, tekan Enter untuk menambah'),

                        // Catatan
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Tambahan')
                            ->rows(3)
                            ->placeholder('Catatan medis tambahan (opsional)'),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('employee.Name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('employee.NRP')
                    ->label('NRP')
                    ->searchable(),

                Tables\Columns\TextColumn::make('blood_pressure')
                    ->label('Tekanan Darah')
                    ->getStateUsing(fn ($record) => $record->sistole . '/' . $record->diastole . ' mmHg')
                    ->badge()
                    ->color(fn ($record) =>
                        $record->sistole >= 140 || $record->diastole >= 90 ? 'danger' :
                        ($record->sistole >= 130 || $record->diastole >= 80 ? 'warning' : 'success')
                    ),

                Tables\Columns\TagsColumn::make('medications')
                    ->label('Obat/Terapi')
                    ->placeholder('Tidak ada obat'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'Fit To Work',
                        'warning' => 'Fit With Medical Therapy',
                        'danger' => 'Unfit',
                        'info' => 'Observasi',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dicatat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'Fit To Work' => 'Fit To Work',
                        'Fit With Medical Therapy' => 'Fit With Medical Therapy',
                        'Unfit' => 'Unfit',
                        'Observasi' => 'Observasi',
                    ]),

                Tables\Filters\Filter::make('today')
                    ->label('Hari Ini')
                    ->query(fn ($query) => $query->whereDate('date', today())),

                Tables\Filters\Filter::make('high_blood_pressure')
                    ->label('Tekanan Darah Tinggi')
                    ->query(fn ($query) => $query->where('sistole', '>=', 140)->orWhere('diastole', '>=', 90)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListBloodPressures::route('/'),
            'create' => Pages\CreateBloodPressure::route('/create'),
            'view' => Pages\ViewBloodPressure::route('/{record}'),
            'edit' => Pages\EditBloodPressure::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            BloodPresureOverview::class,
        ];
    }
}
