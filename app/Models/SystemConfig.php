<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemConfig extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'key', 'value', 'type', 'status', 'created_at'
    ];

    /**
     * @var array
     */
    public static $ConfigTypeList = [
        0 => '基础配置',
        1 => '微信配置',
        2 => '小程序配置'
    ];

    /**
     * 所有的键
     * @var array
     */
    public static $keysList = [
        'site_name',
        'site_url',
        'site_logo',
        'site_icp',
        'site_tongji',
        'site_copyright',
        'site_co_name',
        'address',
        'map_lat',
        'map_lng',
        'site_phone',
        'site_email',
        'site_qq',
        'site_wechat',
        'seo_title',
        'site_seo_keywords',
        'site_seo_description'
    ];

    /**
     * Description:获取配置名称
     * User: Vijay
     * Date: 2019/6/13
     * Time: 23:14
     * @return mixed
     */
    public function getConfigTypeNameAttribute()
    {
        return self::$ConfigTypeList[$this->config_type];
    }

    /**
     * Description:获取缓存
     * User: Vijay
     * Date: 2019/11/10
     * Time: 14:24
     * @param null $key
     * @param bool $isCache
     * @return mixed|string
     */
    public static function getConfigCache($key = null, $isCache = true)
    {
        if (!in_array($key, self::$keysList)) {
            return '';
        }
        $val = Cache::get($key);
        if ($isCache === false || !$val) {
            $info = self::where('key', $key)->select('value')->first();
            if (empty($info)) {
                return '';
            }
            $val = $info->value;
            Cache::put($key, $info->value);
        }
        return $val;
    }

    /**
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/09/14
     * Time: 10:52
     * @param bool $isCache
     * @return \Illuminate\Support\Collection|mixed
     */
    public static function getConfigList($isCache = true)
    {
        $key  = 'system_configs';
        $data = Cache::get($key);
        if ($isCache === false || !$data) {
            $data = self::query()
                ->where('status', 1)
                ->pluck('value','key')->toArray();
            Cache::put($key, $data);
        }
        return $data;
    }
}
