<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Journal;

class MainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Student (Azid)
        $student = User::firstOrCreate(
            ['email' => 'student@school.com'],
            [
                'name' => 'Azid (Siswa)',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]
        );

        // 2. Create Teacher (Guru BK)
        User::firstOrCreate(
            ['email' => 'teacher@school.com'],
            [
                'name' => 'Ibu Guru BK',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ]
        );

        // 3. Create Admin
        User::firstOrCreate(
            ['email' => 'admin@school.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 4. Create Sample Journals for Student
        // Ensure we don't duplicate if already seeded (simple check)
        if (Journal::where('user_id', $student->id)->count() === 0) {
            
            $journals = [
                [
                    'title' => 'Hari ini cukup menyenangkan',
                    'content' => 'Saya berhasil mengerjakan tugas matematika dengan baik. Teman-teman juga sangat suportif hari ini.',
                    'mood' => 'happy',
                    'is_anonymous' => false,
                    'created_at' => now()->subDays(2),
                ],
                [
                    'title' => 'Merasa sedikit cemas',
                    'content' => 'Ada ujian besok dan saya merasa belum siap sepenuhnya. Perlu belajar lebih giat lagi malam ini.',
                    'mood' => 'neutral',
                    'is_anonymous' => false,
                    'created_at' => now()->subDays(1),
                ],
                [
                    'title' => 'Masalah di kantin',
                    'content' => 'Ada kakak kelas yang menyindir saya tadi di kantin. Saya merasa tidak nyaman tapi takut untuk melapor. Saya harap guru bisa lebih memperhatikan saat istirahat.',
                    'mood' => 'sad',
                    'is_anonymous' => true, // Test Anonymous Feature
                    'created_at' => now()->subHours(5),
                ],
                [
                    'title' => 'Latihan Basket',
                    'content' => 'Latihan hari ini sangat melelahkan tapi seru. Tim kami semakin kompak.',
                    'mood' => 'calm',
                    'is_anonymous' => false,
                    'created_at' => now()->subHours(1),
                ],
            ];

            foreach ($journals as $data) {
                Journal::create([
                    'user_id' => $student->id,
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'mood' => $data['mood'],
                    'is_anonymous' => $data['is_anonymous'],
                    'created_at' => $data['created_at'],
                ]);
            }
        }

        $this->command->info('✅ Mental Health App Users & Journals Seeded Successfully!');
        $this->command->info('👉 Student: student@school.com (Has 4 Journals)');
        $this->command->info('👉 Teacher: teacher@school.com');
        $this->command->info('👉 Admin:   admin@school.com');
    }
}
