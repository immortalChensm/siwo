<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/21
 * Time: 18:42
 */
return [
    'user/test'=>'App\Tcp\UserController@index',
    'user/client'=>'App\Tcp\ClientController@index',
    'user/tables'=>'App\Tcp\ClientController@showTables',
    'user/users'=>'App\Tcp\ClientController@addUsers',
    'user/get'=>'App\Tcp\ClientController@getUser',
    'test'=>'App\Tcp\Controllers\TestController@index',
    'redis'=>'App\Tcp\Controllers\TestController@redis',


    'mycat'=>'App\Tcp\Controllers\MycatController@client',
];