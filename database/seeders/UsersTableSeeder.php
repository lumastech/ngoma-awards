<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'System Admin',
                'email' => 'admin@ngoma.com',
                'role' => 'admin',
                'phone' => '1234567890',
                'address' => '123 Main St',
                'status' => 'active',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ],
            // You can add more users here
        ]);
    }
}
