<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sectors')->insert([
            [
                'name' => 'Dairy',

            ],
            [
                'name' => 'Beef',

            ],
            [
                'name' => 'Arable',

            ],
            [
                'name' => 'Machinery',

            ],
            [
                'name' => 'Equestrian',

            ],
            [
                'name' => 'Other',

            ],
        ]);
    }
}
