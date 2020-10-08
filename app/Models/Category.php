<?php

namespace App\Models;

use App\Http\Traits\TraitsModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use TraitsModel;

    /**
     * Author: Vijay  <1937832819@qq.com>
     * @var array
     */
    protected $fillable = [
        'name', 'keywords', 'slug', 'description', 'sort', 'pid', 'created_at'
    ];

    /**
     * @param array $data
     * @param int $pid
     * @return array
     * Description:获取菜单树结构
     * User: Vijay
     * Date: 2019/5/24
     * Time: 22:38
     */
    public static function getMenuTree(array $data = [], int $pid = 0): array
    {
        $resArr = [];
        foreach ($data as $key => &$val) {
            if ($val['pid'] == $pid) {
                $val['child'] = self::getMenuTree($data, $val['id']);
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
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/07/31
     * Time: 12:47
     * @param bool $isCache
     * @return array|mixed
     */
    public static function cacheCategory($isCache = false)
    {
        if ($isCache == true) {
            $category    = self::getMenuTree(self::query()->orderBy('sort', 'asc')->select('id', 'name', 'pid')->get()->toArray());
            Cache::forever('cache_category', $category);
            $category = Cache::get('cache_category');
        } else {
            $category = Cache::rememberForever('cache_category', function () {
                $category    = self::getMenuTree(self::query()->orderBy('sort', 'asc')->select('id', 'name', 'pid')->get()->toArray());
                return $category;
            });
        }
        return $category;
    }
}
