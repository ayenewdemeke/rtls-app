<?php

namespace Database\Seeders;

use App\Models\WorkZoneStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkZoneStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkZoneStatus::insert([
            ['name' => 'Pending'],
            ['name' => 'Active'],
            ['name' => 'Inactive'],
        ]);
    }
}
