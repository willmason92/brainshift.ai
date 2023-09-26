<?php

namespace Database\Seeders\TestData;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('colours')->insert([
            ['id' => 1, 'name' => 'Van Dyke Brown'],
            ['id' => 2, 'name' => 'Sherwood'],
            ['id' => 3, 'name' => 'Laurel Green'],
            ['id' => 4, 'name' => 'Black'],
            ['id' => 5, 'name' => 'Slate Blue'],
            ['id' => 6, 'name' => 'Natural Grey'],
            ['id' => 7, 'name' => 'Farmscape Anthracite'],
        ]);
    }
}
