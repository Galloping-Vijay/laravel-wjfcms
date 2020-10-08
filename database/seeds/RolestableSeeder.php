<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolestableSeeder extends Seeder
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
                'name' => 'admin',
                'description' => '超级管理员',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'own',
                'description' => '网站拥有者',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'test',
                'description' => '测试',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];
        DB::table('roles')->insert($data);

        $rolesData = [
            [
                'role_id' => '1',
                'model_type' => 'App\Models\Admin',
                'model_id' => '1',
            ],
        ];
        DB::table('model_has_roles')->insert($rolesData);
        $rolePerData = [];
        for ($i = 1; $i <= 82; $i++) {
            $rolePerData[] = [
                'role_id' => '1',
                'permission_id' => $i,
            ];
        }
        DB::table('role_has_permissions')->insert($rolePerData);
    }
}
