<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
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
                'name'        => 'php',
                'slug'        => 'php',
                'keywords'    => 'php',
                'description' => '关于PHP的文章',
                'sort'        => 1,
                'pid'         => 0,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  =>  date('Y-m-d H:i:s'),
                'deleted_at'  => null,
            ],
            [
                'name'        => 'linux',
                'slug'        => 'linux',
                'keywords'    => 'linux',
                'description' => 'linux相关文章',
                'sort'        => 1,
                'pid'         => 0,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  =>  date('Y-m-d H:i:s'),
                'deleted_at'  => null,
            ],
        ];
        DB::table('categories')->insert($data);
    }
}
