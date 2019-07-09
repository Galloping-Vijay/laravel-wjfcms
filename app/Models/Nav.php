<?php

namespace App\Models;

use App\Http\Traits\TraitsModel;
use Illuminate\Database\Eloquent\Model;

class Nav extends Model
{
    use TraitsModel;

    /**
     * Author: Vijay  <1937832819@qq.com>
     * @var array
     */
    protected $fillable = [
        'name', 'pid', 'sort', 'url', 'created_at'
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
}
