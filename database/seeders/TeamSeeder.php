<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('teams')->insert([
            'name' => 'Liverpool',
            'power' => 80,
        ]);

        DB::table('teams')->insert([
            'name' => 'Manchester City',
            'power' => 75,
        ]);


        DB::table('teams')->insert([
            'name' => 'Chelsea',
            'power' => 90,
        ]);

        DB::table('teams')->insert([
            'name' => 'Arsenal',
            'power' => 90,
        ]);
    }
}
