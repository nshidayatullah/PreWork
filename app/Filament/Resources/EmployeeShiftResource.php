<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeShiftResource\Pages;
use App\Models\Employee;
use App\Models\Roster;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class EmployeeShiftResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-s-users';
    protected static ?string $navigationLabel = 'Karyawan per Shift';
    protected static ?string $modelLabel = 'Karyawan Shift';
    protected static ?string $navigationGroup = 'Karyawan';
    protected static ?int $navigationSort = 3;
    protected static ?string $pluralModelLabel = 'Data Karyawan per Shift';

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Employee::query()
                    ->with(['rosters' => function ($query) {
                        $query->whereDate('date', today());
                    }])
                    ->where('Status', true)
            )
            ->columns([
                Tables\Columns\TextColumn::make('NRP')
                    ->label('NRP')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('Name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('Position')
                    ->label('Jabatan')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('Departement')
                    ->label('Departemen')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('current_shift')
                    ->label('Shift Hari Ini')
                    ->getStateUsing(function ($record) {
                        $roster = $record->rosters->first();
                        return $roster ? $roster->shift : 'Tidak ada shift';
                    })
                    ->colors([
                        'primary' => 'shift 1',
                        'success' => 'shift 2',
                        'warning' => 'off',
                        'danger' => 'cuti',
                        'info' => 'dirumahkan',
                        'purple' => 'training',
                        'orange' => 'tugas luar',
                        'gray' => 'Tidak ada shift',
                    ]),

                Tables\Columns\TextColumn::make('Company')
                    ->label('Perusahaan')
                    ->toggleable(),
            ])
            // ->headerActions([
            //     Tables\Actions\Action::make('select_date')
            //         ->label('Pilih Tanggal')
            //         ->icon('heroicon-o-calendar')
            //         ->form([
            //             DatePicker::make('date')
            //                 ->label('Tanggal')
            //                 ->default(today())
            //                 ->required(),
            //         ])
            //         ->action(function (array $data) {
            //             session(['selected_shift_date' => $data['date']]);
            //             redirect()->to(request()->url());
            //         }),
            // ])
            ->filters([
                Filter::make('shift_1')
                    ->label('S1')
                    ->query(function (Builder $query) {
                        $date = session('selected_shift_date', today());
                        return $query->whereHas('rosters', function ($q) use ($date) {
                            $q->whereDate('date', $date)->where('shift', 'shift 1');
                        });
                    }),

                Filter::make('shift_2')
                    ->label('S2')
                    ->query(function (Builder $query) {
                        $date = session('selected_shift_date', today());
                        return $query->whereHas('rosters', function ($q) use ($date) {
                            $q->whereDate('date', $date)->where('shift', 'shift 2');
                        });
                    }),

                Filter::make('off')
                    ->label('Off')
                    ->query(function (Builder $query) {
                        $date = session('selected_shift_date', today());
                        return $query->whereHas('rosters', function ($q) use ($date) {
                            $q->whereDate('date', $date)->where('shift', 'off');
                        });
                    }),

                Filter::make('cuti')
                    ->label('C')
                    ->query(function (Builder $query) {
                        $date = session('selected_shift_date', today());
                        return $query->whereHas('rosters', function ($q) use ($date) {
                            $q->whereDate('date', $date)->where('shift', 'cuti');
                        });
                    }),

                Filter::make('dirumahkan')
                    ->label('H')
                    ->query(function (Builder $query) {
                        $date = session('selected_shift_date', today());
                        return $query->whereHas('rosters', function ($q) use ($date) {
                            $q->whereDate('date', $date)->where('shift', 'dirumahkan');
                        });
                    }),

                Filter::make('training')
                    ->label('TR')
                    ->query(function (Builder $query) {
                        $date = session('selected_shift_date', today());
                        return $query->whereHas('rosters', function ($q) use ($date) {
                            $q->whereDate('date', $date)->where('shift', 'training');
                        });
                    }),

                Filter::make('tugas_luar')
                    ->label('TL')
                    ->query(function (Builder $query) {
                        $date = session('selected_shift_date', today());
                        return $query->whereHas('rosters', function ($q) use ($date) {
                            $q->whereDate('date', $date)->where('shift', 'tugas luar');
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('Name');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Riwayat Shift & Tekanan Darah - Bulan Ini')
                    ->schema([
                        TextEntry::make('history_table')
                            ->label('')
                            ->getStateUsing(function ($record) {
                                // Ambil data roster bulan ini dengan sorting ascending
                                $rosters = \App\Models\Roster::where('employee_id', $record->id)
                                    ->whereMonth('date', now()->month)
                                    ->whereYear('date', now()->year)
                                    ->orderBy('date', 'asc') // Ubah ke ascending
                                    ->get();

                                // Ambil data tekanan darah bulan ini
                                $bloodPressures = \App\Models\BloodPressure::where('employee_id', $record->id)
                                    ->whereMonth('date', now()->month)
                                    ->whereYear('date', now()->year)
                                    ->get()
                                    ->keyBy(function ($item) {
                                        return $item->date->format('Y-m-d');
                                    });

                                if ($rosters->isEmpty()) {
                                    return 'Belum ada data roster untuk bulan ini.';
                                }

                                return view('filament.components.blood-pressure-history', [
                                    'rosters' => $rosters,
                                    'bloodPressures' => $bloodPressures,
                                    'employee' => $record
                                ])->render();
                            })
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployeeShifts::route('/'),
            'view' => Pages\ViewEmployeeShift::route('/{record}'),
        ];
    }
}
