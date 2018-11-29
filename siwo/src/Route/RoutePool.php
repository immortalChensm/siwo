<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/19
 * Time: 16:47
 */
namespace Siwo\Route;
class RoutePool
{
    private $routes = [];

    public function addRoute(Route $route)
    {
        $this->routes[$route->getMethod()][$route->getUri()] = $route;
    }

    public function match(\swoole_http_request $request)
    {
        if (false === strpos($request->server['request_uri'],"favicon")) {
            if ($this->routes[$request->server['request_method']]) {
                if(isset($this->routes[$request->server['request_method']][substr($request->server['request_uri'], 1)])){
                    return $this->routes[$request->server['request_method']][substr($request->server['request_uri'], 1)];
                }

            } else {
                throw new \RuntimeException("路由参数未定义");
            }
        }
    }
}