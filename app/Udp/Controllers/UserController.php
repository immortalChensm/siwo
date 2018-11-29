<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/24
 * Time: 18:09
 */
namespace App\Udp\Controllers;

use Siwo\Foundation\UdpController;

class UserController extends UdpController
{
    public function index()
    {
        $this->server->sendto($this->clientInfo['address'],$this->clientInfo['port'],json_encode($this->getClientInfo()));
    }


}