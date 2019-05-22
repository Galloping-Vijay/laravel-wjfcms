<?php
/**
 * Description:
 * Created by PhpStorm.
 * User: VIjay
 * Date: 2019/5/22
 * Time: 22:30
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Base extends Model
{
    //软删除
    use SoftDeletes;

    /**
     * @var null
     */
    protected $error = null;

    /**
     * @return Base
     * Description: 获取模型
     * User: VIjay
     * Date: 2019/5/22
     * Time: 22:45
     */
    public static function initialization()
    {
        return new static();
    }

    /**
     * @return null
     * Description:返回错误提示信息
     * User: VIjay
     * Date: 2019/5/22
     * Time: 22:54
     */
    public function getError()
    {
        return $this->error;
    }
}