<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/12/20
 * Time: 21:38
 */
namespace App\Socket\Controllers;

use Siwo\Foundation\SocketController;

class UserController extends SocketController
{
    public function index()
    {
        $this->getServer()->send($this->data);
    }
}