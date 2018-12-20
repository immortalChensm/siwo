<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/19
 * Time: 21:39
 */
return [
    'worker_num'=>8,
    'reactor_num'=>4,
    'pid_file'=>'./tmp/siwo.pid',
    'document_root' => '/data/webroot/example.com',
    'enable_static_handler' => true,
    'task_num'=>0,
    'server_type'=>SWOOLE_SOCK_TCP,//SWOOLE_SOCK_TCP
    'server_mode'=>SWOOLE_PROCESS,
    'port'=>2346,
    'host'=>'0.0.0.0',
    'dispatch_mode'=>1,
//    'dispatch_func'=>function(){
//
//    },
    'task_worker_num'=>3,//task 任务进程
    'max_request'=>10,
    'daemonize'=>0,
    'log_file'=>'./log/siwo.log',
    'type'=>'socket',//http,tcp,udp
    'db'=>[
        'host'=>'127.0.0.1',
        'port'=>3306,
        'user'=>'root',
        'password'=>'1jackCsm*',
        'database'=>'swoole'
    ]
];