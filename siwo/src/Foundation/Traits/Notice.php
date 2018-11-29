<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/21
 * Time: 13:03
 */
namespace Siwo\Foundation\Traits;

trait Notice
{
    public function error($response,$msg)
    {
        $response->header("content-type","text/html;charset=utf8");
        $response->write("<html><body style='background: #fefefe;color: #333;font-size: 20px;'>{$msg}</body></html>");
    }
}