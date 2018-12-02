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
class Udp extends \swoole_server
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
            'daemonize'=>app('config')['daemonize']
        ]);
        $this->on("Start",[$this,'OnStart']);
        $this->on("Packet",[$this,'OnPacket']);
        $this->on("Shutdown",[$this,'OnShutdown']);
        return $this;
    }

    public function OnStart(\swoole_server $server)
    {
        $this->showLogo();
    }

    public function OnPacket(\swoole_server $server,$data,$clientInfo)
    {
        $this->dispatch($server,$clientInfo,$data);
    }

    public function OnShutdown(\swoole_server $server)
    {
        echo "siwo udp server is shutdown!".PHP_EOL;
    }

    public function dispatch(\swoole_server $server,$clientInfo,$data)
    {
        if (false !== strpos($data,":")){
            $msg = explode(":",$data);
            if(isset(self::$app['udp_routes'][$msg[0]])){
                $action = explode("@",self::$app['udp_routes'][$msg[0]]);
                $controller = self::$app[$action[0]];
                if (is_object($controller)){
                    if (method_exists($controller,'callAction')){

                        $controller->{'callAction'}($action[1],$server,$clientInfo,$msg[1]);
                    }
                }
            }


        }else{
            $server->sendto($clientInfo['address'],$clientInfo['port'],"Route ".$data." not Defined!");
        }
    }


}