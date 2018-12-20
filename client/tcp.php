<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/12/18
 * Time: 0:42
 */



for ($i=0;$i<20;$i++){

    $process = new swoole_process(function (swoole_process $process){
        Co::create(function (){


            for ($i=0;$i<10000;$i++){
                $client = new \Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);

                $client->connect("127.0.0.1",2346);
                $client->send("redis:".$i);

                $result = $client->recv();
                echo $result.PHP_EOL;
                if ($result == '库存已经不足！'){
                    $client->close();
                    break;
                }
                //$client->setDefer();
            }



        });
    });

    $process->start();
}
for ($i=0;$i<20;$i++){
    swoole_process::wait();
}