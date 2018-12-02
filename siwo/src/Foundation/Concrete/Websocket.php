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
class Websocket extends \swoole_websocket_server
{
    use Output;
    private static $app;
    function run(Application $app)
    {
        self::$app = $app;
        $this->set([
            'pid_file'=>self::$app['config']['pid_file'],
            'worker_num'=>self::$app['config']['worker_num'],
            'reactor_num'=>self::$app['config']['reactor_num'],
            'daemonize'=>app('config')['daemonize']
        ]);
        $this->on("open",[$this,'OnOpen']);
        $this->on("close",[$this,'OnClose']);
        $this->on("message",[$this,'OnMessage']);
        $this->on("request",[$this,'OnRequest']);
        $this->on("start",[$this,'OnStart']);
        $this->on("shutdown",[$this,'OnShutdown']);
        return $this;
    }

    public function OnOpen(\swoole_websocket_server $server,\swoole_http_request $request)
    {

    }

    public function OnClose(\swoole_websocket_server $server,$fd)
    {

    }

    public function OnMessage(\swoole_websocket_server $server,\swoole_websocket_frame $frame)
    {
        $this->dispatch($server,$frame);
    }
    public function OnRequest(\swoole_http_request $request,\swoole_http_response $response)
    {
        self::$app['router']->dispatchToRoute($request,$response);
    }

    public function OnStart(\swoole_http_server $server)
    {
        $this->showLogo();
    }

    public function OnShutdown(\swoole_http_server $server)
    {
        echo "siwo shutdown!".PHP_EOL;
    }

    public function dispatch(\swoole_server $server,\swoole_websocket_frame $frame)
    {
        $data = $frame->data;
        if (false !== strpos($data,":")){
            $msg = explode(":",$data);
            if(isset(self::$app['ws_routes'][$msg[0]])){
                $action = explode("@",self::$app['ws_routes'][$msg[0]]);
                $controller = self::$app[$action[0]];
                if (is_object($controller)){
                    if (method_exists($controller,'callAction')){

                        $controller->{'callAction'}($action[1],$server,$frame,$msg[1]);
                    }
                }
            }


        }else{
            $server->push($frame->fd,"Route ".$frame->data." not defined!");
        }
    }
}