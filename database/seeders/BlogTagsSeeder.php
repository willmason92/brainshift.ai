<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('blog_tags')->insert([
            ['name' => 'Case Studies'],
            ['name' => 'Installation Videos'],
            ['name' => 'Farming Articles'],
            ['name' => 'Technical Documents'],
            ['name' => 'Agri Brochure'],
            ['name' => 'Social Media'],
            ['name' => 'Media'],
            ['name' => 'Dairy'],
            ['name' => 'Beef'],
            ['name' => 'Arable'],
            ['name' => 'Storage'],
            ['name' => 'Refurb'],
            ['name' => 'New Build'],
            ['name' => 'Animal Welfare'],
            ['name' => 'Equestrian'],
            ['name' => 'Product'],
            ['name' => 'FarmTec'],
            ['name' => 'Sustainability'],
            ['name' => 'Innovation'],
            ['name' => 'Sales'],
            ['name' => 'Testimonials'],
            ['name' => 'FarmTec'],
            ['name' => 'Profile 6'],
            ['name' => 'Accessories + Fittings'],
            ['name' => 'Farm Buildings'],
            ['name' => 'Environment'],
            ['name' => 'Booklet'],
            ['name' => 'Datasheet'],
            ['name' => 'Certificate'],
            ['name' => 'Performance Declaration'],
        ]);
        //
    }
}
