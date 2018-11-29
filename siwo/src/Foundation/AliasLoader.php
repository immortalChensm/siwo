<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/19
 * Time: 10:12
 */
namespace Siwo\Foundation;

class AliasLoader
{
    protected static $alias = [];
    public function load($alias)
    {
        if (isset(self::$alias[$alias])){
            return class_alias(self::$alias[$alias],$alias);
        }
    }

    public function setAlias(Array $alias){
        self::$alias = $alias;
    }

    public function register()
    {
        spl_autoload_register([$this,'load'],true, true);
    }
}