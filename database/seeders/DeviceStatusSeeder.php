<?php

namespace Database\Seeders;

use App\Models\DeviceStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeviceStatus::insert([
            ['name' => 'Pending'],
            ['name' => 'Working'],
            ['name' => 'Under maintenance'],
            ['name' => 'Not working'],
        ]);
    }
}
