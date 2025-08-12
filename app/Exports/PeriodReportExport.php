<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeriodReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate ?: '2025-08-01';
        $this->endDate = $endDate ?: '2025-08-31';
    }

    /**
     * Get data collection
     */
    public function collection()
    {
        return DB::table('rosters')
            ->selectRaw("
                REPLACE(rosters.date, '-', '') as id,
                rosters.date as tanggal,
                COUNT(DISTINCT rosters.employee_id) as total_karyawan_shift,
                COUNT(DISTINCT bp.employee_id) as sudah_periksa,
                (COUNT(DISTINCT rosters.employee_id) - COUNT(DISTINCT bp.employee_id)) as belum_periksa,
                ROUND(
                    CASE
                        WHEN COUNT(DISTINCT rosters.employee_id) > 0
                        THEN (COUNT(DISTINCT bp.employee_id) * 100.0 / COUNT(DISTINCT rosters.employee_id))
                        ELSE 0
                    END, 1
                ) as persentase_periksa
            ")
            ->leftJoin('blood_pressures as bp', function($join) {
                $join->on('rosters.employee_id', '=', 'bp.employee_id')
                     ->on('rosters.date', '=', 'bp.date');
            })
            ->whereIn('rosters.shift', ['shift 1', 'shift 2'])
            ->whereBetween('rosters.date', [$this->startDate, $this->endDate])
            ->groupBy('rosters.date')
            ->orderBy('rosters.date', 'asc')
            ->get();
    }

    /**
     * Set headings
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Total Karyawan Shift 1&2',
            'Sudah Periksa',
            'Belum Periksa',
            'Persentase (%)'
        ];
    }

    /**
     * Map data untuk setiap row
     */
    public function map($row): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            Carbon::parse($row->tanggal)->format('d/m/Y'),
            $row->total_karyawan_shift,
            $row->sudah_periksa,
            $row->belum_periksa,
            $row->persentase_periksa . '%'
        ];
    }

    /**
     * Set column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 15,  // Tanggal
            'C' => 20,  // Total Karyawan
            'D' => 15,  // Sudah Periksa
            'E' => 15,  // Belum Periksa
            'F' => 15,  // Persentase
        ];
    }

    /**
     * Set worksheet title
     */
    public function title(): string
    {
        return 'Laporan Periode Pemeriksaan';
    }

    /**
     * Apply styles
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Style untuk data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A2:F{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style khusus untuk kolom tanggal (align left)
        $sheet->getStyle("B2:B{$lastRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ]);

        // Auto height untuk semua rows
        for ($i = 1; $i <= $lastRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(-1);
        }

        return $sheet;
    }
}
