<?php

namespace Database\Seeders;

use App\Models\SystemStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemStatus::insert([
            ['name' => 'Pending'],
            ['name' => 'Working'],
            ['name' => 'Maintenance'],
            ['name' => 'Stopped'],
        ]);
    }
}
