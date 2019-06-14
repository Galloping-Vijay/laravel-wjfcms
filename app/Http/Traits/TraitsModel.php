<?php
/**
 * Description:
 * Created by PhpStorm.
 * User: Vijay
 * Date: 2019/6/10
 * Time: 21:39
 */

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;

trait TraitsModel
{
    /**
     * 软删除
     */
    use SoftDeletes;

    /**
     * @var array
     */
    public static $delete = [
        0 => '正常',
        1 => '软删除',
        2 => '全部'
    ];

    /**
     * @var null
     */
    protected $error = null;

    /**
     * Description:
     * User: Vijay
     * Date: 2019/6/10
     * Time: 21:41
     * @return TraitsModel
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