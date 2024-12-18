<?php

namespace Database\Seeders;

use App\Models\DeviceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeviceType::insert([
            ['name' => 'Worker device'],
            ['name' => 'Equipment device'],
            ['name' => 'Cone device'],
            ['name' => 'Other'],
        ]);
    }
}
