<?php
/**
 * Description:
 * Created by PhpStorm.
 * User: Vijay
 * Date: 2019/4/14
 * Time: 13:07
 */

use Intervention\Image\Facades\Image;

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

/**
 * Description:图片添加水印
 * User: Vijay <1937832819@qq.com>
 * Date: 2019/11/08
 * Time: 10:27
 * @param string $img 图片
 * @param boolean $isCover 是否为封面图
 * @param string $text 文字
 * @param string $color 颜色
 * @param string $size 尺寸
 * @return bool|\Intervention\Image\Image
 */
function waterMarkImage($img, $isCover = false, $text = '', $color = '', $size = '')
{
    if (!$img) {
        return false;
    }
    //不修改默认logo
    if (strpos($img, 'default-img') !== false) {
        return false;
    }
    if (strpos($img, $_SERVER['REQUEST_SCHEME']) !== false) {
        $domain = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        $img = public_path(str_ireplace($domain, '', $img));
    }
    if (!file_exists($img)) {
        return false;
    }
    if (!$text) {
        $text = config('vijay.water.text');
    }
    if (!$color) {
        $color = config('vijay.water.color');
    }
    if (!$size) {
        $size = config('vijay.water.size');
    }
    $extension = strtolower(pathinfo($img, PATHINFO_EXTENSION));
    if ($extension !== 'gif') {
        $image = Image::make($img);
        if ($isCover) {
            $image->resize(218, 218);
        }
        $image->text($text, $image->width() - 10, $image->height() - 10, function ($font) use ($color, $size) {
            $font->file(public_path('fonts/msyh.ttf'));
            $font->size($size);
            $font->color($color);
            $font->align('right');
            $font->valign('bottom');
        });
        $res = $image->save($img);
        if ($res->encoded) {
            return true;
        }
    }
    return false;
}

/**
 * Description:获取客户端Ip
 * User: Vijay <1937832819@qq.com>
 * Date: 2019/12/18
 * Time: 15:33
 * @return array|false|string
 */
function getIp()
{
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] AS $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    return $ip;
}
