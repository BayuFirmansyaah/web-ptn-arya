<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/peserta.json'));
        $data = json_decode($json, true);
        foreach ($data as $item) {
            \App\Models\Student::create([
                'nama' => $item['nama'],
                'program' => $item['program'] ?? null,
                'kelas' => $item['kelas'] ?? null,
                'ma' => $item['ma'] ?? null,
                'gender' => $item['gender'] ?? null,
                'jurusan' => $item['jurusan'] ?? null,
            ]);
        }

        $this->createAdminUser();
    }

    // create user from ./admin.json
    public function createAdminUser()
    {
        $json = file_get_contents(database_path('seeders/admin.json'));
        $data = json_decode($json, true);
        foreach ($data as $item) {
            User::create([
                'name' => $item['username'],
                'email' => $item['username'] . '@custom.local',
                'password' => Hash::make('admin12345'),
                'role' => $item['role'] ?? 'admin',
            ]);
        }
    }
}