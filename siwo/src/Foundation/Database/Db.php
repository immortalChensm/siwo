<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/22
 * Time: 14:58
 */
namespace Siwo\Foundation\Database;
class Db
{
    public static function getInstance()
    {
        return Query::getInstance();
    }

    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        $query = Query::getInstance();
        if (method_exists($query,$name)){
            return $query->{$name}(...$arguments);
        }
    }
}