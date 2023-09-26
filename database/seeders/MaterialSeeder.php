<?php

namespace Database\Seeders;

use App\Models\Colour;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colourNaturalGrey = 1;
        $colourSlateBlue = 2;
        $colourBlack = 3;
        $colourVanDykeBrown = 4;
        $colourLaurelGreen = 5;
        $colourFarmscapeAnthracite = 6;

        $profile6_1220mm = 'Profile 6';
//        $profile6_1375mm = 'Profile 6 Sheet 1375mm';
//        $profile6_1525mm = 'Profile 6 Sheet 1525mm';
//        $profile6_1675mm = 'Profile 6 Sheet 1675mm';
//        $profile6_1825mm = 'Profile 6 Sheet 1825mm';
//        $profile6_1975mm = 'Profile 6 Sheet 1975mm';
//        $profile6_2125mm = 'Profile 6 Sheet 2125mm';
//        $profile6_2275mm = 'Profile 6 Sheet 2275mm';
//        $profile6_2440mm = 'Profile 6 Sheet 2440mm';
//        $profile6_2600mm = 'Profile 6 Sheet 2600mm';
//        $profile6_2750mm = 'Profile 6 Sheet 2750mm';
//        $profile6_2900mm = 'Profile 6 Sheet 2900mm';
//        $profile6_3050mm = 'Profile 6 Sheet 3050mm';

//        $webpMimetype = 0;
//        $jpegMimetype = 0;
        $mime = 0;
        $url = 'https://www.brainshift.ai/en-gb/corrugated-sheets-products/profile-6-uk/';

        $fileNaturalGrey = DB::table('files')->insertGetId([
            'filename' => 'profile-3-6-natural-grey-profiled-sheets-eternit.webp',
            'path' => 'materials',
            'mimetype' => $mime,
        ]);

        $fileSlateBlue = DB::table('files')->insertGetId([
            'filename' => 'profile-3-6-slate-blue-profiled-sheets-eternit.webp',
            'path' => 'materials',
            'mimetype' => $mime,
        ]);

        $fileBlack = DB::table('files')->insertGetId([
            'filename' => 'profile-3-6-black-profiled-sheets-eternit.webp',
            'path' => 'materials',
            'mimetype' => $mime,
        ]);

        $fileVanDykeBrown = DB::table('files')->insertGetId([
            'filename' => 'profile-3-6-van-dyke-brown-profiled-sheets-eternit.webp',
            'path' => 'materials',
            'mimetype' => $mime,
        ]);

        $fileLaurelGreen = DB::table('files')->insertGetId([
            'filename' => 'profile-3-6-laurel-green-profiled-sheets-eternit.webp',
            'path' => 'materials',
            'mimetype' => $mime,
        ]);

        $fileFarmscapeAnthracite = DB::table('files')->insertGetId([
            'filename' => 'profile-3-6-farmscape-profiled-sheets-eternit.webp',
            'path' => 'materials',
            'mimetype' => $mime,
        ]);

        $colours = Colour::pluck('name', 'id');

        // Profile 6 Sheet 1220mm
        DB::table('materials')->insert([
            [
                'name' => $profile6_1220mm.' '.$colours[$colourNaturalGrey],
                'product_line' => 0, // Profile 6
                'image' => $fileNaturalGrey,
                'colour' => $colourNaturalGrey,
                'shop_url' => $url,
            ],
            [
                'name' => $profile6_1220mm.' '.$colours[$colourSlateBlue],
                'product_line' => 0, // Profile 6
                'image' => $fileSlateBlue,
                'colour' => $colourSlateBlue,
                'shop_url' => $url,
            ],
            [
                'name' => $profile6_1220mm.' '.$colours[$colourBlack],
                'product_line' => 0, // Profile 6
                'image' => $fileBlack,
                'colour' => $colourBlack,
                'shop_url' => $url,
            ],
            [
                'name' => $profile6_1220mm.' '.$colours[$colourVanDykeBrown],
                'product_line' => 0, // Profile 6
                'image' => $fileVanDykeBrown,
                'colour' => $colourVanDykeBrown,
                'shop_url' => $url,
            ],
            [
                'name' => $profile6_1220mm.' '.$colours[$colourLaurelGreen],
                'product_line' => 0, // Profile 6
                'image' => $fileLaurelGreen,
                'colour' => $colourLaurelGreen,
                'shop_url' => $url,
            ],
            [
                'name' => $profile6_1220mm.' '.$colours[$colourFarmscapeAnthracite],
                'product_line' => 0, // Profile 6
                'image' => $fileFarmscapeAnthracite,
                'colour' => $colourFarmscapeAnthracite,
                'shop_url' => $url,
            ],
        ]);
