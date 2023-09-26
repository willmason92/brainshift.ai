<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('goals')->insert([
            ['name' => 'Improve my yield'],
            ['name' => 'Modernise the farm'],
            ['name' => 'Lower the cost of maintenance'],
            ['name' => 'Increase herd size'],
            ['name' => 'Improve animal health & welfare'],
        ]);
    }
}
