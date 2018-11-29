<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/28
 * Time: 15:30
 */

$client = new swoole_http_client("127.0.0.1","2346");

$client->on("message",function ($cli,$frame){
    print_r($frame);
});

$client->upgrade("/",function (swoole_http_client $cli){
    $cli->push("user/test:hi");
    $cli->body;
});