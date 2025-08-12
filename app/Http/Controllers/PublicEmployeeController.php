<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PublicEmployeeController extends Controller
{
    public function unexaminedEmployees()
    {
        $today = Carbon::today()->format('Y-m-d');

        // Query untuk mendapatkan karyawan yang belum periksa hari ini
        // Berdasarkan struktur tabel employees yang sebenarnya
        $employees = DB::table('rosters as r')
            ->select([
                'e.NRP as nrp',
                'e.Name as nama_karyawan',
                'e.Position as jabatan',
                'e.Departement as departemen', // Note: ada typo di schema (Departement, bukan Department)
                'e.Company as perusahaan',
                'r.shift'
            ])
            ->join('employees as e', 'r.employee_id', '=', 'e.id')
            ->leftJoin('blood_pressures as bp', function($join) use ($today) {
                $join->on('r.employee_id', '=', 'bp.employee_id')
                     ->where('bp.date', '=', $today);
            })
            ->where('r.date', $today)
            ->whereIn('r.shift', ['shift 1', 'shift 2'])
            ->where('e.Status', 'open') // Hanya karyawan dengan status open
            ->whereNull('bp.employee_id') // Yang belum ada pemeriksaan
            ->orderBy('e.Departement')
            ->orderBy('e.Name')
            ->get();

        // Statistik
        $totalScheduled = DB::table('rosters as r')
            ->join('employees as e', 'r.employee_id', '=', 'e.id')
            ->where('r.date', $today)
            ->whereIn('r.shift', ['shift 1', 'shift 2'])
            ->where('e.Status', 'open')
            ->distinct('r.employee_id')
            ->count();

        $totalExamined = DB::table('rosters as r')
            ->join('employees as e', 'r.employee_id', '=', 'e.id')
            ->join('blood_pressures as bp', function($join) use ($today) {
                $join->on('r.employee_id', '=', 'bp.employee_id')
                     ->where('bp.date', '=', $today);
            })
            ->where('r.date', $today)
            ->whereIn('r.shift', ['shift 1', 'shift 2'])
            ->where('e.Status', 'open')
            ->distinct('r.employee_id')
            ->count();

        $totalUnexamined = $employees->count();
        $percentage = $totalScheduled > 0 ? round(($totalExamined / $totalScheduled) * 100, 1) : 0;

        return view('public.unexamined-employees', compact(
            'employees',
            'totalScheduled',
            'totalExamined',
            'totalUnexamined',
            'percentage',
            'today'
        ));
    }

    public function unexaminedEmployeesApi()
    {
        $today = Carbon::today()->format('Y-m-d');

        $employees = DB::table('rosters as r')
            ->select([
                'e.NRP as nrp',
                'e.Name as nama_karyawan',
                'e.Position as jabatan',
                'e.Departement as departemen',
                'e.Company as perusahaan',
                'r.shift'
            ])
            ->join('employees as e', 'r.employee_id', '=', 'e.id')
            ->leftJoin('blood_pressures as bp', function($join) use ($today) {
                $join->on('r.employee_id', '=', 'bp.employee_id')
                     ->where('bp.date', '=', $today);
            })
            ->where('r.date', $today)
            ->whereIn('r.shift', ['shift 1', 'shift 2'])
            ->where('e.Status', 'open')
            ->whereNull('bp.employee_id')
            ->orderBy('e.Departement')
            ->orderBy('e.Name')
            ->get();

        $stats = [
            'total_scheduled' => DB::table('rosters as r')
                ->join('employees as e', 'r.employee_id', '=', 'e.id')
                ->where('r.date', $today)
                ->whereIn('r.shift', ['shift 1', 'shift 2'])
                ->where('e.Status', 'open')
                ->distinct('r.employee_id')
                ->count(),
            'total_examined' => DB::table('rosters as r')
                ->join('employees as e', 'r.employee_id', '=', 'e.id')
                ->join('blood_pressures as bp', function($join) use ($today) {
                    $join->on('r.employee_id', '=', 'bp.employee_id')
                         ->where('bp.date', '=', $today);
                })
                ->where('r.date', $today)
                ->whereIn('r.shift', ['shift 1', 'shift 2'])
                ->where('e.Status', 'open')
                ->distinct('r.employee_id')
                ->count(),
            'total_unexamined' => $employees->count(),
            'date' => $today
        ];

        $stats['percentage'] = $stats['total_scheduled'] > 0 
            ? round(($stats['total_examined'] / $stats['total_scheduled']) * 100, 1) 
            : 0;

        return response()->json([
            'success' => true,
            'data' => $employees,
            'stats' => $stats
        ]);
    }
}