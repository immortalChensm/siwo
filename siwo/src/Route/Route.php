<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/19
 * Time: 15:04
 */
namespace Siwo\Route;

use Siwo\Foundation\Application;
use Siwo\Foundation\Traits\Notice;

class Route
{
    use Notice;
    protected $method = '';
    protected $uri = '';
    protected $action = '';
    protected $controller = '';
    protected $attribute;
    protected $namespace;
    protected $app;
    public function __construct(Array $params)
    {
        $this->method    = $params['method'];
        $this->uri       =  $params['uri'];
        $this->action    =  $params['action'];
        $this->attribute =  $params['attributes'];
        $this->namespace =  $params['namespace'];
        $this->app       =  Application::getInstance();
    }
    public function getUriPrefix()
    {
        return $this->attribute['prefix'];
    }
    public function getController()
    {
        $action = explode("@",$this->action);
        return $this->app[$this->namespace."\\".$action[0]];
    }

    public function getControllerMethod()
    {
        $action = explode("@",$this->action);
        return $action[1];
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return isset($this->attribute['prefix'])?$this->attribute['prefix']."/".$this->uri:$this->uri;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function dispatchToController(\swoole_http_request $request,\swoole_http_response $response)
    {
        $handle = false;
        $controller = $this->getController();
        if (method_exists($controller,'OnActionBefore')){
            $controller->{'OnActionBefore'}($request,$response);
        }
        if (method_exists($controller,'OnRequest')){
            $handle = $controller->{'OnRequest'}($request,$response);
        }
        if($handle === true){
            $controller->callAction($this->getControllerMethod(),$request,$response);
        }else{
            $this->error($response,"No permission to access!");
        }

        if (method_exists($controller,'OnActionBefore')){
            $controller->{'OnActionBefore'}($request,$response);
        }
    }

}