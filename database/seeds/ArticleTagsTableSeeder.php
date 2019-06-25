<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleTagsTableSeeder extends Seeder
{
    /**
     * Description:
     * User: Vijay
     * Date: 2019/6/25
     * Time: 21:16
     */
    public function run()
    {
        $data = [
            [
                'article_id' => 1,
                'tag_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
        ];
        DB::table('article_tags')->insert($data);
    }
}
