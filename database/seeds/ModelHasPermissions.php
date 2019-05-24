<?php

use Illuminate\Database\Seeder;

class ModelHasPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        for ($i = 1; $i < 28; $i++) {
            $data[] = [
                'permission_id' => $i,
                'model_type' => 'App\Models\Admin',
                'model_id' => 1,
            ];
        }
        \Illuminate\Support\Facades\DB::table('model_has_permissions')->insert($data);
    }
}
