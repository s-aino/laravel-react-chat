<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'taro@example.com'],
            [
                'name' => '太郎',
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'hanako@example.com'],
            [
                'name' => '花子',
                'password' => Hash::make('password'),
            ]
        );
    }
}
