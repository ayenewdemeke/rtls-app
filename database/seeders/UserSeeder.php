<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Ayenew Demeke',
            'email' => 'ayennew@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        $user->roles()->attach(Role::SUPER_ADMIN);
    }
}
