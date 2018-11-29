<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/21
 * Time: 21:00
 */
if (!function_exists('app')){
    function app($abstract){
        $app = \Siwo\Foundation\Application::getInstance();
        return $app->make($abstract);
    }
}

if (!function_exists('config')){
    function config($key){
        $app = \Siwo\Foundation\Application::getInstance();
        return $app['config'][$key];
    }
}

if (!function_exists('db')){
    function db(){
        return \Siwo\Foundation\Database\Db::getInstance();
    }
}