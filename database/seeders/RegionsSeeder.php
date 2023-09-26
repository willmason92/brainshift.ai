<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RegionsSeeder extends Seeder
{
    /**
     * @var array
     */
    private array $users;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $found = [];
        $csvFile = fopen(base_path('database/data/postcodes.csv'), 'r');

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            if (! $firstline) {
                if (! in_array($data['4'], $found)) {
                    if ($data['2'] === '' || is_null($data['2'])) {
                        continue;
                    }
                    $found[] = $data['4'];
                    $user = $this->makeAdminUser($data);
                    \DB::table('regions')->insert([
                        'region_name' => $data['4'],
                        'user_id' => $user->id,
                    ]);
                }
            }
            $firstline = false;
        }
        fclose($csvFile);
    }

    /**
     * @param  array  $data
     * @return User
     */
    private function makeAdminUser(array $data): User
    {
        $user = ['name' => $data['2'], 'phone' => $data['3']];
        $user['email'] = strtolower(
            explode(' ', $user['name'])[0].'.'.explode(' ', $user['name'])[1].
            '@myeternit.local'
        );
        $user = User::create($user);
        $user->assignRole(Role::findByName('Admin'));

        return $user;
    }
}
