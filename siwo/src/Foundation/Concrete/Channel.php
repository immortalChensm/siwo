<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/12/3
 * Time: 19:55
 */
namespace Siwo\Foundation\Concrete;

class Channel
{
    protected $pool;
    public function __construct($size=100)
    {
        $this->pool = new \Swoole\Coroutine\Channel($size);
    }

    public function get()
    {
        return $this->pool->pop(3);
    }

    public function set($data)
    {
        return $this->pool->push($data);
    }

    public function getPool()
    {
        return $this->pool;
    }
}