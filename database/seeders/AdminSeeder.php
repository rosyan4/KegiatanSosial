<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hanya satu admin utama
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@rt-rw.local',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'address' => 'Kantor RT/RW',
            'rt' => '01',
            'rw' => '01',
            'profile_photo' => null,
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
            'phone_verified_at' => Carbon::now(),
        ]);

        $this->command->info('✅ Admin berhasil dibuat!');
        $this->command->info('📧 Email: admin@rt-rw.local');
        $this->command->info('🔑 Password: password123');
    }
}