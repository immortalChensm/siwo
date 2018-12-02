<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/19
 * Time: 1:21
 */
namespace Siwo\Foundation\Concrete;
use Siwo\Foundation\Application;
use Siwo\Foundation\Traits\Output;
class Tcp extends \swoole_server
{
    use Output;
    private static $app;
    public function run(Application $app)
    {
        self::$app = $app;
        $this->set([
            'pid_file'=>self::$app['config']['pid_file'],
            'worker_num'=>self::$app['config']['worker_num'],
            'reactor_num'=>self::$app['config']['reactor_num'],
            'task_worker_num'=>self::$app['config']['task_worker_num'],
            'daemonize'=>app('config')['daemonize']
        ]);

        if (config("task_worker_num")!=0){
            $this->on("Task",[$this,'OnTask']);
            $this->on("Finish",[$this,'OnFinish']);
        }
        $this->on("Start",[$this,'OnStart']);
        $this->on("Receive",[$this,'OnReceive']);
        $this->on("Shutdown",[$this,'OnShutdown']);
        return $this;
    }

    public function OnTask(\swoole_server $server,$task_id,$src_worker_id,$data)
    {
        
    }

    public function OnFinish(\swoole_server $server,$task_id,$data)
    {

    }
    public function OnStart(\swoole_server $server)
    {
        $this->showLogo();
    }

    public function OnReceive(\swoole_server $server,$fd,$reactorId,$data)
    {
        $this->dispatch($server,$fd,$reactorId,$data);
    }

    public function OnShutdown(\swoole_server $server)
    {
        echo "siwo tcp server is shutdown!".PHP_EOL;
    }

    public function dispatch(\swoole_server $server,$fd,$reactorId,$data)
    {
        if (false !== strpos($data,":")){
            $msg = explode(":",$data);
            if(isset(self::$app['tcp_routes'][$msg[0]])){
                $action = explode("@",self::$app['tcp_routes'][$msg[0]]);
                $controller = self::$app[$action[0]];
                if (is_object($controller)){
                    if (method_exists($controller,'callAction')){

                        $controller->{'callAction'}($action[1],$server,$fd,$reactorId,$msg[1]);
                    }
                }
            }


        }else{
            $server->send($fd,"Route ".$data." not Defined!");
        }
    }


}