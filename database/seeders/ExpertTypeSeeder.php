<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpertTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('expert_type')->insert([
            ['name' => 'Installers'],
            ['name' => 'Eternit Sales'],
            ['name' => 'Eternit Technical'],
            ['name' => 'Animal Welfare'],
            ['name' => 'Agri Colleges and Universities'],
            ['name' => 'Sustainable Farming'],
            ['name' => 'Vets'],
            ['name' => 'Associations'],
        ]);
        //
    }
}
