<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/21
 * Time: 18:47
 */
namespace App\Tcp;

use Siwo\Foundation\TcpController;

class UserController extends TcpController
{
    public function index(\swoole_server $server,$fd,$reactorId,$data)
    {
        $server->send($fd,$data);
    }

    public function task()
    {
        $this->server->task(function (){

        });
    }
}