//
//        // Profile 6 Sheet 1375mm
//        DB::table('materials')->insert([
//            [
//                'name' => $profile6_1375mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileNaturalGrey,
//                'colour' => $colourNaturalGrey,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1375mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileSlateBlue,
//                'colour' => $colourSlateBlue,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1375mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileBlack,
//                'colour' => $colourBlack,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1375mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileVanDykeBrown,
//                'colour' => $colourVanDykeBrown,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1375mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileLaurelGreen,
//                'colour' => $colourLaurelGreen,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1375mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileFarmscapeAnthracite,
//                'colour' => $colourFarmscapeAnthracite,
//                'shop_url' => 'profile-6-uk',
//            ],
//        ]);
//
//        // Profile 6 Sheet 1525mm
//        DB::table('materials')->insert([
//            [
//                'name' => $profile6_1525mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileNaturalGrey,
//                'colour' => $colourNaturalGrey,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1525mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileSlateBlue,
//                'colour' => $colourSlateBlue,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1525mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileBlack,
//                'colour' => $colourBlack,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1525mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileVanDykeBrown,
//                'colour' => $colourVanDykeBrown,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1525mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileLaurelGreen,
//                'colour' => $colourLaurelGreen,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1525mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileFarmscapeAnthracite,
//                'colour' => $colourFarmscapeAnthracite,
//                'shop_url' => 'profile-6-uk',
//            ],
//        ]);
//
//        // Profile 6 Sheet 1675mm
//        DB::table('materials')->insert([
//            [
//                'name' => $profile6_1675mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileNaturalGrey,
//                'colour' => $colourNaturalGrey,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1675mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileSlateBlue,
//                'colour' => $colourSlateBlue,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1675mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileBlack,
//                'colour' => $colourBlack,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1675mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileVanDykeBrown,
//                'colour' => $colourVanDykeBrown,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1675mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileLaurelGreen,
//                'colour' => $colourLaurelGreen,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1675mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileFarmscapeAnthracite,
//                'colour' => $colourFarmscapeAnthracite,
//                'shop_url' => 'profile-6-uk',
//            ],
//        ]);
//
//        // Profile 6 Sheet 1825mm
//        DB::table('materials')->insert([
//            [
//                'name' => $profile6_1825mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileNaturalGrey,
//                'colour' => $colourNaturalGrey,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1825mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileSlateBlue,
//                'colour' => $colourSlateBlue,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1825mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileBlack,
//                'colour' => $colourBlack,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1825mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileVanDykeBrown,
//                'colour' => $colourVanDykeBrown,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1825mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileLaurelGreen,
//                'colour' => $colourLaurelGreen,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1825mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileFarmscapeAnthracite,
//                'colour' => $colourFarmscapeAnthracite,
//                'shop_url' => 'profile-6-uk',
//            ],
//        ]);
//
//        // Profile 6 Sheet 1975mm
//        DB::table('materials')->insert([
//            [
//                'name' => $profile6_1975mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileNaturalGrey,
//                'colour' => $colourNaturalGrey,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1975mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileSlateBlue,
//                'colour' => $colourSlateBlue,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1975mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileBlack,
//                'colour' => $colourBlack,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1975mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileVanDykeBrown,
//                'colour' => $colourVanDykeBrown,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1975mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileLaurelGreen,
//                'colour' => $colourLaurelGreen,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_1975mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileFarmscapeAnthracite,
//                'colour' => $colourFarmscapeAnthracite,
//                'shop_url' => 'profile-6-uk',
//            ],
//        ]);
//
//        // Profile 6 Sheet 2125mm
//        DB::table('materials')->insert([
//            [
//                'name' => $profile6_2125mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileNaturalGrey,
//                'colour' => $colourNaturalGrey,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2125mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileSlateBlue,
//                'colour' => $colourSlateBlue,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2125mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileBlack,
//                'colour' => $colourBlack,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2125mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileVanDykeBrown,
//                'colour' => $colourVanDykeBrown,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2125mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileLaurelGreen,
//                'colour' => $colourLaurelGreen,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2125mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileFarmscapeAnthracite,
//                'colour' => $colourFarmscapeAnthracite,
//                'shop_url' => 'profile-6-uk',
//            ],
//        ]);
//
//        // Profile 6 Sheet 2275mm
//        DB::table('materials')->insert([
//            [
//                'name' => $profile6_2275mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileNaturalGrey,
//                'colour' => $colourNaturalGrey,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2275mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileSlateBlue,
//                'colour' => $colourSlateBlue,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2275mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileBlack,
//                'colour' => $colourBlack,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2275mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileVanDykeBrown,
//                'colour' => $colourVanDykeBrown,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2275mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileLaurelGreen,
//                'colour' => $colourLaurelGreen,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2275mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileFarmscapeAnthracite,
//                'colour' => $colourFarmscapeAnthracite,
//                'shop_url' => 'profile-6-uk',
//            ],
//        ]);
//
//        // Profile 6 Sheet 2440mm
//        DB::table('materials')->insert([
//            [
//                'name' => $profile6_2440mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileNaturalGrey,
//                'colour' => $colourNaturalGrey,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2440mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileSlateBlue,
//                'colour' => $colourSlateBlue,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2440mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileBlack,
//                'colour' => $colourBlack,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2440mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileVanDykeBrown,
//                'colour' => $colourVanDykeBrown,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2440mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileLaurelGreen,
//                'colour' => $colourLaurelGreen,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2440mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileFarmscapeAnthracite,
//                'colour' => $colourFarmscapeAnthracite,
//                'shop_url' => 'profile-6-uk',
//            ],
//        ]);
//
//        // Profile 6 Sheet 2600mm
//        DB::table('materials')->insert([
//            [
//                'name' => $profile6_2600mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileNaturalGrey,
//                'colour' => $colourNaturalGrey,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2600mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileSlateBlue,
//                'colour' => $colourSlateBlue,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2600mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileBlack,
//                'colour' => $colourBlack,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2600mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileVanDykeBrown,
//                'colour' => $colourVanDykeBrown,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2600mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileLaurelGreen,
//                'colour' => $colourLaurelGreen,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2600mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileFarmscapeAnthracite,
//                'colour' => $colourFarmscapeAnthracite,
//                'shop_url' => 'profile-6-uk',
//            ],
//        ]);
//
//        // Profile 6 Sheet 2600mm
//        DB::table('materials')->insert([
//            [
//                'name' => $profile6_2750mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileNaturalGrey,
//                'colour' => $colourNaturalGrey,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2750mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileSlateBlue,
//                'colour' => $colourSlateBlue,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2750mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileBlack,
//                'colour' => $colourBlack,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2750mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileVanDykeBrown,
//                'colour' => $colourVanDykeBrown,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2750mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileLaurelGreen,
//                'colour' => $colourLaurelGreen,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2750mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileFarmscapeAnthracite,
//                'colour' => $colourFarmscapeAnthracite,
//                'shop_url' => 'profile-6-uk',
//            ],
//        ]);
//
//        // Profile 6 Sheet 2900mm
//        DB::table('materials')->insert([
//            [
//                'name' => $profile6_2900mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileNaturalGrey,
//                'colour' => $colourNaturalGrey,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2900mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileSlateBlue,
//                'colour' => $colourSlateBlue,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2900mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileBlack,
//                'colour' => $colourBlack,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2900mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileVanDykeBrown,
//                'colour' => $colourVanDykeBrown,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2900mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileLaurelGreen,
//                'colour' => $colourLaurelGreen,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_2900mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileFarmscapeAnthracite,
//                'colour' => $colourFarmscapeAnthracite,
//                'shop_url' => 'profile-6-uk',
//            ],
//        ]);
//
//        // Profile 6 Sheet 3050mm
//        DB::table('materials')->insert([
//            [
//                'name' => $profile6_3050mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileNaturalGrey,
//                'colour' => $colourNaturalGrey,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_3050mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileSlateBlue,
//                'colour' => $colourSlateBlue,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_3050mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileBlack,
//                'colour' => $colourBlack,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_3050mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileVanDykeBrown,
//                'colour' => $colourVanDykeBrown,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_3050mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileLaurelGreen,
//                'colour' => $colourLaurelGreen,
//                'shop_url' => 'profile-6-uk',
//            ],
//            [
//                'name' => $profile6_3050mm,
//                'product_line' => 0, // Profile 6
//                'image' => $fileFarmscapeAnthracite,
//                'colour' => $colourFarmscapeAnthracite,
//                'shop_url' => 'profile-6-uk',
//            ],
//        ]);
    }
}
