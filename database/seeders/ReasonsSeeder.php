<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('reasons')->insert([
            ['name' => 'Replacing old sheets'],
            ['name' => 'Modernise the farm'],
            ['name' => 'Lower the cost of maintenance'],
            ['name' => 'Improve animal health & welfare'],
            ['name' => 'Increase herd number'],
        ]);
    }
}
