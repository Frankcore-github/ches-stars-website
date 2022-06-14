<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->insert([
        //     'username' => 'dapmon',
        //     'usertype' => 'staff',
        //     'password' => Hash::make('dapmon123'),
        // ]);
        // DB::table('users')->insert([
        //     'username' => 'arie',
        //     'usertype' => 'student',
        //     'password' => Hash::make('arie123'),
        // ]);
        
        DB::table('users')->insert([
            'username' => 'admin',
            'usertype' => 'admin',
            'password' => Hash::make('ariesadapha'),
        ]); 

        // DB::table('staff')->insert([
        //     'user_id' => '1',
        //     'first_name' => 'Dapmon',
        //     'middle_name' => 'I',
        //     'last_name' => 'Papang'
        // ]);
        // DB::table('students')->insert([
        //     'user_id' => '2',
        //     'first_name' => 'Arie',
        //     'middle_name' => 'Jones',
        //     'last_name' => 'Chullai',
        //     'class' => 'ten',
        // ]);
    }
}
