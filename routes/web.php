<?php

use App\Models\Roster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicEmployeeController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/tes-pivot', function () {
    $month = '2025-08';

    $rosters = Roster::select(
            'employee_id',
            DB::raw('DAY(`date`) as day'),
            'shift'
        )
        ->whereRaw("DATE_FORMAT(`date`, '%Y-%m') = ?", [$month])
        ->with('employee')
        ->get()
        ->groupBy('employee_id')
        ->map(function ($rows) {
            $employee = $rows->first()->employee;
            $data = [
                'NRP'       => $employee->NRP,
                'Name'      => $employee->Name,
                'Position'  => $employee->Position,
                'Departement' => $employee->Departement,
                'Company'   => $employee->Company,
            ];

            for ($i = 1; $i <= 31; $i++) {
                $shift = $rows->firstWhere('day', $i)->shift ?? '';
                $data[$i] = $shift;
            }

            return $data;
        })
        ->values();

    dd($rosters);
});



Route::get('/employee-report-pdf/{employee}', [App\Http\Controllers\EmployeeReportController::class, 'downloadPDF'])
    ->name('employee.report.pdf')
    ->middleware('auth');

Route::get('/employee-report-stream/{employee}', [App\Http\Controllers\EmployeeReportController::class, 'streamPDF'])
    ->name('employee.report.stream')
    ->middleware('auth');

Route::post('/employee-report-custom/{employee}', [App\Http\Controllers\EmployeeReportController::class, 'generateCustomPeriod'])
    ->name('employee.report.custom')
    ->middleware('auth');


Route::get('/monitoring', [PublicEmployeeController::class, 'unexaminedEmployees'])
    ->name('monitoring.public');


