<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Permission as BasePermission;

class Permissions extends BasePermission
{
    use SoftDeletes;

    /**
     * Author: Vijay  <1937832819@qq.com>
     * @var array
     */
    protected $fillable = [
        'name', 'guard_name', 'sort_order', 'url', 'level', 'icon', 'parent_id', 'display_menu', 'created_at'
    ];

    /**
     * Author: Vijay  <1937832819@qq.com>
     * @var array
     */
    public static $guard_name_list = [
        'admin' => '后台权限组',
        'home' => '前台权限组'
    ];

    /**
     * @var array
     */
    public static $display_menu = [
        0 => '不显示',
        1 => '显示'
    ];

    /**
     * @var array
     */
    public static $delete = [
        0 => '正常',
        1 => '软删除',
        2 => '全部'
    ];

    /**
     * @param array $data
     * @param int $parent_id
     * @return array
     * Description:获取菜单树结构
     * User: VIjay
     * Date: 2019/5/24
     * Time: 22:38
     */
    public static function getMenuTree(array $data = [], int $parent_id = 0): array
    {
        $resArr = [];
        foreach ($data as $key => &$val) {
            if ($val['display_menu'] == 0) {
                continue;
            }
            if ($val['parent_id'] == $parent_id) {
                $val['child'] = self::getMenuTree($data, $val['id']);
                $resArr[] = $val;
            }
        }
        return $resArr;
    }

    /**
     * Instructions:
     * Author: Vijay  <1937832819@qq.com>
     * Time: 2019/4/22 14:44
     * @param array $data
     * @param int $parent_id
     * @param int $role_id
     * @return array
     */
    public static function getAuthTree(array $data = [], int $parent_id = 0, int $role_id = 0): array
    {
        static $roleList = [];
        if (empty($roleList)) {
            $roleList = RoleHasPermissions::where('role_id', $role_id)->get(['id', 'permission_id']);
        }
        $resArr = [];
        foreach ($data as $key => &$val) {
            if ($val['parent_id'] == $parent_id) {
                $val['value'] = $val['id'];
                if (in_array($val['id'], $roleList)) {
                    $val['checked'] = true;
                } else {
                    $val['checked'] = false;
                }
                $val['list'] = self::getAuthTree($data, $val['id']);
                if (empty($val['list'])) unset($val['list']);
                $resArr[] = $val;
            }
        }
        return $resArr;
    }

    /**
     * Instructions:获取下拉菜单
     * Author: Vijay  <1937832819@qq.com>
     * Time: 2019/4/17 15:45
     * @param $data
     * @param int $parent_id
     * @param int $level
     * @return array
     */
    public static function array2level(array $data = [], int $parent_id = 0, int $level = 0): array
    {
        static $list = [];
        $jiari = '&nbsp;&nbsp;&nbsp;└─';
        foreach ($data as $key => $val) {
            if ($val['parent_id'] == $parent_id) {
                $val['level'] = $level;
                for ($i = 1; $i <= $level; $i++) {
                    $val['name'] = $jiari . $val['name'];
                }
                $list[] = $val;
                self::array2level($data, $val['id'], $level + 1);
            }
        }
        return $list;
    }
}
