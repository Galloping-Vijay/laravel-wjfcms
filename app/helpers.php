<?php
/**
 * Description:
 * Created by PhpStorm.
 * User: Vijay
 * Date: 2019/4/14
 * Time: 13:07
 */

/**
 * @param $data
 * @param int $choice
 * Description:
 * User: Vijay
 * Date: 2019/4/20
 * Time: 14:06
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