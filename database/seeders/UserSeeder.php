<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'neeraj@gmail.com',
            ],
            [
                'name' => 'Neeraj Choudhary',
                'password' => 'test@123'
            ]
        );

        // info("User add successfully via seeder");
    }
}
