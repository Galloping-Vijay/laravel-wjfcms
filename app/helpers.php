<?php
/**
 * Description:
 * Created by PhpStorm.
 * User: Vijay
 * Date: 2019/4/14
 * Time: 13:07
 */

/**
 * Instructions:打印数据
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

/**
 * Description:获得随机字符串
 * User: Vijay <1937832819@qq.com>
 * Date: 2019/11/06
 * Time: 16:43
 * @param int $len 需要的长度
 * @param bool $special 是否需要特殊符号
 * @return string 返回随机字符串
 */
function getRandomStr($len = 4, $special = false)
{
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );
    if ($special) {
        $chars = array_merge($chars, array(
            "!", "@", "#", "$", "?", "|", "{", "/", ":", ";",
            "%", "^", "&", "*", "(", ")", "-", "_", "[", "]",
            "}", "<", ">", "~", "+", "=", ",", "."
        ));
    }
    $charsLen = count($chars) - 1;
    //打乱数组顺序
    shuffle($chars);
    $str = '';
    for ($i = 0; $i < $len; $i++) {
        //随机取出一位
        $str .= $chars[mt_rand(0, $charsLen)];
    }
    return $str;
}
