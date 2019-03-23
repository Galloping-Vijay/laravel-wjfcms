<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => Str::random(10),
                'email' => '1@gmail.com',
                'password' => bcrypt('12345'),
            ],
            [
                'name' => Str::random(10),
                'email' => '2@gmail.com',
                'password' => bcrypt('12345'),
            ]
        ]);
    }
}
