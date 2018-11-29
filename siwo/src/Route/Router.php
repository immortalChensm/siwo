<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/19
 * Time: 10:45
 */
namespace Siwo\Route;
use Siwo\Foundation\Traits\Notice;

class Router
{
    use Notice;
    private $allowMethods = ['get','post','delete','put'];
    public $routes = [];
    protected $namespace = '';
    private $routePool = null;

    protected $attributes = [];
    protected $allowAttributes = ['prefix','namespace','middleware'];
    public function __construct()
    {
        $this->routePool = new RoutePool();
    }

    public function get($uri,$action)
    {
        $this->addRoute('GET',$uri,$action);
    }

    public function post($uri,$action)
    {
        $this->addRoute('POST',$uri,$action);
    }

    public function addRoute($method,$uri,$action)
    {
        $this->routePool->addRoute(new Route([
            'method'=>$method,
            'uri'=>$uri,
            'action'=>$action,
            'attributes'=>$this->attributes,
            'namespace'=>$this->namespace,
        ]),$method);
    }

    public function group($attribute,$callback)
    {
        if (count($attribute)>0&&is_array($attribute)){
            foreach(array_keys($attribute) as $key){
                if (!in_array($key,$this->allowAttributes)){
                    throw new \InvalidArgumentException("路由参数定义错误");
                }
            }
        }

        $this->attributes = $attribute;
        if ($callback instanceof \Closure){
            $callback();
        }else{
            require_once $callback;
        }
    }

    public function dispatchToRoute(\swoole_http_request $request,\swoole_http_response $response)
    {
            $route = $this->routePool->match($request);
            if(is_object($route)){
                $route->dispatchToController($request,$response);
            }else{
                $this->error($response,$request->server['request_uri']."该路由未定义");
            }

    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        if ($name == 'namespace'){
            $this->namespace = $arguments[0];
        }
        return $this;
    }
}