<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'Name'       => 'Adi Setiawan',
                'NRP'        => '21001975',
                'Position'   => 'Operator HD 785',
                'Departement'=> 'Produksi',
                'Company'    => 'PT PPA',
                'Status'     => 'open',
            ],
            [
                'Name'       => 'Budi Santoso',
                'NRP'        => '21001976',
                'Position'   => 'Operator DT 31',
                'Departement'=> 'Produksi',
                'Company'    => 'PT PPA',
                'Status'     => 'open',
            ],
        ];

        foreach ($employees as $emp) {
            Employee::create($emp);
        }
    }
}
