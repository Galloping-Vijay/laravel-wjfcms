<?php
/**
 * Description:api基础类
 * User: Vijay <1937832819@qq.com>
 * Date: 2020/04/10
 * Time: 13:53
 */

namespace App\Libs\api;


abstract class Driver
{
    /**
     * @var array
     * Description:参数
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 13:54
     */
    protected $param = [];

    /**
     * @var null
     * Description:实例
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 14:00
     */
    protected static $handle = null;

    /**
     * @var null
     * Description:错误信息
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 14:00
     */
    protected $error = null;

    /**
     * @var null
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 13:59
     */
    protected $api_appid = null;

    /**
     * @var null
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 13:59
     */
    protected $api_key = null;

    /**
     * @var null
     * Description:
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 13:59
     */
    protected $app_url = null;

    /**
     * Description:获取操作句柄
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 14:01
     * @return mixed
     */
    abstract public static function handle();

    /**
     * Description:初始化操作
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 14:13
     * @return mixed
     */
    abstract public function init();

    /**
     * Description:设置参数
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 14:18
     * @return mixed
     */
    abstract public function param();

    /**
     * Description:通用回复接口
     * User: Vijay <1937832819@qq.com>
     * Date: 2020/04/10
     * Time: 14:11
     * @return mixed
     */
    abstract public function answer();

    /**
     * 获取最后一次上传错误信息
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 设置错误
     * @param $error
     * @return $this
     */
    protected function setError($error)
    {
        $this->error = $error;
        return $this;
    }
}
