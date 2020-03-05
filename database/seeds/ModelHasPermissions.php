<?php

use Illuminate\Database\Seeder;

class ModelHasPermissions extends Seeder
{
    /**
     * //如果根据角色来管理权限,最好不要运行这个迁移文件
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       /* $data = [];
        for ($i = 1; $i < 28; $i++) {
            $data[] = [
                'permission_id' => $i,
                'model_type' => 'App\Models\Admin',
                'model_id' => 1,
            ];
        }
        \Illuminate\Support\Facades\DB::table('model_has_permissions')->insert($data);*/
    }
}
