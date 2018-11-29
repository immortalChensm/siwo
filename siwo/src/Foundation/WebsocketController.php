<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/21
 * Time: 18:22
 */
namespace Siwo\Foundation;

class WebsocketController
{
    protected $fd;
    protected $frame;
    protected $server;
    protected $data;

    public function callAction($method,\swoole_websocket_server $server,\swoole_websocket_frame $frame,$data){
        $this->fd        = $frame->fd;
        $this->frame     = $frame;
        $this->data      = $data;
        $this->server    = $server;
        if (method_exists($this,$method)){
            $handle = $this->onRequest();
            if ($handle == true){
                $this->{$method}($server,$frame->fd,$frame,$data);
            }else{
                $this->server->send($frame->fd,"Can not response!");
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

    public function getFrame()
    {
        return $this->frame;
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