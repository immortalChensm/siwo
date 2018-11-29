<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/19
 * Time: 10:05
 */
namespace Siwo\Foundation\Facade;

use Siwo\Foundation\Application;
use Siwo\Route\Router;

abstract class Facade
{
    private static $app;

    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        $object = self::$app[static::getFacade()];
        if(is_object($object)){
            return $object->{$name}(...$arguments);
        }else{
            throw new \RuntimeException("Application实例化错误");
        }
    }
    public static function setApplicationFacade($app)
    {
        self::$app = $app;
    }
    protected static function getFacade()
    {
        throw new \RuntimeException("子类未重写getFacade方法");
    }


}