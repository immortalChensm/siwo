<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/28
 * Time: 15:11
 */
namespace App\Ws\Controllers;

use Siwo\Foundation\WebsocketController;

class UserController extends WebsocketController
{
    public function index()
    {
        echo $this->frame->data;
        $this->getServer()->push($this->fd,"hello,swoole");
    }
}