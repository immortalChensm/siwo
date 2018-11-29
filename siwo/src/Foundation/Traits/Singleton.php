<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/19
 * Time: 10:23
 */
namespace Siwo\Foundation\Traits;
trait Singleton
{
    public static function getInstance()
    {
        if (!self::$instance instanceof self){
            self::$instance = new self();
        }
        return self::$instance;
    }
}