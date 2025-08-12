<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeriodReportMonthlyChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Total Karyawan Diperiksa Per Bulan';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Ambil filter dari page (jika ada) atau gunakan default 12 bulan terakhir
        $startDate = Carbon::parse($this->filters['startDate'] ?? Carbon::now()->subMonths(11)->startOfMonth());
        $endDate = Carbon::parse($this->filters['endDate'] ?? Carbon::now()->endOfMonth());

        $data = DB::table('rosters')
            ->selectRaw("
                DATE_FORMAT(rosters.date, '%Y-%m') as bulan,
                COUNT(DISTINCT bp.employee_id) as total_diperiksa,
                COUNT(DISTINCT rosters.employee_id) as total_karyawan
            ")
            ->leftJoin('blood_pressures as bp', function($join) {
                $join->on('rosters.employee_id', '=', 'bp.employee_id')
                     ->on('rosters.date', '=', 'bp.date');
            })
            ->whereIn('rosters.shift', ['shift 1', 'shift 2'])
            ->whereBetween('rosters.date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->groupByRaw('DATE_FORMAT(rosters.date, "%Y-%m")')
            ->orderByRaw('DATE_FORMAT(rosters.date, "%Y-%m") ASC')
            ->get();

        // Generate semua bulan dalam periode yang dipilih
        $allMonths = [];
        $current = $startDate->copy()->startOfMonth();
        while ($current <= $endDate) {
            $allMonths[$current->format('Y-m')] = [
                'label' => $current->format('M Y'),
                'diperiksa' => 0,
                'total' => 0,
            ];
            $current->addMonth();
        }

        // Fill data yang ada
        foreach ($data as $item) {
            if (isset($allMonths[$item->bulan])) {
                $allMonths[$item->bulan]['diperiksa'] = $item->total_diperiksa;
                $allMonths[$item->bulan]['total'] = $item->total_karyawan;
            }
        }

        // Prepare data untuk chart
        $labels = [];
        $diperiksa = [];
        $total = [];

        foreach ($allMonths as $month) {
            $labels[] = $month['label'];
            $diperiksa[] = $month['diperiksa'];
            $total[] = $month['total'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Karyawan Diperiksa',
                    'data' => $diperiksa,
                    'borderColor' => 'rgb(34, 197, 94)', // Green
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => false,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Total Karyawan Shift 1&2',
                    'data' => $total,
                    'borderColor' => 'rgb(156, 163, 175)', // Gray
                    'backgroundColor' => 'rgba(156, 163, 175, 0.1)',
                    'fill' => false,
                    'tension' => 0.4,
                    'borderDash' => [5, 5], // Dashed line
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
        $startDate = $this->filters['startDate'] ?? Carbon::now()->subMonths(11)->startOfMonth()->format('Y-m-d');
        $endDate = $this->filters['endDate'] ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Periode: ' . Carbon::parse($startDate)->format('M Y') . ' - ' . Carbon::parse($endDate)->format('M Y'),
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
                        'text' => 'Bulan',
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
        ];
    }
}
