<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('interests')->truncate();
        DB::table('interests')->insert([
            [
                'name' => 'Reading'
            ],
            [
                'name' => 'Video Games'
            ],
            [
                'name' => 'Sports'
            ],
            [
                'name' => 'Traveling'
            ],

        ]);
    }
}
