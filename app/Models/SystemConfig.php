<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
