<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/19
 * Time: 1:21
 */
namespace Siwo\Foundation\Concrete;

use Siwo\Foundation\Application;

class ManageServer
{
    private $app;
    function setApplication(Application $app)
    {
        $this->app = $app;
    }
    function runServer($cmd)
    {
        switch ($cmd){
            case 'start':
                $this->createServer();
                break;
            case 'stop':
                if (file_exists($this->app['config']['pid_file'])){
                    \swoole_process::kill(file_get_contents($this->app['config']['pid_file']),SIGTERM);
                }
                break;
            case 'restart':
                if (file_exists($this->app['config']['pid_file'])){
                    \swoole_process::kill(file_get_contents($this->app['config']['pid_file']),SIGTERM);
                }
                $this->createServer();
                break;
        }

    }

    function createServer()
    {
        if($this->app['config']['type'] == 'http'){
            (new Http($this->app['config']['host'],$this->app['config']['port']))->run($this->app)->start();
        }elseif($this->app['config']['type'] == 'tcp'){
            (new Tcp($this->app['config']['host'],$this->app['config']['port'],$this->app['config']['server_mode'],$this->app['config']['server_type']))->run($this->app)->start();
        }elseif($this->app['config']['type'] == 'udp'){

            (new Udp($this->app['config']['host'],$this->app['config']['port'],$this->app['config']['server_mode'],$this->app['config']['server_type']))->run($this->app)->start();
        }elseif($this->app['config']['type'] == 'ws'){

            (new Websocket($this->app['config']['host'],$this->app['config']['port']))->run($this->app)->start();
        }
    }
}