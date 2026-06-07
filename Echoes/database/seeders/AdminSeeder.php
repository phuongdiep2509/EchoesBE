<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo tài khoản Admin mặc định
        User::updateOrCreate(
            ['email' => 'admin@echoes.com'],
            [
                'username'  => 'admin',
                'name'      => 'Quản trị viên',
                'email'     => 'admin@echoes.com',
                'password'  => Hash::make('Admin@123'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );

        $this->command->info('✓ Tài khoản Admin đã được tạo:');
        $this->command->info('  Email   : admin@echoes.com');
        $this->command->info('  Password: Admin@123');
    }
}
