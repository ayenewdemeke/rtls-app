<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            ['name' => 'Super Admin', 'description' => 'Administrator with full access'],
            ['name' => 'Admin', 'description' => 'Administrator with full access except adding admins'],
            ['name' => 'User', 'description' => 'Regular user with limited access'],
        ]);
    }
}
