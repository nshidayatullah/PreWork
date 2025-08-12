<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeriodReportDailyChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Total Karyawan Diperiksa Per Hari';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Ambil filter dari page (jika ada) atau gunakan default
        $startDate = $this->filters['startDate'] ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $this->filters['endDate'] ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        // Ambil data karyawan yang diperiksa per hari dalam periode
        $data = DB::table('rosters')
            ->selectRaw("
                rosters.date as tanggal,
                COUNT(DISTINCT bp.employee_id) as sudah_periksa
            ")
            ->leftJoin('blood_pressures as bp', function($join) {
                $join->on('rosters.employee_id', '=', 'bp.employee_id')
                     ->on('rosters.date', '=', 'bp.date');
            })
            ->whereIn('rosters.shift', ['shift 1', 'shift 2'])
            ->whereBetween('rosters.date', [$startDate, $endDate])
            ->groupBy('rosters.date')
            ->orderBy('rosters.date', 'asc')
            ->get();

        // Prepare data untuk chart
        $labels = [];
        $values = [];

        foreach ($data as $item) {
            $labels[] = Carbon::parse($item->tanggal)->format('d/m');
            $values[] = $item->sudah_periksa;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Karyawan Diperiksa',
                    'data' => $values,
                    'borderColor' => 'rgb(59, 130, 246)', // Blue
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        $startDate = $this->filters['startDate'] ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $this->filters['endDate'] ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Periode: ' . Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y'),
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Karyawan',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Tanggal',
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
