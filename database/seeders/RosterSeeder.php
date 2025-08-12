<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Roster;
use Carbon\Carbon;

class RosterSeeder extends Seeder
{
    public function run(): void
    {
        $month = '2025-08'; // bulan target
        $employeeIds = [1, 2]; // sesuaikan dengan ID employee yang sudah dibuat

        $shifts = [
            'shift 1',
            'shift 2',
            'off',
            'cuti',
            'dirumahkan',
            'training',
            'tugas luar',
        ];

        foreach ($employeeIds as $empId) {
            // Buat shift selama 10 hari pertama bulan ini
            for ($day = 1; $day <= 30; $day++) {
                Roster::create([
                    'employee_id' => $empId,
                    'month'       => Carbon::parse("$month-01"),
                    'date'        => Carbon::parse("$month-$day"),
                    'shift'       => collect($shifts)->random(),
                ]);
            }
        }
    }
}
