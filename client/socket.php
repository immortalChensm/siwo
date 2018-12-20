<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/12/20
 * Time: 22:39
 */
print_r($argv);
go(function ()use($argv){


    $client = new \Swoole\Coroutine\Socket(AF_INET, SOCK_STREAM, IPPROTO_IP);

    $client->connect("127.0.0.1",2346);

    $client->send("user/test:".$argv[1]);

    echo $client->recv();
});