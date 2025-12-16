<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class MainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Student (Azid)
        User::create([
            'name' => 'Azid (Siswa)',
            'email' => 'student@school.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        // 2. Create Teacher (Guru BK)
        User::create([
            'name' => 'Ibu Guru BK',
            'email' => 'teacher@school.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        // 3. Create Admin (Optional)
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->command->info('✅ Mental Health App Users Seeded Successfully!');
        $this->command->info('👉 Student: student@school.com | password');
        $this->command->info('👉 Teacher: teacher@school.com | password');
    }
}
