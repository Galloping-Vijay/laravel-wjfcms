<?php
/**
 * Description:
 * Created by PhpStorm.
 * User: Vijay
 * Date: 2019/4/14
 * Time: 13:07
 */

/**
 * Instructions:终止打印数据
 * Author: Vijay  <1937832819@qq.com>
 * Time: 2019/6/14 9:59
 * @param $data
 * @param int $choice
 */
function pr($data, $choice = 0)
{
    if ($choice == 1) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    } elseif ($choice == 2) {
        dump($data, true, null);
    } else {
        echo "<pre>";
        var_export($data);
        echo "</pre>";
    }
    exit;
}