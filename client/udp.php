<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/24
 * Time: 19:09
 */

$client = new swoole_client(SWOOLE_SOCK_UDP);

$client->connect("127.0.0.1",2346);

$client->send("user/test:hi");

echo $client->recv(8192);

$client->close();