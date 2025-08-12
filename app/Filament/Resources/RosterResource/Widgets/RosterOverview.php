<?php

namespace App\Filament\Resources\RosterResource\Widgets;

use App\Models\Roster;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RosterOverview extends ChartWidget
{
    protected static ?string $heading = 'Rekap Shift Hari Ini';

    protected function getData(): array
    {
        $today = Carbon::today()->toDateString();

        $shifts = [
            'shift 1'    => 'S1',
            'shift 2'    => 'S2',
            'off'        => 'Off',
            'cuti'       => 'C',
            'dirumahkan' => 'H',
            'training'   => 'TR',
            'tugas luar' => 'TL',
        ];

        $counts = [];

        foreach ($shifts as $shiftKey => $label) {
            $counts[] = Roster::whereDate('date', $today)
                ->where('shift', $shiftKey)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Karyawan',
                    'data' => $counts,
                    'backgroundColor' => [
                        '#4ade80', // hijau - S1
                        '#60a5fa', // biru - S2
                        '#d1d5db', // abu - Off
                        '#facc15', // kuning - Cuti
                        '#f87171', // merah - Dirumahkan
                        '#f97316', // oranye - Training
                        '#a78bfa', // ungu - Tugas Luar
                    ],
                ],
            ],
            'labels' => array_values($shifts),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
