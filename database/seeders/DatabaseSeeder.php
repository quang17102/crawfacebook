<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Comment;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::insert(
            [
                [
                    'name' => 'duongvankhai2022001@gmail.com',
                    'password' => Hash::make(1),
                    'role' => 1,
                    'limit' => 5,
                    'expire' => '2025-01-01',
                    'limit_follow' => 5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'user@gmail.com',
                    'password' => Hash::make(1),
                    'role' => 0,
                    'limit' => 5,
                    'expire' => '2025-01-01',
                    'limit_follow' => 5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'tu@gmail.com',
                    'password' => Hash::make(1),
                    'role' => 0,
                    'limit' => 5,
                    'expire' => '2025-01-01',
                    'limit_follow' => 5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );

        Setting::insert([
            [
                'key' => 'craw-count',
                'name' => 'Số luồng crawl count',
                'value' => '5',
            ],
            [
                'key' => 'time-delay',
                'name' => 'Delay time mỗi luồng crawl count (ms)',
                'value' => '2000',
            ]
        ]);
    }
}
