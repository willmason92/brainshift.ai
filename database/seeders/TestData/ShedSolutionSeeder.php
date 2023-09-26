<?php

namespace Database\Seeders\TestData;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShedSolutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = DB::table('files')->insertGetId([
            'filename' => 'test-title-1-1671138137.jpg',
            'path' => 'test/shed-solutions',
            'mimetype' => 0,
        ]);

        $shedSolution = DB::table('shed_solutions')->insertGetId([
            'project_type_id' => 0, // new build
            'project_file_id' => $file,
            'sector_id' => 1,
            'length' => 40,
            'width' => 40,
            'size_type_id' => 0, // meter
            'user_id' => 2,
            'title' => 'Test Title 1',
            'age' => 1,
            'responsibility_id' => 0, // Installer
        ]);

        DB::table('shed_solution_goals')->insert([
            'shed_solution_id' => $shedSolution,
            'goal_id' => 1,
        ]);

        DB::table('shed_solution_goals')->insert([
            'shed_solution_id' => $shedSolution,
            'goal_id' => 3,
        ]);

        DB::table('requests')->insert([
            'request_status' => 0, // New request
            'user_id' => 2,
            'installer_id' => 1,
            'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed posuere nisl in diam aliquam sollicitudin. Donec dignissim consequat libero, et blandit leo pellentesque sollicitudin.', // Dairy
            'contact_phone' => '+441234567890',
            'contact_email' => 'test@test.com',
            'project_type' => '0',
            'shed_solution_id' => $shedSolution,
        ]);

        $file = DB::table('files')->insertGetId([
            'filename' => 'test-title-2-1671138138.jpg',
            'path' => 'test/shed-solutions',
            'mimetype' => 0,
        ]);

        $shedSolution = DB::table('shed_solutions')->insertGetId([
            'project_type_id' => 1, // new build
            'project_file_id' => $file,
            'sector_id' => 2,
            'length' => 40,
            'width' => 40,
            'size_type_id' => 1, // meter
            'user_id' => 2,
            'title' => 'Test Title 2',
            'age' => 5,
            'responsibility_id' => 1, // Installer
        ]);

        DB::table('shed_solution_goals')->insert([
            'shed_solution_id' => $shedSolution,
            'goal_id' => 2,
        ]);

        DB::table('shed_solution_goals')->insert([
            'shed_solution_id' => $shedSolution,
            'goal_id' => 3,
        ]);

        DB::table('requests')->insert([
            'request_status' => 0, // New request
            'user_id' => 2,
            'installer_id' => 1,
            'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed posuere nisl in diam aliquam sollicitudin. Donec dignissim consequat libero, et blandit leo pellentesque sollicitudin.', // Dairy
            'contact_phone' => '+441234567890',
            'contact_email' => 'test@test.com',
            'project_type' => '0',
            'shed_solution_id' => $shedSolution,
        ]);

        $file = DB::table('files')->insertGetId([
            'filename' => 'test-title-3-1671138139.jpg',
            'path' => 'test/shed-solutions',
            'mimetype' => 0,
        ]);

        $shedSolution = DB::table('shed_solutions')->insertGetId([
            'project_type_id' => 0, // refurb
            'project_file_id' => $file,
            'sector_id' => 3,
            'length' => 40,
            'width' => 40,
            'size_type_id' => 1, // meter
            'user_id' => 2,
            'title' => 'Test Title 2',
            'age' => 7,
            'responsibility_id' => 2, // Installer
        ]);

        DB::table('shed_solution_reasons')->insert([
            'shed_solution_id' => $shedSolution,
            'reason_id' => 2,
        ]);

        DB::table('shed_solution_reasons')->insert([
            'shed_solution_id' => $shedSolution,
            'reason_id' => 4,
        ]);

        DB::table('shed_solution_reasons')->insert([
            'shed_solution_id' => $shedSolution,
            'reason_id' => 5,
        ]);

        DB::table('requests')->insert([
            'request_status' => 0, // New request
            'user_id' => 2,
            'installer_id' => 1,
            'message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed posuere nisl in diam aliquam sollicitudin. Donec dignissim consequat libero, et blandit leo pellentesque sollicitudin.', // Dairy
            'contact_phone' => '+441234567890',
            'contact_email' => 'test@test.com',
            'project_type' => '1',
            'sector_id' => '4',
            //            'shed_solution_id' => $shedSolution,
        ]);
    }
}
