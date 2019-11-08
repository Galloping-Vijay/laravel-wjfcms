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
                'name' => '有些话',
                'url' => '/chat',
                'pid' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'name' => '文档',
                'sort' => 1,
                'url' => 'https://www.kancloud.cn/wjf19940211/laravel-wjfcms/1132641',
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
