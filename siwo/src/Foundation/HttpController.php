<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/21
 * Time: 13:21
 */
namespace Siwo\Foundation;

class HttpController
{
    protected $request;
    protected $response;

    public function callAction($method,\swoole_http_request $request,\swoole_http_response $response){
       $this->request = $request;
       $this->response = $response;
       $this->response->header("content-type","text/html;charset=utf8");
       if (method_exists($this,$method)){
           $this->{$method}();
       }
    }

    public function OnRequest(\swoole_http_request $request,\swoole_http_response $response)
    {
        return true;
    }

    public function OnActionAfter(\swoole_http_request $request,\swoole_http_response $response)
    {

    }

    public function OnActionBefore(\swoole_http_request $request,\swoole_http_response $response)
    {

    }
}