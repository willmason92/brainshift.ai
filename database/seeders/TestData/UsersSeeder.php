<?php

namespace Database\Seeders\TestData;

use App\Models\ExpertType;
use App\Models\Sector;
use App\Models\Speciality;
use App\Models\User;
use Carbon\Carbon;
use ESolution\DBEncryption\Encrypter;
use ESolution\DBEncryption\Traits\EncryptedAttribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    use EncryptedAttribute;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //START Dan Walker = Super Admin
        //create user
        $entityId = DB::table('users')->insertGetId([
            'name' => Encrypter::encrypt('Dan Walker'),
            'email' => Encrypter::encrypt('dan@cygnus.co.uk'),
            'phone' => Encrypter::encrypt('+441234567890'),
        ]);

        //assign role
        $role = Role::findByName('SuperAdmin');
        $user = User::where('id', $entityId)->first();
        $user->assignRole($role);

        //END Dan Walker = Super Admin

        //START Homer Zuckerburg = Farmer
        //create user
        $entityId = DB::table('users')->insertGetId([
            'name' => Encrypter::encrypt('Homer Zuckerman'),
            'email' => Encrypter::encrypt('eternit+farmer@cygnus.co.uk'),
            'phone' => Encrypter::encrypt('+441234567890'),
        ]);

        //assign role
        $role = Role::findByName('Farmer');
        $user = User::where('id', $entityId)->first();
        $user->assignRole($role);

        //add location
        $location_id = DB::table('locations')->insertGetId([
            'entity_id' => $entityId,
            'entity_type' => User::class,
            'name' => 'Zuckerman Farm',
            'street' => '635 Bay Road',
            'city' => 'Brooklin',
            'postcode' => 'ME 04616',
            'country' => 'United States',
            'latitude' => 44.30179,
            'longitude' => -68.56074,
            'sizeradius' => 56.9645,
            'sizeradiustype' => 2,
        ]);

        //Farm Sector
        $sId = Sector::where('name', 'Beef')->first()?->id;
        if ($sId) {
            DB::table('sector_user_expert')->insert([
                'expert_id' => null,
                'user_id' => $entityId,
                'sector_id' => $sId,
            ]);
        }

        //END  Homer Zuckerburg = Farmer

        //START Bob the Builder = Installer
        //create user
        $entityId = DB::table('users')->insertGetId([
            'name' => Encrypter::encrypt('Bob the Builder'),
            'email' => Encrypter::encrypt('eternit+installer@cygnus.co.uk'),
            'phone' => Encrypter::encrypt('+447788123456'),
        ]);

        //assign role
        $role = Role::findByName('Installer');
        $user = User::where('id', $entityId)->first();
        $user->assignRole($role);

        //add expert logo
        $expertLogo = DB::table('files')->insertGetId([
            'filename' => 'Bob_the_builder.jpg',
            'path' => 'test/company-logo',
            'mimetype' => 0,
        ]);

        //add expert
        $expertId = DB::table('experts')->insertGetId([
            'user_id' => $entityId,
            'company_name' => 'Bobs Builders',
            'company_email' => 'eternit+bob.builder@cygnus.co.uk',
            'nationwide' => 1,
            'description' => 'Bob the Builder is an Installer working for Bobs Builders.',
            'region_id' => null,
            'expert_url' => null,
            'logo' => $expertLogo,
            'expert_type_id' => ExpertType::where('name', 'Installers')->first()?->id,
            'created_at' => Carbon::parse('1/10/2022'),
            'updated_at' => Carbon::parse('1/10/2022'),
        ]);

        //add Expert Specialities
        $data = [];
        $id = Speciality::where('name', 'New Builds')->first()?->id;
        if ($id) {
            $data[] = ['expert_id' => $expertId, 'speciality_id' => $id];
        }
        $id = Speciality::where('name', 'Refurbishment')->first()?->id;
        if ($id) {
            $data[] = ['expert_id' => $expertId, 'speciality_id' => $id];
        }
        DB::table('expert_speciality')->insert($data);

        //Expert Sectors
        $sId = Sector::where('name', 'Beef')->first()?->id;
        if ($sId) {
            DB::table('sector_user_expert')->insert([
                'expert_id' => $expertId,
                'user_id' => null,
                'sector_id' => $sId,
            ]);
        }
        $sId = Sector::where('name', 'Dairy')->first()?->id;
        if ($sId) {
            DB::table('sector_user_expert')->insert([
                'expert_id' => $expertId,
                'user_id' => null,
                'sector_id' => $sId,
            ]);
        }

        //add location
        $location_id = DB::table('locations')->insertGetId([
            'entity_id' => $entityId,
            'entity_type' => User::class,
            'name' => 'Bobs Builders',
            'street' => '1 Bobsville',
            'city' => 'Fixham Harbour',
            'postcode' => 'FH1 8OB',
            'country' => 'UK',
            'latitude' => 50.73695,
            'longitude' => 0.24973,
            'sizeradius' => null,
            'sizeradiustype' => null,
        ]);

        //END Bob the Builder = Installer

        //START A King Installer = Installer
        //create user
        $entityId = DB::table('users')->insertGetId([
            'name' => Encrypter::encrypt('A King Installer'),
            'email' => Encrypter::encrypt('eternit+installer2@cygnus.co.uk'),
            'phone' => Encrypter::encrypt('+441225705918'),
        ]);

        //assign role
        $user = User::where('id', $entityId)->first();
        $user->assignRole($role);

        //add expert logo
        $expertLogo = DB::table('files')->insertGetId([
            'filename' => 'A_King.png',
            'path' => 'test/company-logo',
            'mimetype' => 0,
        ]);

        //add expert
        $expertId = DB::table('experts')->insertGetId([
            'user_id' => $entityId,
            'company_name' => 'A L King Roofing Merchant Ltd',
            'company_email' => 'eternit+a.king@cygnus.co.uk',
            'nationwide' => 0,
            'description' => 'Roofers in Wiltshire & Surrounding Areas.',
            'region_id' => null,
            'expert_url' => 'https://www.alkingroofing.co.uk/
            ',
            'logo' => $expertLogo,
            'expert_type_id' => ExpertType::where('name', 'Installers')->first()?->id,
            'created_at' => Carbon::parse('2/10/2022'),
            'updated_at' => Carbon::parse('2/10/2022'),
        ]);

        //add Expert Specialities
        $data = [];
        $id = Speciality::where('name', 'New Builds')->first()?->id;
        if ($id) {
            $data[] = ['expert_id' => $expertId, 'speciality_id' => $id];
        }
        $id = Speciality::where('name', 'Refurbishment')->first()?->id;
        if ($id) {
            $data[] = ['expert_id' => $expertId, 'speciality_id' => $id];
        }
        $id = Speciality::where('name', 'Asbestos Removal')->first()?->id;
        if ($id) {
            $data[] = ['expert_id' => $expertId, 'speciality_id' => $id];
        }
        DB::table('expert_speciality')->insert($data);

        //add location
        $location_id = DB::table('locations')->insertGetId([
            'entity_id' => $entityId,
            'entity_type' => User::class,
            'name' => 'A L King Roofing Merchant Ltd',
            'street' => 'Hampton Park W',
            'city' => 'Bath',
            'postcode' => 'SN12 6EZ',
            'country' => 'UK',
            'latitude' => 51.29368,
            'longitude' => -1.94078,
            'sizeradius' => 34,
            'sizeradiustype' => 0,
        ]);

        //END A King Installer = Installer

        //START SIG Roofing = Installer
        //create user
        $entityId = DB::table('users')->insertGetId([
            'name' => Encrypter::encrypt('Cig Installer'),
            'email' => Encrypter::encrypt('eternit+installer3@cygnus.co.uk'),
            'phone' => Encrypter::encrypt('+441636611880'),
        ]);

        //assign role
        $user = User::where('id', $entityId)->first();
        $user->assignRole($role);

        //add expert logo
        $expertLogo = DB::table('files')->insertGetId([
            'filename' => 'sig.png',
            'path' => 'test/company-logo',
            'mimetype' => 0,
        ]);

        //add expert
        $expertId = DB::table('experts')->insertGetId([
            'user_id' => $entityId,
            'company_name' => 'SIG Roofing',
            'company_email' => 'eternit+sig@cygnus.co.uk',
            'nationwide' => 1,
            'description' => 'SIG Roofing has everything you need under one roof. Whatever you need for your roof, with '
                .'over 100 branches nationwide, SIG Roofing’s got it covered for you.',
            'region_id' => null,
            'expert_url' => 'https://www.sigroofing.co.uk/',
            'logo' => $expertLogo,
            'expert_type_id' => ExpertType::where('name', 'Installers')->first()?->id,
            'created_at' => Carbon::parse('3/10/2022'),
            'updated_at' => Carbon::parse('3/10/2022'),
        ]);

        //add Expert Specialities
        $data = [];
        $id = Speciality::where('name', 'New Builds')->first()?->id;
        if ($id) {
            $data[] = ['expert_id' => $expertId, 'speciality_id' => $id];
        }
        DB::table('expert_speciality')->insert($data);

        //add location
        $location_id = DB::table('locations')->insertGetId([
            'entity_id' => $entityId,
            'entity_type' => User::class,
            'name' => 'SIG Roofing Rotherham',
            'street' => 'Sheffield Road',
            'city' => 'Rotherham',
            'postcode' => 'S60 1DA',
            'country' => 'UK',
            'latitude' => 53.42626,
            'longitude' => -1.35991,
            'sizeradius' => null,
            'sizeradiustype' => null,
        ]);

        //END SIG Roofing = Installer
    }
}
