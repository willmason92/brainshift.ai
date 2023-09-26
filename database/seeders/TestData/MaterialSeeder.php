<?php

namespace Database\Seeders\TestData;

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
        $jpgMimetype = 0;

        $farmscape_1525mm = 'Profile 6 Sheet 1525mm';
        $farmscape_1675mm = 'Profile 6 Sheet 1675mm';
        $farmscape_2440mm = 'Profile 6 Sheet 2440mm';
        $farmscape_2750mm = 'Profile 6 Sheet 2750mm';
        $farmscape_2900mm = 'Profile 6 Sheet 2900mm';
        $farmscape_3050mm = 'Profile 6 Sheet 3050mm';

        // Profile 6 Fibre Cement Cranked Crown
        $fileCrankedCrown = DB::table('files')->insertGetId([
            'filename' => '2 Cranked crown sheet Profile 6.jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'Profile 6 Fibre Cement Cranked Crown',
                'product_line' => 0, // Profile 6
                'image' => $fileCrankedCrown,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Profile 6 Fibre Cement Cranked Vent
        $fileVentilatingCrown = DB::table('files')->insertGetId([
            'filename' => '3 Ventilating crown sheet Profile 6.jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'Profile 6 Fibre Cement Cranked Vent',
                'product_line' => 0, // Profile 6
                'image' => $fileVentilatingCrown,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Profile 6 Fibre Cement Open Protected Ridge Flashing
        $fileOpenProtectedRidge = DB::table('files')->insertGetId([
            'filename' => '4 Open protected ridge flashing Profile 6.jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'Profile 6 Fibre Cement Open Protected Ridge Flashing',
                'product_line' => 0, // Profile 6
                'image' => $fileOpenProtectedRidge,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // P6 Two Piece Close Fitting Ridge (half)
        $fileTwoPieceClose = DB::table('files')->insertGetId([
            'filename' => '6 Two piece close fitting ridge Profile 6.jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'P6 Two Piece Close Fitting Ridge (half)',
                'product_line' => 0, // Profile 6
                'image' => $fileTwoPieceClose,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Profile 6 Fibre Cement 2 piece ventilating ridge
        $fileTwoPieceVentilating = DB::table('files')->insertGetId([
            'filename' => '7 Two piece ventilating ridge Profile 6.jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'Profile 6 Fibre Cement 2 piece ventilating ridge',
                'product_line' => 0, // Profile 6
                'image' => $fileTwoPieceVentilating,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // P6 Two Piece Plain Wing Ridge (half)
        $fileTwoPiecePlainWing = DB::table('files')->insertGetId([
            'filename' => '8 Two piece plain wing ridge Profile 6.jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'P6 Two Piece Plain Wing Ridge (half)',
                'product_line' => 0, // Profile 6
                'image' => $fileTwoPiecePlainWing,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // P6 Disc Finial
        $fileDiscTypeRidge = DB::table('files')->insertGetId([
            'filename' => '10 Disc type ridge finial Profile 6.jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'P6 Disc Finial',
                'product_line' => 0, // Profile 6
                'image' => $fileDiscTypeRidge,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // P6 Two Piece Hooded Final (half)
        $fileHoodedTwoPieceRidge = DB::table('files')->insertGetId([
            'filename' => '11 Hooded two piece ridge finial Profile 6.jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'P6 Two Piece Hooded Final (half)',
                'product_line' => 0, // Profile 6
                'image' => $fileHoodedTwoPieceRidge,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Rolltop Bargeboard
        $fileRollTopBarge = DB::table('files')->insertGetId([
            'filename' => '12 Roll top barge board Profile 6.jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'Rolltop Bargeboard',
                'product_line' => 0, // Profile 6
                'image' => $fileRollTopBarge,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Cranked Rolltop Bargeboard
        $fileCrankedCrownRollTop = DB::table('files')->insertGetId([
            'filename' => '13Cranked crown roll top barge board Profile 6.jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'Cranked Rolltop Bargeboard',
                'product_line' => 0, // Profile 6
                'image' => $fileCrankedCrownRollTop,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Fibre Cement Plain Barge Board
        $fileExternalCornerPiece = DB::table('files')->insertGetId([
            'filename' => '14  External corner piece Profile 6 or Profile 3.jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'Fibre Cement Plain Barge Board',
                'product_line' => 0, // Profile 6
                'image' => $fileExternalCornerPiece,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Profile 6 Fibre Cement Cranked Plain Barge Board (=Cranked external corner)
        $fileCrankedExternalCornerPiece = DB::table('files')->insertGetId([
            'filename' => '15 Cranked external corner piece Profile 6.jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'Profile 6 Fibre Cement Cranked Plain Barge Board (=Cranked external corner)',
                'product_line' => 0, // Profile 6
                'image' => $fileCrankedExternalCornerPiece,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // P6 Eaves Filler
        $fileEavesFillerPiece = DB::table('files')->insertGetId([
            'filename' => '20 Eaves filler piece Profile 6 (1).jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'P6 Eaves Filler',
                'product_line' => 0, // Profile 6
                'image' => $fileEavesFillerPiece,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // P6 Apron Flashing
        $fileApronFlashingPiece = DB::table('files')->insertGetId([
            'filename' => '21 Apron flashing piece Profile 6 (1).jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'P6 Apron Flashing',
                'product_line' => 0, // Profile 6
                'image' => $fileApronFlashingPiece,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Movement Joint
        $fileMovementJoint = DB::table('files')->insertGetId([
            'filename' => '22 Movement joint Profile 6 (1).jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'Movement Joint',
                'product_line' => 0, // Profile 6
                'image' => $fileMovementJoint,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Cranked Movement Joint
        $fileCrankedMovementJoint = DB::table('files')->insertGetId([
            'filename' => '23 Cranked movement joint Profile 6.jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'Cranked Movement Joint',
                'product_line' => 0, // Profile 6
                'image' => $fileCrankedMovementJoint,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Movement Joint Two Piece Ridge (half)
        $fileMovementJointTwoPiece = DB::table('files')->insertGetId([
            'filename' => '24 Movement joint two piece Profile 6.jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'Movement Joint Two Piece Ridge (half)',
                'product_line' => 0, // Profile 6
                'image' => $fileMovementJointTwoPiece,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Movement Joint Stop End
        $fileMovementJointStopEnd = DB::table('files')->insertGetId([
            'filename' => '25 Movement joint stop end Profile 6 (1).jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'Movement Joint Stop End',
                'product_line' => 0, // Profile 6
                'image' => $fileMovementJointStopEnd,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Profile 6 GRP Translucent Sheet - Agrisheet
        $fileGRPTranslucentSheet = DB::table('files')->insertGetId([
            'filename' => '38 GRP translucent sheet Profile 6.jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'Profile 6 GRP Translucent Sheet - Agrisheet',
                'product_line' => 0, // Profile 6
                'image' => $fileGRPTranslucentSheet,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // External Corner
        $fileUPExternal = DB::table('files')->insertGetId([
            'filename' => '44 UP External Corner.jpg',
            'path' => 'uploads/materials/',
            'mimetype' => $jpgMimetype,
        ]);
        DB::table('materials')->insert([
            [
                'name' => 'External Corner',
                'product_line' => 0, // Profile 6
                'image' => $fileUPExternal,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Profile 6 Sheet 1525mm
        DB::table('materials')->insert([
            [
                'name' => $farmscape_1525mm,
                'product_line' => 1, // Profile 6
                'image' => $fileFarmscapeAnthracite,
                'colour' => $colourFarmscapeAnthracite,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Profile 6 Sheet 1675mm
        DB::table('materials')->insert([
            [
                'name' => $farmscape_1675mm,
                'product_line' => 1, // Profile 6
                'image' => $fileFarmscapeAnthracite,
                'colour' => $colourFarmscapeAnthracite,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Profile 6 Sheet 2440mm
        DB::table('materials')->insert([
            [
                'name' => $farmscape_2440mm,
                'product_line' => 1, // Profile 6
                'image' => $fileFarmscapeAnthracite,
                'colour' => $colourFarmscapeAnthracite,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Profile 6 Sheet 2750mm
        DB::table('materials')->insert([
            [
                'name' => $farmscape_2750mm,
                'product_line' => 1, // Profile 6
                'image' => $fileFarmscapeAnthracite,
                'colour' => $colourFarmscapeAnthracite,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Profile 6 Sheet 2900mm
        DB::table('materials')->insert([
            [
                'name' => $farmscape_2900mm,
                'product_line' => 1, // Profile 6
                'image' => $fileFarmscapeAnthracite,
                'colour' => $colourFarmscapeAnthracite,
                'shop_url' => 'profile-6-uk',
            ],
        ]);

        // Profile 6 Sheet 3050mm
        DB::table('materials')->insert([
            [
                'name' => $farmscape_3050mm,
                'product_line' => 1, // Profile 6
                'image' => $fileFarmscapeAnthracite,
                'colour' => $colourFarmscapeAnthracite,
                'shop_url' => 'profile-6-uk',
            ],
        ]);
    }
}
