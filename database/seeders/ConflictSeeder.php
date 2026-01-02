<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Journal;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ConflictSeeder extends Seeder
{
    public function run()
    {
        // 1. Create or Get 'Aziid'
        $aziid = User::firstOrCreate(
            ['email' => 'aziid@student.com'],
            [
                'name' => 'Aziid',
                'password' => Hash::make('password'),
                'role' => 'student',
                'risk_score' => 0
            ]
        );

        // 2. Create or Get 'Student B' (Target of conflict)
        $studentB = User::firstOrCreate(
            ['email' => 'studentb@student.com'],
            [
                'name' => 'Budi Santoso', // Let's call him Budi
                'password' => Hash::make('password'),
                'role' => 'student',
                'risk_score' => 0
            ]
        );

        // Clean up old journals for Aziid to ensure clean test
        Journal::where('user_id', $aziid->id)->delete();

        // 3. Create 3 Journals representing the conflict flow
        $journals = [
            [
                'content' => 'Hari ini aku kesel banget sama Budi. Dia minjem buku catatanku tapi pas dibalikin sobek. Padahal itu catetan penting buat ujian besok. Dia malah ketawa doang pas aku tegur.',
                'mood' => 'angry',
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'content' => 'Masih kepikiran soal kemarin. Si Budi bukannya minta maaf malah nyindir aku di grup kelas. Katanya aku baperan cuma gara-gara kertas sobek. Sumpah sakit hati banget digituin di depan anak-anak lain.',
                'mood' => 'sad',
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'content' => 'Males banget ke sekolah kalau ada Budi. Rasanya pengen nonjok dia kalau ketemu. Kenapa sih dia nggak bisa ngehargain barang orang lain? Awas aja kalau dia macem-macem lagi.',
                'mood' => 'angry',
                'created_at' => Carbon::now(),
            ],
        ];

        foreach ($journals as $data) {
            Journal::create([
                'user_id' => $aziid->id,
                'title' => 'Curhat Harian',
                'content' => $data['content'],
                'mood' => $data['mood'],
                'created_at' => $data['created_at'],
                'updated_at' => $data['created_at'],
            ]);
        }

        $this->command->info("Conflict Scenario Seeded: Aziid vs Budi (3 Journals)");
    }
}
