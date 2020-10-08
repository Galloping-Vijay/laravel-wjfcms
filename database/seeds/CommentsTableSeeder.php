<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentsTableSeeder extends Seeder
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
                'user_id' => 1,
                'type' => 1,
                'pid' => 0,
                'article_id' => 1,
                'status' => 0,
                'content' => 'PHP是世界上最好的语言',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ]
        ];
        DB::table('comments')->insert($data);
    }
}
