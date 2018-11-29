<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/19
 * Time: 10:39
 */
namespace Siwo\Route;

use Siwo\Foundation\Facade\Route;
use Siwo\Foundation\Services\BaseServices;

class RouteServiceProvider extends BaseServices
{
    protected $namespace = "App\\Http\Controllers";
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->mapWebRoute();
    }

    public function mapWebRoute()
    {
        Route::namespace($this->namespace)->group([],$this->app->getRoutePath()."/web.php");
    }
}