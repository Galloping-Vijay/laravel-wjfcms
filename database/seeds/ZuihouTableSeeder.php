<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 由于外键关联问题,有些表的数据只能在最后添加,所以,写在这里,避免执行php artisan db:seed时候报错
 * Class ZuihouTableSeeder
 */
class ZuihouTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolesData = [
            [
                'role_id' => '1',
                'model_type' => 'App\Models\Admin',
                'model_id' => '1',
            ],
        ];
        DB::table('model_has_roles')->insert($rolesData);
        $rolePerData = [];
        for ($i = 1; $i <= 100; $i++) {
            $rolePerData[] = [
                'role_id' => '1',
                'permission_id' => $i,
            ];
        }
        DB::table('role_has_permissions')->insert($rolePerData);
    }
}
