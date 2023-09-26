<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialitiesSeeds extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('specialities')->insert([
            ['name' => 'New Builds'],
            ['name' => 'Refurbishment'],
            ['name' => 'Asbestos Removal'],

        ]);
    }
}
