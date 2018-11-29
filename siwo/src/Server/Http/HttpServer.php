<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/19
 * Time: 0:48
 */
namespace Siwo\Server\Http;
class HttpServer extends swoole_http_server
{
    protected $server = null;

    function run()
    {
        $this->on("Start",[$this,'OnStart']);
        $this->on("Shutdown",[$this,'OnShutdown']);
        $this->on("request",[$this,'OnRequest']);
    }

    function OnStart(swoole_http_server $server)
    {

    }

    function OnShutdown(swoole_http_server $server)
    {

    }

    function OnRequest(swoole_http_request $request,swoole_http_response $response)
    {
        print_r($request->server);
    }
}