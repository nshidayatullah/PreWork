<?php

namespace App\Filament\Resources\PeriodReportResource\Pages;

use App\Filament\Resources\PeriodReportResource;
use App\Exports\PeriodReportExport;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ListPeriodReports extends ListRecords
{
    protected static string $resource = PeriodReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('Export Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    // Ambil filter yang sedang aktif
                    $filters = $this->getTableFiltersForm()->getState();
                    $startDate = $filters['periode']['dari_tanggal'] ?? '2025-08-01';
                    $endDate = $filters['periode']['sampai_tanggal'] ?? '2025-08-31';

                    // Generate filename dengan periode
                    $filename = 'Laporan_Periode_Pemeriksaan_' .
                               str_replace('-', '', $startDate) . '_' .
                               str_replace('-', '', $endDate) . '.xlsx';

                    // Download Excel
                    return Excel::download(
                        new PeriodReportExport($startDate, $endDate),
                        $filename
                    );
                })
                ->requiresConfirmation()
                ->modalHeading('Export Laporan ke Excel')
                ->modalDescription('Apakah Anda yakin ingin mengexport laporan periode pemeriksaan ke file Excel?')
                ->modalSubmitActionLabel('Ya, Export'),

                Actions\Action::make('refresh')
                    ->label('Refresh Data')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->action(function () {
                        $this->js('location.reload()');
                    })
,
        ];
    }

    public function getTitle(): string
    {
        return 'Laporan Periode Pemeriksaan';
    }

    public function getBreadcrumbs(): array
    {
        return [
            url('/admin') => 'Dashboard',
            url('/admin/period-reports') => 'Laporan Periode Pemeriksaan',
        ];
    }

    /**
     * Override untuk handle filter periode dengan benar
     */
    public function updatedTableFilters(): void
    {
        // Ketika filter berubah, reset pagination ke halaman 1
        $this->resetPage();
    }

    /**
     * Mount method - setup awal halaman
     */
    public function mount(): void
    {
        parent::mount();
    }

    /**
     * Custom method untuk mendapatkan summary data
     */
    public function getSummaryData(): array
    {
        $filters = $this->getTableFiltersForm()->getState();
        $startDate = $filters['periode']['dari_tanggal'] ?? '2025-08-01';
        $endDate = $filters['periode']['sampai_tanggal'] ?? '2025-08-31';

        $data = DB::table('rosters')
            ->selectRaw("
                SUM(COUNT(DISTINCT rosters.employee_id)) as total_karyawan,
                SUM(COUNT(DISTINCT bp.employee_id)) as total_sudah_periksa,
                SUM(COUNT(DISTINCT rosters.employee_id) - COUNT(DISTINCT bp.employee_id)) as total_belum_periksa
            ")
            ->leftJoin('blood_pressures as bp', function($join) {
                $join->on('rosters.employee_id', '=', 'bp.employee_id')
                     ->on('rosters.date', '=', 'bp.date');
            })
            ->whereIn('rosters.shift', ['shift 1', 'shift 2'])
            ->whereBetween('rosters.date', [$startDate, $endDate])
            ->groupBy('rosters.date')
            ->first();

        return [
            'total_karyawan' => $data->total_karyawan ?? 0,
            'total_sudah_periksa' => $data->total_sudah_periksa ?? 0,
            'total_belum_periksa' => $data->total_belum_periksa ?? 0,
            'persentase_total' => $data->total_karyawan > 0
                ? round(($data->total_sudah_periksa / $data->total_karyawan) * 100, 1)
                : 0
        ];
    }

    /**
     * Override getTableRecordsPerPage untuk pagination
     */
    public function getTableRecordsPerPage(): ?int
    {
        return 25;
    }
}
