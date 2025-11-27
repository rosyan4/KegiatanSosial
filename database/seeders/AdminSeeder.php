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
            'email' => 'rosyanassiry14@gmail.com',
            'phone' => '0895618417641',
            'password' => Hash::make('ahmadrosyan'),
            'role' => 'admin',
            'address' => 'Kantor RT',
            'rt' => '04',
            'rw' => '01',
            'profile_photo' => null,
            'is_active' => true,
            'email_verified_at' => Carbon::now(),
            'phone_verified_at' => Carbon::now(),
        ]);

        $this->command->info('✅ Admin berhasil dibuat!');
        $this->command->info('📧 Email: rosyanassiry14@gmail.com');
        $this->command->info('🔑 Password: ahmadrosyan');
    }
}