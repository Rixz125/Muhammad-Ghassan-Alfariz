<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'role' => 'user',
            'password' => Hash::make('userID')
        ]);
        // User::create([
        //     'name' => 'Administrator',
        //     'email' => 'admin@gmail.com',
        //     'role' => 'admin',
        //     'password' => Hash::make('adminID')
        // ]);

    //     User::create([
    //         'name' => 'Staff',
    //         'email' => 'staff@gmail.com',
    //         'role'  => 'staff',
    //         'password' => Hash::make('staffID')
    // ]);

    }
}
