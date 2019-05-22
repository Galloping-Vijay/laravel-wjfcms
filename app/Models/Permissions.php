<?php

namespace App\Models;

class Permissions extends Base
{

    /**
     * Instructions:
     * Author: Vijay  <1937832819@qq.com>
     * Time: 2019/4/15 15:59
     * @param string $role_ids 例:1,2
     * @param int $pid
     * @param int $display_menu
     * @return array
     */
    public static function menu(string $role_ids = '', int $pid = 0, int $display_menu = 1): array
    {
        if (empty($role_ids)) return [];
        $sql = "select p.id,p.name,p.description,p.level,p.parent_id,p.icon from " . config('database.prefix') . "permission_role pr left join " . config('database.prefix') . "permissions p on p.id=pr.permission_id where pr.role_id IN (" . $role_ids . ") and display_menu=" . $display_menu . " order by sort_order asc";
        $resSql = Db::query($sql);
        if (empty($resSql)) return [];
        $resArr = self::getSubclass($resSql, $pid);
        return $resArr;
    }

    /**
     * Instructions:获取Tree结构
     * Author: Vijay  <1937832819@qq.com>
     * Time: 2019/4/15 15:59
     * @param array $data
     * @param int $parent_id
     * @return array
     */
    public static function getSubclass(array $data = [], int $parent_id = 0): array
    {
        $resArr = [];
        foreach ($data as $key => &$val) {
            if ($val['parent_id'] == $parent_id) {
                $val['child'] = self::getSubclass($data, $val['id']);
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
    public static function getAuthTree(array $data = [], int $parent_id = 0, int $role_id = 0)
    {
        static $roleList = [];
        if (empty($roleList)) {
            $roleList = PermissionRole::where('role_id', $role_id)->column('permission_id', 'id');
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
     * Instructions:
     * Author: Vijay  <1937832819@qq.com>
     * Time: 2019/4/17 15:45
     * @param $data
     * @param int $parent_id
     * @param int $level
     * @return array
     */
    public static function array2level(array $data = [], int $parent_id = 0, int $level = 0)
    {
        static $list = [];
        $jiari = '&nbsp;&nbsp;&nbsp;└─';
        foreach ($data as $key => $val) {
            if ($val['parent_id'] == $parent_id) {
                $val['level'] = $level;
                for ($i = 1; $i <= $level; $i++) {
                    $val['description'] = $jiari . $val['description'];
                }
                $list[] = $val;
                self::array2level($data, $val['id'], $level + 1);
            }
        }
        return $list;
    }
}
