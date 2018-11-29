<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/21
 * Time: 18:22
 */
namespace Siwo\Foundation;

class TcpController
{
    protected $fd;
    protected $reactorId;
    protected $server;
    protected $data;

    public function callAction($method,\swoole_server $server,$fd,$reactorId,$data){
        $this->fd        = $fd;
        $this->reactorId = $reactorId;
        $this->data      = $data;
        $this->server    = $server;
        if (method_exists($this,$method)){
            $handle = $this->onRequest();
            if ($handle == true){
                $this->{$method}($server,$fd,$reactorId,$data);
            }else{
                $this->server->send($fd,"Can not response!");
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

    public function getFd()
    {
        return $this->fd;
    }

    public function getReactorId()
    {
        return $this->reactorId;
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