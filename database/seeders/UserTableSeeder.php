<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Str;
use Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
            [
                'uuid' =>Str::uuid(),
                'first_name' => 'HCI',
                'last_name' => 'Admin',
                'email'=>'dev@hci.com',
                'role_id'=>1,
                'password' => Hash::make('dev@hci.com')
            ],
            [
                'uuid' =>Str::uuid(),
                'first_name' => 'ADI GmbH',
                'last_name' => 'Admin',
                'email'=>'sa@pt3d.de',
                'role_id'=>2,
                'password' => Hash::make('adi12345#')
            ],
            [
                'uuid' =>Str::uuid(),
                'first_name' => 'ADI GmbH',
                'last_name' => 'Admin',
                'email'=>'dk@pt3d.de',
                'role_id'=>2,
                'password' => Hash::make('adi12345#')
            ],
        ]);
    }
}
