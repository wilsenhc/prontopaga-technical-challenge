<?php

namespace Database\Seeders;

use App\Models\User;
use App\RoleEnum;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Doctor Doctor',
            'email' => 'doctor@example.com',
            'password' => bcrypt('password'),
            'role_id' => RoleEnum::DOCTOR,
        ]);

        User::factory()->create([
            'name' => 'Patient Patient',
            'email' => 'patient@example.com',
            'password' => bcrypt('password'),
            'role_id' => RoleEnum::PATIENT,
        ]);
    }
}
