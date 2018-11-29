<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/21
 * Time: 18:22
 */
namespace Siwo\Foundation;

class UdpController
{
    protected $clientInfo;
    protected $server;
    protected $data;

    public function callAction($method,\swoole_server $server,$clientInfo,$data){
        $this->clientInfo = $clientInfo;
        $this->data      = $data;
        $this->server    = $server;
        if (method_exists($this,$method)){
            $handle = $this->onRequest();
            if ($handle == true){
                $this->{$method}($data);
            }else{
                $this->server->sendto($clientInfo['address'],$clientInfo,['port'],"Can not response!");
            }

            $this->onAfter();

        }
    }

    public function onRequest()
    {
        return true;
    }

    public function onAfter()
    {

    }

    public function getClientInfo()
    {
        return $this->clientInfo;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getServer()
    {
        return $this->server;
    }
}