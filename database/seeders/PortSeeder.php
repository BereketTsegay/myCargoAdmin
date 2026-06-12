<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create the ports
        $ports = [
            ['name' => 'Massawa', 'country' => 'Eritrea', 'country_code' => 'ER'],
            ['name' => 'Jebel Ali', 'country' => 'United Arab Emirates', 'country_code' => 'AE'],
        ];

        foreach ($ports as $port) {
            \App\Models\Port::create($port);
        }
    }
}
