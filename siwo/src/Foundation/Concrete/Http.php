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
class Http extends \swoole_http_server
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
            'package_max_length'=>100*1024*1024
        ]);
        $this->on("request",[$this,'OnRequest']);
        $this->on("start",[$this,'OnStart']);
        $this->on("shutdown",[$this,'OnShutdown']);
        return $this;
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
}