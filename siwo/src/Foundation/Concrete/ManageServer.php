<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/19
 * Time: 1:21
 */
namespace Siwo\Foundation\Concrete;

use Siwo\Foundation\Application;
use Swoole\Coroutine;

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
                    $this->handleProcess();
                }
                break;
            case 'restart':
                if (file_exists($this->app['config']['pid_file'])){
                    $this->handleProcess();
                }
                $this->createServer();
                break;
        }

    }

    function handleProcess()
    {
        Coroutine::create(function(){
            $shell = " ps -ef |grep siwod |grep -v grep |awk '{print $2'}";
            $process_exist = \swoole_coroutine::exec($shell);
            $process_list = explode("\n",$process_exist['output']);

            $pid_file_content = file_get_contents($this->app['config']['pid_file']);
            if (in_array($pid_file_content,$process_list)){
                \swoole_process::kill(file_get_contents($this->app['config']['pid_file']),SIGTERM);
            }
        });
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
        }elseif($this->app['config']['type'] == 'socket'){

            (new Socket())->run($this->app);
        }
    }
}