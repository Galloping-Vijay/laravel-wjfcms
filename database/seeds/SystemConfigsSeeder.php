<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemConfigsSeeder extends Seeder
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
                'title' => '网站名称',
                'key' => 'site_name',
                'value' => 'laravel-wjfcms',
                'type' => 'text',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'title' => '网站地址',
                'key' => 'site_url',
                'value' => 'www.xxx.com',
                'type' => 'text',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'title' => 'LOGO',
                'key' => 'site_logo',
                'value' => '',
                'type' => 'text',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'title' => '联系电话',
                'key' => 'site_phone',
                'value' => '130000000000',
                'type' => 'text',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'title' => '联系邮箱',
                'key' => 'site_email',
                'value' => '10086@qq.com',
                'type' => 'text',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'title' => '联系QQ',
                'key' => 'site_qq',
                'value' => '10000',
                'type' => 'text',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'title' => '联系微信',
                'key' => 'site_wechat',
                'value' => 'xxx',
                'type' => 'text',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'title' => '备案信息',
                'key' => 'site_icp',
                'value' => 'xxx',
                'type' => 'text',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'title' => '公司/工作室名称',
                'key' => 'site_co_name',
                'value' => 'xxx有限公司',
                'type' => 'textarea',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'title' => '联系地址',
                'key' => 'address',
                'value' => 'xxx',
                'type' => 'textarea',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'title' => '版权信息',
                'key' => 'site_copyright',
                'value' => 'xxx',
                'type' => 'textarea',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'title' => '统计代码',
                'key' => 'site_tongji',
                'value' => '',
                'type' => 'textarea',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'title' => '地图lat',
                'key' => 'map_lat',
                'value' => '23.029759',
                'type' => 'textarea',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'title' => '地图lng',
                'key' => 'map_lng',
                'value' => '113.752114',
                'type' => 'textarea',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'title' => 'SEO标题',
                'key' => 'seo_title',
                'value' => '臭大佬',
                'type' => 'text',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'title' => 'SEO关键字',
                'key' => 'site_seo_keywords',
                'value' => '臭大佬',
                'type' => 'textarea',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'title' => 'SEO描述',
                'key' => 'site_seo_description',
                'value' => '臭大佬',
                'type' => 'textarea',
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];
        DB::table('system_configs')->insert($data);
    }
}
