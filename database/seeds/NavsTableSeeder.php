<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NavsTableSeeder extends Seeder
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
                'name' => '文章归档',
                'sort' => 0,
                'url' => '/archive',
                'pid' => 0,
                'target' => '_self',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'name' => '有些话',
                'sort' => 0,
                'url' => '/chat',
                'pid' => 0,
                'target' => '_self',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'name' => '文档',
                'sort' => 1,
                'url' => 'https://www.choudalao.com/static/docs/_book/installation.html',
                'pid' => 0,
                'target' => '_blank',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
        ];
        DB::table('navs')->insert($data);
    }
}
