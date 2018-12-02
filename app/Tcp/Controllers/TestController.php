<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/21
 * Time: 20:37
 */
namespace App\Tcp\Controllers;
use Siwo\Foundation\Database\Db;
use Siwo\Foundation\TcpController;
use Swoole\Coroutine;

class TestController extends TcpController
{
    public function index()
    {
        $this->server->send($this->fd,'hello,siwo');
    }


}