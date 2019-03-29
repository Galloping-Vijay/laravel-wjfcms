<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \Illuminate\Support\Facades\DB::table('admins')->insert([
            [
                'name' => 'vijay',
                'email' => '1937832819@qq.com',
                'password' => bcrypt(123456),
            ]
        ]);
    }
}
