<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        $users = [
            ['nrp' => '20000536', 'name' => 'M RIZAL FAUZI'],
            ['nrp' => '22001357', 'name' => 'AGUNG PRIAMBARA'],
            ['nrp' => '21001660', 'name' => 'HUDA AL FARIS'],
            ['nrp' => '22001018', 'name' => 'INDRA PRATAMA FRANS'],
            ['nrp' => '23000614', 'name' => 'M. ADDIN RIDHANI PUTRA'],
            ['nrp' => '21001624', 'name' => 'M HUSAINI'],
            ['nrp' => '22005487', 'name' => 'M. HIDAYATULLAH'],
            ['nrp' => '25001041', 'name' => 'WAHYUDI'],
            ['nrp' => '25000866', 'name' => 'FATIMAH ZAHRATANNOR'],
            ['nrp' => '25001862', 'name' => 'MUHAMMAD SURYANI'],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'name' => ucwords(strtolower($user['name'])),
                'nrp' => $user['nrp'],
                'password' => Hash::make($user['nrp']), // password = NRP
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
