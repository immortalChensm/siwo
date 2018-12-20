<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/21
 * Time: 18:22
 */
namespace Siwo\Foundation;
class SocketController
{
    protected $server;
    protected $data;

    public function callAction($method,$client,$data){

        $this->data      = $data;
        $this->server    = $client;
        if (method_exists($this,$method)){
            $handle = $this->onRequest();
            if ($handle == true){
                $this->{$method}($client,$data);

            }else{
                $this->server->send("Can not response!");
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

    public function getData()
    {
        return $this->data;
    }

    public function getServer()
    {
        return $this->server;
    }
}