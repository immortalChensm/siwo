<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/21
 * Time: 20:37
 */
namespace App\Tcp\Controllers;
use Siwo\Foundation\Database\Db;
use Siwo\Foundation\TcpController;
use Swoole\Coroutine;

class TestController extends TcpController
{
    public function index()
    {
        $this->server->send($this->fd,'hello,siwo');
    }

    public function redis()
    {
        $redis = new Coroutine\Redis();
        $redis->connect("127.0.0.1",6379);
//
//          if ($this->data>=10){
//              $this->server->send($this->fd,"库存已经不足！");
//          }else{
//              $this->server->send($this->fd,$this->data);
//          }

        $redis->lPush("test:order",$this->data);
        if ($redis->lRange("test:order",0,-1)>0){

            $data = $redis->rPop("test:order");
            echo $data.PHP_EOL;
            if (trim($data)>=10){
               if ( $redis->exists("test:order")){
                   $redis->del("test:order");
               }

                $this->server->send($this->fd,"库存已经不足！");
               $this->server->close($this->fd);
            }else{
                $this->server->send($this->fd,$data);
            }

        }

    }


}