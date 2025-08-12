<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Roster;
use App\Models\Employee;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\RosterResource\Pages;
use App\Filament\Resources\RosterResource\Widgets\RosterOverview;

class RosterResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-s-calendar-date-range';
    protected static ?string $navigationLabel = 'Roster Karyawan';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Karyawan';

    public static function table(Table $table): Table
    {
        // Ambil semua tanggal unik yang ada di tabel roster
        $dates = Roster::query()
            ->selectRaw('DATE(date) as date_only')
            ->distinct()
            ->orderBy('date_only')
            ->pluck('date_only')
            ->map(fn ($d) => Carbon::parse($d));

        return $table
            ->query(
                Employee::query()->with('rosters')
            )
            ->columns([
                Tables\Columns\TextColumn::make('NRP')
                    ->label('NRP')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('Name')
                    ->label('Nama Karyawan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('Position')
                    ->label('Jabatan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('Departement')
                    ->label('Departemen')
                    ->sortable()
                    ->toggleable(),

                // Loop header tanggal dengan SelectColumn untuk edit langsung
                ...$dates->map(function ($date) {
                    return SelectColumn::make('shift_' . $date->format('Y_m_d'))
                        ->label($date->format('d'))
                        ->options([
                            'shift 1' => 'S1',
                            'shift 2' => 'S2',
                            'off' => 'Off',
                            'cuti' => 'C',
                            'dirumahkan' => 'H',
                            'training' => 'TR',
                            'tugas luar' => 'TL',
                        ])
                        ->getStateUsing(function ($record) use ($date) {
                            $roster = $record->rosters->firstWhere(
                                fn ($roster) => $roster->date->isSameDay($date)
                            );
                            return $roster ? $roster->shift : null;
                        })
                        ->updateStateUsing(function ($record, $state) use ($date) {
                            $roster = Roster::updateOrCreate(
                                [
                                    'employee_id' => $record->id,
                                    'date' => $date->format('Y-m-d'),
                                ],
                                [
                                    'shift' => $state,
                                ]
                            );

                            $record->load('rosters');

                            Notification::make()
                                ->title('Roster Updated!')
                                ->body("Shift {$record->Name} pada {$date->format('d M Y')} berhasil diubah ke: {$state}")
                                ->success()
                                ->duration(3000)
                                ->send();

                            return $state;
                        })
                        ->placeholder('Pilih shift...')
                        ->selectablePlaceholder(false)
                        ->extraAttributes([
                            'style' => 'min-width: 120px;'
                        ]);
                })->toArray(),
            ])
            ->headerActions([
                // Action untuk membuat periode baru
                Tables\Actions\Action::make('create_period')
                    ->label('Buat Periode Baru')
                    ->icon('heroicon-o-calendar-days')
                    ->color('success')
                    ->form([
                        Forms\Components\Grid::make(2)->schema([
                            DatePicker::make('start_date')
                                ->label('Tanggal Mulai')
                                ->required()
                                ->default(now()->startOfMonth()),

                            DatePicker::make('end_date')
                                ->label('Tanggal Selesai')
                                ->required()
                                ->default(now()->endOfMonth()),
                        ]),

                        Select::make('default_shift')
                            ->label('Shift Default')
                            ->options([
                                'shift 1' => 'Shift 1',
                                'shift 2' => 'Shift 2',
                                'off' => 'Off',
                            ])
                            ->default('shift 1')
                            ->required(),

                        Checkbox::make('only_active_employees')
                            ->label('Hanya Karyawan Aktif')
                            ->default(true),
                    ])
                    ->action(function (array $data) {
                        return self::createNewPeriod($data);
                    })
                    ->slideOver(),

                // Action untuk generate roster karyawan baru
                Tables\Actions\Action::make('generate_for_new_employees')
                    ->label('Generate Roster Karyawan Baru')
                    ->icon('heroicon-o-user-plus')
                    ->color('warning')
                    ->form([
                        Forms\Components\CheckboxList::make('employee_ids')
                            ->label('Pilih Karyawan Baru')
                            ->options(function () {
                                // Ambil karyawan yang belum memiliki roster
                                $employeesWithRoster = Roster::distinct()->pluck('employee_id');
                                return Employee::whereNotIn('id', $employeesWithRoster)
                                    ->where('Status', true)
                                    ->pluck('Name', 'id');
                            })
                            ->required(),

                        Forms\Components\Grid::make(2)->schema([
                            DatePicker::make('start_date')
                                ->label('Tanggal Mulai')
                                ->required()
                                ->default(now()->startOfMonth()),

                            DatePicker::make('end_date')
                                ->label('Tanggal Selesai')
                                ->required()
                                ->default(now()->endOfMonth()),
                        ]),

                        Select::make('default_shift')
                            ->label('Shift Default')
                            ->options([
                                'shift 1' => 'Shift 1',
                                'shift 2' => 'Shift 2',
                                'off' => 'Off',
                            ])
                            ->default('shift 1')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        return self::generateForNewEmployees($data);
                    })
                    ->slideOver(),
            ])
            ->defaultSort('Name', 'asc')
            ->striped();
    }

    /**
     * Membuat periode baru untuk semua karyawan
     */
    protected static function createNewPeriod(array $data): void
    {
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $defaultShift = $data['default_shift'];

        // Ambil karyawan berdasarkan filter
        $query = Employee::query();
        if ($data['only_active_employees']) {
            $query->where('Status', true);
        }
        $employees = $query->get();

        $processedCount = 0;
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            foreach ($employees as $employee) {
                // Cek apakah roster sudah ada
                $existingRoster = Roster::where('employee_id', $employee->id)
                    ->whereDate('date', $currentDate)
                    ->first();

                if (!$existingRoster) {
                    Roster::create([
                        'employee_id' => $employee->id,
                        'date' => $currentDate->format('Y-m-d'),
                        'shift' => $defaultShift,
                    ]);
                    $processedCount++;
                }
            }

            $currentDate->addDay();
        }

        Notification::make()
            ->title('Periode Baru Berhasil Dibuat!')
            ->body("Berhasil membuat {$processedCount} data roster untuk periode {$startDate->format('d M Y')} - {$endDate->format('d M Y')}")
            ->success()
            ->send();
    }

    /**
     * Generate roster untuk karyawan baru
     */
    protected static function generateForNewEmployees(array $data): void
    {
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $defaultShift = $data['default_shift'];
        $employees = Employee::whereIn('id', $data['employee_ids'])->get();

        $processedCount = 0;

        foreach ($employees as $employee) {
            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {
                // Cek apakah roster sudah ada
                $existingRoster = Roster::where('employee_id', $employee->id)
                    ->whereDate('date', $currentDate)
                    ->first();

                if (!$existingRoster) {
                    Roster::create([
                        'employee_id' => $employee->id,
                        'date' => $currentDate->format('Y-m-d'),
                        'shift' => $defaultShift,
                    ]);
                    $processedCount++;
                }

                $currentDate->addDay();
            }
        }

        Notification::make()
            ->title('Roster Karyawan Baru Berhasil Dibuat!')
            ->body("Berhasil membuat {$processedCount} data roster untuk {$employees->count()} karyawan baru")
            ->success()
            ->send();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRosters::route('/'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            RosterOverview::class,
        ];
    }

}
