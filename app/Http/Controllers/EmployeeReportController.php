<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Roster;
use App\Models\BloodPressure;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class EmployeeReportController extends Controller
{
    public function downloadPDF($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        // Ambil data roster bulan ini dengan sorting ascending
        $rosters = Roster::where('employee_id', $employee->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->orderBy('date', 'asc')
            ->get();

        // Ambil data tekanan darah bulan ini
        $bloodPressures = BloodPressure::where('employee_id', $employee->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->get()
            ->keyBy(function ($item) {
                return $item->date->format('Y-m-d');
            });

        // Hitung statistik
        $totalHariKerja = $rosters->whereIn('shift', ['shift 1', 'shift 2'])->count();
        $totalOff = $rosters->where('shift', 'off')->count();
        $totalCuti = $rosters->where('shift', 'cuti')->count();
        $totalDiperiksa = $bloodPressures->count();

        $data = [
            'employee' => $employee,
            'rosters' => $rosters,
            'bloodPressures' => $bloodPressures,
            'period' => Carbon::now()->locale('id')->isoFormat('MMMM YYYY'),
            'generatedAt' => Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY HH:mm'),
            'stats' => [
                'totalHariKerja' => $totalHariKerja,
                'totalOff' => $totalOff,
                'totalCuti' => $totalCuti,
                'totalDiperiksa' => $totalDiperiksa,
            ]
        ];

        // Load view dengan data
        $pdf = Pdf::loadView('pdf.employee-report', $data);

        // Set paper size dan orientation
        $pdf->setPaper('A4', 'portrait');

        // Set options untuk DOMPDF
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        // Generate filename yang aman
        $filename = 'Laporan_' . str_replace(' ', '_', $employee->Name) . '_' . now()->format('Y-m') . '.pdf';

        // Download PDF
        return $pdf->download($filename);
    }

    public function streamPDF($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        // Ambil data roster bulan ini dengan sorting ascending
        $rosters = Roster::where('employee_id', $employee->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->orderBy('date', 'asc')
            ->get();

        // Ambil data tekanan darah bulan ini
        $bloodPressures = BloodPressure::where('employee_id', $employee->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->get()
            ->keyBy(function ($item) {
                return $item->date->format('Y-m-d');
            });

        // Hitung statistik
        $totalHariKerja = $rosters->whereIn('shift', ['shift 1', 'shift 2'])->count();
        $totalOff = $rosters->where('shift', 'off')->count();
        $totalCuti = $rosters->where('shift', 'cuti')->count();
        $totalDiperiksa = $bloodPressures->count();

        $data = [
            'employee' => $employee,
            'rosters' => $rosters,
            'bloodPressures' => $bloodPressures,
            'period' => Carbon::now()->locale('id')->isoFormat('MMMM YYYY'),
            'generatedAt' => Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY HH:mm'),
            'stats' => [
                'totalHariKerja' => $totalHariKerja,
                'totalOff' => $totalOff,
                'totalCuti' => $totalCuti,
                'totalDiperiksa' => $totalDiperiksa,
            ]
        ];

        // Load view dengan data
        $pdf = Pdf::loadView('pdf.employee-report', $data);

        // Set paper size dan orientation
        $pdf->setPaper('A4', 'portrait');

        // Set options untuk DOMPDF
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        // Stream PDF (tampilkan di browser)
        return $pdf->stream('Laporan_' . str_replace(' ', '_', $employee->Name) . '_' . now()->format('Y-m') . '.pdf');
    }

    public function generateCustomPeriod(Request $request, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        // Validasi input tanggal
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Ambil data roster untuk periode custom
        $rosters = Roster::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get();

        // Ambil data tekanan darah untuk periode custom
        $bloodPressures = BloodPressure::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->keyBy(function ($item) {
                return $item->date->format('Y-m-d');
            });

        // Hitung statistik
        $totalHariKerja = $rosters->whereIn('shift', ['shift 1', 'shift 2'])->count();
        $totalOff = $rosters->where('shift', 'off')->count();
        $totalCuti = $rosters->where('shift', 'cuti')->count();
        $totalDiperiksa = $bloodPressures->count();

        $data = [
            'employee' => $employee,
            'rosters' => $rosters,
            'bloodPressures' => $bloodPressures,
            'period' => $startDate->locale('id')->isoFormat('DD MMMM YYYY') . ' - ' . $endDate->locale('id')->isoFormat('DD MMMM YYYY'),
            'generatedAt' => Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY HH:mm'),
            'stats' => [
                'totalHariKerja' => $totalHariKerja,
                'totalOff' => $totalOff,
                'totalCuti' => $totalCuti,
                'totalDiperiksa' => $totalDiperiksa,
            ]
        ];

        // Load view dengan data
        $pdf = Pdf::loadView('pdf.employee-report', $data);

        // Set paper size dan orientation
        $pdf->setPaper('A4', 'portrait');

        // Set options untuk DOMPDF
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        // Generate filename
        $filename = 'Laporan_' . str_replace(' ', '_', $employee->Name) . '_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.pdf';

        // Download PDF
        return $pdf->download($filename);
    }
}
