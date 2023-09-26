<?php

namespace Database\Seeders;

use App\Models\ExpertType;
use App\Models\Sector;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = fopen(base_path('database/data/myeternit_experts.csv'), 'r');

        $firstline = true;

        //get the types and sectors
        $eTypes = ExpertType::pluck('name', 'id')->toArray();
        $sectors = Sector::pluck('name', 'id')->toArray();

        /*
         * 0 = name
         * 1 = type
         * 2 = sector
         * 3 = email
         * 4 = logo
         * 5 = description
         * 6 = url
         * 7 = street
         * 8 = city
         * 9 = postcode
         * 10 = country
         * 11 = lat
         * 12 = lng
         */
        while (($data = fgetcsv($csvFile, 5000, ',')) !== false) {
            if (! $firstline) {
                //required
                if (empty($data[0]) || empty($data[1]) || empty($data[2]) || empty($data[4]) || empty($data[6])) {
                    continue;
                }

                //nationwide
                $nationwide = (empty($data[7]) || empty($data[9]) || empty($data[11]) || empty($data[12]));

                //expert type
                if (($expertTypeId = array_search($data['1'], $eTypes)) === false) {
                    continue;
                }

                //sectors
                $sectorIds = [];
                if ($data['2'] == 'All') {
                    $sectorIds = array_keys($sectors);
                } else {
                    foreach (explode('|', $data['2']) as $a) {
                        if (($sId = array_search(trim($a), $sectors)) !== false) {
                            $sectorIds[] = $sId;
                        }
                    }
                }
                if (empty($sectorIds)) {
                    continue;
                }

                //logo
                $logoId = DB::table('files')->insertGetId([
                    'filename' => $data[4],
                    'path' => 'experts/company-logo',
                    'mimetype' => 0,
                ]);

                $insertData = [
                    'user_id' => null,
                    'company_name' => $data[0],
                    'company_email' => empty($data[3]) ? null : $data[3],
                    'nationwide' => (int) $nationwide,
                    'description' => $data[5],
                    'region_id' => null,
                    'expert_url' => $data[6],
                    'logo' => $logoId,
                    'expert_type_id' => $expertTypeId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $expertId = DB::table('experts')->insertGetId($insertData);

                //sectors
                $insertData = [];
                foreach ($sectorIds as $sId) {
                    $insertData[] = [
                        'expert_id' => $expertId,
                        'user_id' => null,
                        'sector_id' => $sId,
                    ];
                }

                DB::table('sector_user_expert')->insert($insertData);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
