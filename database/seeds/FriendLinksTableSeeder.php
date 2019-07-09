<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FriendLinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Vijay Blog',
                'url' => 'https://yuemeet.com',
                'sort' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ]
        ];
        DB::table('friend_links')->insert($data);
    }
}
