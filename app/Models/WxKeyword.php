<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WxKeyword extends Model
{
    /**
     * Author: Vijay  <1937832819@qq.com>
     * @var array
     */
    protected $fillable = [
        'key_name', 'key_value', 'sort', 'status', 'created_at',
    ];

    /**
     * @var null
     */
    protected $error = null;

    /**
     * Description:
     * User: Vijay
     * Date: 2020/3/3
     * Time: 23:05
     * @return WxKeyword
     */
    public static function initialization()
    {
        return new static();
    }

    /**
     * Description:返回错误提示信息
     * User: Vijay
     * Date: 2019/6/10
     * Time: 21:42
     * @return null
     */
    public function getError()
    {
        return $this->error;
    }
}
