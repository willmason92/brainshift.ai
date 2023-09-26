<?php

namespace Database\Seeders\TestData;

use App\Models\Expert;
use App\Models\ExpertType;
use App\Models\Speciality;
use Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpertsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //iterate each expert type except Installers
        $faker = Faker\Factory::create('en_GB');
        foreach (ExpertType::whereNotIn('name', ['Installers', 'Associations'])->get() as $eType) {
            for ($i = 1; $i <= $faker->numberBetween(1, 5); $i++) {
                //dump($eType);
                $nationwide = ($faker->numberBetween(0, 3) === 1);
                $date = $faker->dateTimeBetween('-60 days', '-30 days');
                $domain = $faker->domainName;
                $company = $faker->company;
                if (strstr($eType, 'Eternit')) {
                    $logo = DB::table('files')->insertGetId([
                        'filename' => 'eternit-sales.svg',
                        'path' => 'experts/company-logo',
                        'mimetype' => 0,
                    ]);
                } else {
                    $logo = DB::table('files')->insertGetId([
                        'filename' => $eType->name.'_'.$i.'.webp',
                        'path' => 'test/company-logo',
                        'mimetype' => 0,
                    ]);
                }
                $data = [
                    'user_id' => null,
                    'company_name' => $company,
                    'company_email' => strtolower(preg_replace('/[^A-Za-z0-9]+/', '.', $company)).'@'.$domain,
                    'nationwide' => $nationwide,
                    'description' => $faker->text,
                    'region_id' => null,
                    'expert_url' => $domain,
                    'logo' => $logo,
                    'expert_type_id' => $eType->id,
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
                $expertId = DB::table('experts')->insertGetId($data);

                //add Expert Specialities
                //Experts don't have specialities
//                $sData = [];
//                $specialities = Speciality::where('id', '>', 0)->pluck('id')->toArray();
//                foreach ($faker->randomElements($specialities, $faker->numberBetween(1, 3)) as $x) {
//                    $sData[] = ['expert_id' => $expertId, 'speciality_id' => $x];
//                }
//                DB::table('expert_speciality')->insert($sData);

                //add location
                $radiusType = $nationwide ? null : $faker->numberBetween(0, 1);
                $radiusSize = $nationwide ? null : $faker->randomFloat(2,
                    ($radiusType ? 20 : 32),
                    ($radiusType ? 82 : 132)
                );
                $lData = [
                    'entity_id' => $expertId,
                    'entity_type' => Expert::class,
                    'name' => $company,
                    'street' => $faker->streetAddress,
                    'city' => $faker->city,
                    'postcode' => $faker->postcode,
                    'country' => 'UK',
                    'latitude' => $faker->latitude(50.76196, 54.94329),
                    'longitude' => $faker->longitude(-3.32474, 0.84453),
                    'sizeradius' => $radiusSize,
                    'sizeradiustype' => $radiusType,
                ];
                DB::table('locations')->insert($lData);
            }
        }
    }
}
