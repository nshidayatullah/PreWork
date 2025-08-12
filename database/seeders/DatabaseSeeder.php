<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\RosterSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\EmployeeSeeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EmployeeSeeder::class,
            RosterSeeder::class,
            UserSeeder::class,
        ]);
}



}
