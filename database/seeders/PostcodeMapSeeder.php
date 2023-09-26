<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class PostcodeMapSeeder extends Seeder
{
    /**
     * @var array
     */
    private array $regions;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->makeRegionsArray();
        $csvFile = fopen(base_path('database/data/postcodes_official.csv'), 'r');

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            if (! $firstline) {
                if ($data['4'] === '' || $data['4'] === ' ' || is_null($data['4'])) {
                    continue;
                }
                \DB::table('postcode_map')->insert([
                    'outcode' => $data['0'],
                    'region_id' => $data['4'],
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }

    /**
     * @return void
     */
    private function makeRegionsArray(): void
    {
        $arr = [];
        $regions = Region::all();

        foreach ($regions as $region) {
            $arr[$region->region_name] = $region->id;
        }
        $this->regions = $arr;
    }
}
