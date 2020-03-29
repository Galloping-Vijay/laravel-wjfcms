<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'id' => 1,
            'username' => 'Vijay',
            'account' => '13000000000',
            'password' => Hash::make(123456),
        ];
        DB::table('admins')->insert($data);
    }
}
