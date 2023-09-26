<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_types')->insert([
            ['id' => 1, 'name' => '_installer_project'], //MUST be `1
            ['id' => 2, 'name' => 'Articles'],
            ['id' => 3, 'name' => 'Case Studies'],
            ['id' => 4, 'name' => 'Technical Documents'],
            ['id' => 5, 'name' => 'Installation Videos'],
            ['id' => 6, 'name' => 'Social Media Accounts'],
            ['id' => 7, 'name' => 'Animal Welfare'],
        ]);
    }
}
