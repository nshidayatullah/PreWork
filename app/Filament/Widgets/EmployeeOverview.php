<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Hitung statistik karyawan
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('Status', true)->count();
        $inactiveEmployees = Employee::where('Status', false)->count();

        return [
            Stat::make('Total Karyawan', $totalEmployees)
                ->description('Seluruh Wajib Pemeriksaan Awal Shift')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Total', $activeEmployees)
                ->description('Karyawan Dalam Status Ovservasi -Open')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Total', $inactiveEmployees)
                ->description('Karyawan Dalam Status Ovservasi -Close')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
