<?php

namespace Database\Seeders;

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
            ['id' => 1, 'name' => 'Natural Grey'],
            ['id' => 2, 'name' => 'Slate Blue'],
            ['id' => 3, 'name' => 'Black'],
            ['id' => 4, 'name' => 'Van Dyke Brown'],
            ['id' => 5, 'name' => 'Laurel Green'],
            ['id' => 6, 'name' => 'Farmscape Anthracite'],
        ]);
    }
}
