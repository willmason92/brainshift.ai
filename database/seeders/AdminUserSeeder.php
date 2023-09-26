<?php

namespace Database\Seeders;

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

class AdminUserSeeder extends Seeder
{
    use EncryptedAttribute;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role = Role::findByName('SuperAdmin');

        //START Cygnus = Super Admin
        //create or load user
        $user = User::where('email', Encrypter::encrypt('digital@cygnus.co.uk'))->first();

        if ( empty($user->id) ) {
            $entityId = DB::table('users')->insertGetId([
                'name' => Encrypter::encrypt('Cygnus Admin'),
                'email' => Encrypter::encrypt('digital@cygnus.co.uk'),
                'phone' => Encrypter::encrypt('+441908295750'),
            ]);
            $user = User::where('id', $entityId)->first();
        }

        //assign role
        $user->assignRole($role);

        //END Cygnus = Super Admin

        //START Raheela Hussain (Eternit)  = Super Admin
        //create or load user
        $user = User::where('email', Encrypter::encrypt('raheela.hussain@etexgroup.com'))->first();

        if ( empty($user->id) ) {
            $entityId = DB::table('users')->insertGetId([
                'name' => Encrypter::encrypt('Raheela Hussain'),
                'email' => Encrypter::encrypt('raheela.hussain@etexgroup.com'),
            ]);
            $user = User::where('id', $entityId)->first();
        }

        //assign role
        $user->assignRole($role);

        //END Raheela Hussain (Eternit) = Super Admin

        //START Justus Hagemann (Eternit)  = Super Admin
        //create or load user
        $user = User::where('email', Encrypter::encrypt('justus.hagemann@etexgroup.com'))->first();

        if ( empty($user->id) ) {
            $entityId = DB::table('users')->insertGetId([
            'name' => Encrypter::encrypt('Justus Hagemann'),
            'email' => Encrypter::encrypt('justus.hagemann@etexgroup.com'),
            ]);
            $user = User::where('id', $entityId)->first();
        }

        //assign role
        $user->assignRole($role);

        //END Justus Hagemann (Eternit) = Super Admin

        //START Robert Baldwin (Eternit)  = Super Admin
        //create or load user
        $user = User::where('email', Encrypter::encrypt('robert.baldwin@etexgroup.com'))->first();

        if ( empty($user->id) ) {
            $entityId = DB::table('users')->insertGetId([
                'name' => Encrypter::encrypt('Robert Baldwin'),
                'email' => Encrypter::encrypt('robert.baldwin@etexgroup.com'),
            ]);
            $user = User::where('id', $entityId)->first();
        }

        //assign role
        $user->assignRole($role);

        //END Robert Baldwin (Eternit) = Super Admin

        //START Sarah Van Campenhout (Eternit)  = Super Admin
        //create or load user
        $user = User::where('email', Encrypter::encrypt('sarah.van.campenhout@etexgroup.com'))->first();

        if ( empty($user->id) ) {
            $entityId = DB::table('users')->insertGetId([
                'name' => Encrypter::encrypt('Sarah Van Campenhout'),
                'email' => Encrypter::encrypt('sarah.van.campenhout@etexgroup.com'),
            ]);
            $user = User::where('id', $entityId)->first();
        }

        //assign role
        $user->assignRole($role);

        //END Sarah Van Campenhout (Eternit) = Super Admin

    }
}
