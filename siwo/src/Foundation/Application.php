<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/19
 * Time: 10:16
 */

namespace Siwo\Foundation;
use Siwo\Config\Repository;
use Siwo\Foundation\Concrete\ManageServer;
use Siwo\Foundation\Traits\Singleton;
use Siwo\Route\RouteServiceProvider;

class Application implements \ArrayAccess,\IteratorAggregate
{
    use Singleton;
    public static $instance;

    protected $base_path;
    const VERSION = 'v0.2-alpha';

    protected $providers = [];
    protected $instances = [];
    protected $alias     = [];
    protected $tcpRoutes = [];

    public function __construct($path='\\')
    {
        $this->base_path = dirname($path);
        $this->registerPath();
        $this->registerInstance();
        $this->registerFacade();
        $this->registerCoreClass();
        $this->registerServices();
        $this->registerTcpRoute();
        $this->registerUdpRoute();
        $this->registerWsRoute();
        $this->registerSocketRoute();
        $this->loadConfig();
    }

    public function registerWsRoute()
    {
        $this->instances['ws_routes'] = new Repository(require_once $this->getRoutePath()."/ws.php");
    }

    public function registerTcpRoute()
    {
        $this->instances['tcp_routes'] = new Repository(require_once $this->getRoutePath()."/tcp.php");
    }
    public function registerUdpRoute()
    {
        $this->instances['udp_routes'] = new Repository(require_once $this->getRoutePath()."/udp.php");
    }
    public function registerSocketRoute()
    {
        $this->instances['socket_routes'] = new Repository(require_once $this->getRoutePath()."/socket.php");
    }
    public function run()
    {
        global $argv;
        array_shift($argv);
        $this[ManageServer::class]->setApplication($this);
        $this[ManageServer::class]->runServer($argv[0]);
    }

    private function loadConfig()
    {
        $this->instances['config'] = new Repository(require_once $this->getConfigPath()."/app.php");
    }
    private function registerCoreClass()
    {
        foreach([
            'router'=>\Siwo\Route\Router::class,
            'http'=>\Siwo\Foundation\Concrete\Http::class
                ] as $alias=>$class){
                $this->alias[$alias] = $class;
        }
    }
    private function registerFacade()
    {
        $alias = [
            'Route' => \Siwo\Foundation\Facade\Route::class,
            'Http' => \Siwo\Foundation\Facade\Http::class
        ];
        Facade\Facade::setApplicationFacade($this);
        $this[AliasLoader::class]->setAlias($alias);
    }
    public function registerPath()
    {
        $this->instances['app_path'] = basename($this->base_path)."/app";
        $this->instances['config_path'] = basename($this->base_path)."/config";
        $this->instances['public_path'] = basename($this->base_path)."/public";
        $this->instances['route_path'] = basename($this->base_path)."/route";
    }

    public function registerInstance()
    {
        if (!isset($this->instances['app'])){
            $this->instances['app'] = $this;
        }
        self::$instance = $this;

    }

    public function registerServices()
    {
        $this->register(AliasLoader::class);
        $this->register(RouteServiceProvider::class);
    }

    private function register($service)
    {
        $provider = $this->getProvider($service);
        if (method_exists($provider,'boot')){
            $provider->{'boot'}();
        }
        if (method_exists($provider,'register')){
            $provider->{'register'}();
        }
    }

    private function getProvider($provider){
        if (isset($this->providers[$provider])){
            return $this->providers[$provider];
        }
        return $this->providers[$provider] = new $provider($this);
    }

    public function getAppPath()
    {
        return $this->instances['app_path'];
    }

    public function getConfigPath()
    {
        return $this->instances['config_path'];
    }

    public function getPublicPath()
    {
        return $this->instances['public_path'];
    }

    public function getRoutePath()
    {
        return $this->instances['route_path'];
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
        if (isset($this->instances[$offset])){
            unset($this->instances[$offset]);
        }
    }

    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
        if (!isset($this->instances[$offset])){
            $this->instances[$offset] = $value;
        }
    }

    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
        if (isset($this->instances[$offset])){
            return $this->instances[$offset];
        }else{

            return $this->instances[$offset] = $this->make($offset);
        }

    }

    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
       return $this->instances[$offset]?true:false;
    }

    public function getIterator()
    {
        // TODO: Implement getIterator() method.
    }

    public function make($abstract)
    {
        if ($abstract instanceof \Closure){

        }else{
            return $this->bind($abstract);
        }
    }

    public function bind($abstract)
    {
        $abstract = $this->getAbstract($abstract);
        if (isset($this->instances[$abstract])){
            return $this->instances[$abstract];
        }
        $obj = new \ReflectionClass($abstract);
        $constructor = $obj->getConstructor();

        if (!is_null($constructor)){
            if($constructor->getNumberOfParameters()>0){
                $this->resolveMethodDependency($constructor->getParameters());
            }else{
                return $this->instances[$abstract] = $obj->newInstance();
            }

        }else{
            return $this->instances[$abstract] = $obj->newInstance();
        }
    }

    private function getAbstract($abstract)
    {
        if (isset($this->alias[$abstract])){
            return $this->alias[$abstract];
        }else{
            return $abstract;
        }
    }

    private function resolveMethodDependency($dependencies)
    {

        foreach($dependencies as $dependency){
            echo $dependency->getClass();
        }
        exit;
    }

    public function instance()
    {

    }

    public function  __get($name)
    {
        // TODO: Implement __get() method.
        return $this[$name];
    }

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this[$name] = $value;
    }
}