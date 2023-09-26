<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1
        DB::table('fields')->insert([
            'type' => 10,
            'name' => 'Gallery',
            'posttype_id' => 3,
        ]);

        // 2
        DB::table('fields')->insert([
            'type' => 4,
            'name' => 'Location',
            'posttype_id' => 3,
        ]);

        // 3
        DB::table('fields')->insert([
            'type' => 6,
            'name' => 'Type',
            'posttype_id' => 3,
        ]);

        // 4
        DB::table('fields')->insert([
            'type' => 2,
            'name' => 'Project Completed On',
            'posttype_id' => 3,
        ]);

        // 5
        DB::table('fields')->insert([
            'type' => 5,
            'name' => 'Materials Used',
            'posttype_id' => 3,
        ]);

        // 6
        DB::table('fields')->insert([
            'type' => 3,
            'name' => 'Upload File',
            'posttype_id' => 4,
        ]);

        // 7
        DB::table('fields')->insert([
            'type' => 7,
            'name' => 'Youtube Video URL',
            'posttype_id' => 5,
        ]);

        // 8
        DB::table('fields')->insert([
            'type' => 8,
            'name' => 'youtube_poster_override',
            'posttype_id' => 5,
        ]);

        // 9
        DB::table('fields')->insert([
            'type' => 9,
            'name' => 'Social Media URL',
            'posttype_id' => 6,
        ]);

        // 10
        DB::table('fields')->insert([
            'type' => 9,
            'name' => 'Media Partner URL',
            'posttype_id' => 7,
        ]);

        //Installer Profile - Gallery - reusing field 1 === ->gallery
        //        DB::table('fields')->insert([
        //            'type' => 10,
        //            'name' => 'Installer Project Gallery',
        //            'posttype_id' => 7,
        //        ]);
        // 11 - Installer Profile - Sector
        DB::table('fields')->insert([
            'type' => 11,
            'name' => 'Installer Project Sector',
            'posttype_id' => 1,
        ]);

        // 12 - Installer Profile - SizeLength
        DB::table('fields')->insert([
            'type' => 12,
            'name' => 'Installer Project Length',
            'posttype_id' => 1,
        ]);

        // 13 - Installer Profile - SizeWidth
        DB::table('fields')->insert([
            'type' => 12,
            'name' => 'Installer Project Width',
            'posttype_id' => 1,
        ]);

        // 14 - Installer Profile - SizeUnit
        DB::table('fields')->insert([
            'type' => 13,
            'name' => 'Installer Project Size Unit',
            'posttype_id' => 1,
        ]);
    }
}
