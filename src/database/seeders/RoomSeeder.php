<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rooms')->updateOrInsert(
            ['user1_id' => 1, 'user2_id' => 2],
            ['created_at' => now(), 'updated_at' => now()]
        );

        DB::table('rooms')->updateOrInsert(
            ['user1_id' => 2, 'user2_id' => 3],
            ['created_at' => now(), 'updated_at' => now()]
        );

        DB::table('rooms')->updateOrInsert(
            ['user1_id' => 1, 'user2_id' => 3],
            ['created_at' => now(), 'updated_at' => now()]
        );
    }
}
