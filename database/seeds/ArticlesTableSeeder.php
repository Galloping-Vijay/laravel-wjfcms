<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Description:
     * User: Vijay
     * Date: 2019/6/25
     * Time: 21:08
     */
    public function run()
    {
        $data = [
            [
                'category_id' => 1,
                'title' => '记录生活，记录成长',
                'author' => 'Vijay',
                'slug' => 'php',
                'content' => '&lt;p&gt;laravel-wjfcms是基于laravel及layui开发的后台管理系统。&lt;/p&gt;',
                'markdown'=>'laravel-wjfcms是基于laravel及layui开发的后台管理系统。',
                'description'=>'laravel-wjfcms是基于laravel及layui开发的后台管理系统。',
                'keywords' => 'laravel',
                'cover' => '',
                'is_top' => 1,
                'status' => 1,
                'click' => 666,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ]
        ];
        DB::table('articles')->insert($data);
    }
}
