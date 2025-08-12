<?php

namespace App\Filament\Resources\EmployeeShiftResource\Pages;

use App\Filament\Resources\EmployeeShiftResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListEmployeeShifts extends ListRecords
{
    protected static string $resource = EmployeeShiftResource::class;

        protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refresh')
                ->label('Refresh Data')
                ->icon('heroicon-o-arrow-path')
                ->url(fn () => static::getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }

    public function getTabs(): array
    {
        $date = session('selected_shift_date', today());

        return [
            'all' => Tab::make('Semua')
                ->badge(function () use ($date) {
                    return \App\Models\Employee::where('Status', true)
                        ->whereHas('rosters', function ($q) use ($date) {
                            $q->whereDate('date', $date);
                        })
                        ->count();
                }),

            'shift_1' => Tab::make('S1')
                ->modifyQueryUsing(function (Builder $query) use ($date) {
                    return $query->whereHas('rosters', function ($q) use ($date) {
                        $q->whereDate('date', $date)->where('shift', 'shift 1');
                    });
                })
                ->badge(function () use ($date) {
                    return \App\Models\Employee::where('Status', true)
                        ->whereHas('rosters', function ($q) use ($date) {
                            $q->whereDate('date', $date)->where('shift', 'shift 1');
                        })
                        ->count();
                }),

            'shift_2' => Tab::make('S2')
                ->modifyQueryUsing(function (Builder $query) use ($date) {
                    return $query->whereHas('rosters', function ($q) use ($date) {
                        $q->whereDate('date', $date)->where('shift', 'shift 2');
                    });
                })
                ->badge(function () use ($date) {
                    return \App\Models\Employee::where('Status', true)
                        ->whereHas('rosters', function ($q) use ($date) {
                            $q->whereDate('date', $date)->where('shift', 'shift 2');
                        })
                        ->count();
                }),

            'off' => Tab::make('Off')
                ->modifyQueryUsing(function (Builder $query) use ($date) {
                    return $query->whereHas('rosters', function ($q) use ($date) {
                        $q->whereDate('date', $date)->where('shift', 'off');
                    });
                })
                ->badge(function () use ($date) {
                    return \App\Models\Employee::where('Status', true)
                        ->whereHas('rosters', function ($q) use ($date) {
                            $q->whereDate('date', $date)->where('shift', 'off');
                        })
                        ->count();
                }),

            'cuti' => Tab::make('C')
                ->modifyQueryUsing(function (Builder $query) use ($date) {
                    return $query->whereHas('rosters', function ($q) use ($date) {
                        $q->whereDate('date', $date)->where('shift', 'cuti');
                    });
                })
                ->badge(function () use ($date) {
                    return \App\Models\Employee::where('Status', true)
                        ->whereHas('rosters', function ($q) use ($date) {
                            $q->whereDate('date', $date)->where('shift', 'cuti');
                        })
                        ->count();
                }),

            'dirumahkan' => Tab::make('H')
                ->modifyQueryUsing(function (Builder $query) use ($date) {
                    return $query->whereHas('rosters', function ($q) use ($date) {
                        $q->whereDate('date', $date)->where('shift', 'dirumahkan');
                    });
                })
                ->badge(function () use ($date) {
                    return \App\Models\Employee::where('Status', true)
                        ->whereHas('rosters', function ($q) use ($date) {
                            $q->whereDate('date', $date)->where('shift', 'dirumahkan');
                        })
                        ->count();
                }),

            'training' => Tab::make('TR')
                ->modifyQueryUsing(function (Builder $query) use ($date) {
                    return $query->whereHas('rosters', function ($q) use ($date) {
                        $q->whereDate('date', $date)->where('shift', 'training');
                    });
                })
                ->badge(function () use ($date) {
                    return \App\Models\Employee::where('Status', true)
                        ->whereHas('rosters', function ($q) use ($date) {
                            $q->whereDate('date', $date)->where('shift', 'training');
                        })
                        ->count();
                }),

            'tugas_luar' => Tab::make('TL')
                ->modifyQueryUsing(function (Builder $query) use ($date) {
                    return $query->whereHas('rosters', function ($q) use ($date) {
                        $q->whereDate('date', $date)->where('shift', 'tugas luar');
                    });
                })
                ->badge(function () use ($date) {
                    return \App\Models\Employee::where('Status', true)
                        ->whereHas('rosters', function ($q) use ($date) {
                            $q->whereDate('date', $date)->where('shift', 'tugas luar');
                        })
                        ->count();
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Bisa tambahkan widget untuk statistik shift
        ];
    }

    public function getTitle(): string
    {
        $selectedDate = session('selected_shift_date', today());
        return 'Karyawan per Shift - ' . \Carbon\Carbon::parse($selectedDate)->format('d M Y');
    }

    public function getSubheading(): ?string
    {
        $selectedDate = session('selected_shift_date', today());
        $totalEmployees = \App\Models\Employee::where('Status', true)
            ->whereHas('rosters', function ($q) use ($selectedDate) {
                $q->whereDate('date', $selectedDate);
            })
            ->count();

        return "Total {$totalEmployees} karyawan terjadwal pada tanggal ini";
    }
}

