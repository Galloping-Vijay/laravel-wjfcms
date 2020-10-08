<?php

namespace App\Models;

use App\Http\Traits\TraitsModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Nav extends Model
{
    use TraitsModel;

    /**
     * Author: Vijay  <1937832819@qq.com>
     * @var array
     */
    protected $fillable = [
        'name', 'pid', 'sort', 'url', 'target', 'icon', 'created_at'
    ];

    /**
     * 打开方式
     * @var array
     */
    public static $targetList = [
        '_self', '_blank', '_parent', '_top', 'framename'
    ];

    /**
     * Instructions:获取下拉菜单
     * Author: Vijay  <1937832819@qq.com>
     * Time: 2019/4/17 15:45
     * @param $data
     * @param int $pid
     * @param int $level
     * @return array
     */
    public static function array2level(array $data = [], int $pid = 0, int $level = 0): array
    {
        static $list = [];
        $jiari = '&nbsp;&nbsp;&nbsp;└─';
        foreach ($data as $key => $val) {
            if ($val['pid'] == $pid) {
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

    /**
     * Description:获取菜单树结构
     * User: Vijay
     * Date: 2019/7/31
     * Time: 11:43
     * @param array $data
     * @param int $pid
     * @return array
     */
    public static function getMenuTree(array $data = [], int $pid = 0): array
    {
        $resArr = [];
        foreach ($data as $key => &$val) {
            if ($val['pid'] == $pid) {
                $val['child'] = self::getMenuTree($data, $val['id']);
                $resArr[]     = $val;
            }
        }
        return $resArr;
    }

    /**
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/07/31
     * Time: 12:42
     * @param bool $isCache
     * @return array|mixed
     */
    public static function cacheNav($isCache = false)
    {
        if ($isCache == true) {
            $navList    = self::getMenuTree(self::query()->orderBy('sort', 'asc')->get()->toArray());
            Cache::forever('nav_list', $navList);
            $navList = Cache::get('nav_list');
        } else {
            $navList = Cache::rememberForever('nav_list', function () {
                $navList    = self::getMenuTree(self::query()->orderBy('sort', 'asc')->get()->toArray());
                return $navList;
            });
        }
        return $navList;
    }
}
