<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/19
 * Time: 10:41
 */
namespace Siwo\Foundation\Services;

use Siwo\Foundation\Application;

abstract class BaseServices
{
    protected $app;
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    abstract public function register();
    abstract public function boot();
}