<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/12/20
 * Time: 1:21
 */
namespace Siwo\Foundation\Concrete;
use Siwo\Foundation\Application;
use Siwo\Foundation\Traits\Output;
use Swoole\Coroutine;

class Socket
{
    use Output;
    private static $app;
    public function run(Application $app)
    {
        self::$app = $app;
        Coroutine::create(function(){
            $server = new Coroutine\Socket(AF_INET, SOCK_STREAM, IPPROTO_IP);
            $server->bind(config('host'),config('port'));
            $server->listen(100);
            $this->showLogo();
            $clients = [];
            while(true){
                $client = $server->accept();
                if ($client===false){
                    throw  new \RuntimeException("Client connect Error!");
                }
                $clients[] = $client;
                $data = $client->recv();
                if ($data){
                    $this->dispatch($client,$data);
                }else{
                    $client->send("Send some data please!");
                }
            }

        });

    }


    public function dispatch($client,$data)
    {
        if (false !== strpos($data,":")){
            $msg = explode(":",$data);
            if(isset(self::$app['socket_routes'][$msg[0]])){
                $action = explode("@",self::$app['socket_routes'][$msg[0]]);
                $controller = self::$app[$action[0]];
                if (is_object($controller)){
                    if (method_exists($controller,'callAction')){

                        $controller->{'callAction'}($action[1],$client,$msg[1]);
                    }
                }
            }


        }else{
            $client->send("Route ".$data." not Defined!");
        }
    }


}