<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            //settings always first
            SettingsSeeder::class,

            //Specialities, ExpertType, Sectors, Goals, Reasons
            SpecialitiesSeeds::class,
            ExpertTypeSeeder::class,
            SectorSeeder::class,
            GoalsSeeder::class,
            ReasonsSeeder::class,

            //Roles and Permissions
            RolesPermissionSeeder::class,

            //users, installers, experts (including location seeds)
            TestData\UsersSeeder::class,
            ExpertSeeder::class,
            TestData\ExpertsSeeder::class,

            //Colours and Materials
            ColourSeeder::class,
            MaterialSeeder::class,

            //Shed Solutions
            TestData\ShedSolutionSeeder::class,

            //Farmers Library
            BlogTagsSeeder::class,
            PostTypeSeeder::class,
            FieldSeeder::class,
            FarmersLibrarySeeder::class,

            RegionsSeeder::class,
            PostcodeMapSeeder::class,

            //Article - How To Use App
            ArticleHowToUseAppSeeder::class,
        ]);
    }
}
