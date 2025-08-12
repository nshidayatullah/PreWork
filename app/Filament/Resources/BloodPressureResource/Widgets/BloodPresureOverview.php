<?php

namespace App\Filament\Resources\BloodPressureResource\Widgets;

use App\Models\Roster;
use App\Models\BloodPressure;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class BloodPresureOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today()->toDateString();

        // Query rosters hari ini
        $shift1 = Roster::whereDate('date', $today)
            ->where('shift', 'shift 1')
            ->pluck('employee_id');

        $shift2 = Roster::whereDate('date', $today)
            ->where('shift', 'shift 2')
            ->pluck('employee_id');

        $totalTerjadwal = $shift1->count() + $shift2->count();

        // Cek siapa saja yang sudah periksa
        $sudahPeriksaShift1 = BloodPressure::whereDate('date', $today)
            ->whereIn('employee_id', $shift1)
            ->pluck('employee_id');

        $sudahPeriksaShift2 = BloodPressure::whereDate('date', $today)
            ->whereIn('employee_id', $shift2)
            ->pluck('employee_id');

        // Hitung belum periksa
        $belumPeriksaShift1 = $shift1->diff($sudahPeriksaShift1)->count();
        $belumPeriksaShift2 = $shift2->diff($sudahPeriksaShift2)->count();

        // Hitung total sudah periksa dan belum periksa
        $totalSudahPeriksa = $sudahPeriksaShift1->count() + $sudahPeriksaShift2->count();
        $totalBelumPeriksa = $belumPeriksaShift1 + $belumPeriksaShift2;

        return [
            Stat::make('Total Karyawan Shif 1 + Shift 2', $totalTerjadwal),
            Stat::make('Shift 1', $shift1->count()),
            Stat::make('Shift 2', $shift2->count()),
            Stat::make('Belum Periksa Shift 1', $belumPeriksaShift1),
            Stat::make('Belum Periksa Shift 2', $belumPeriksaShift2),
            Stat::make('Total Sudah Periksa', $totalSudahPeriksa),
        ];
    }
}
