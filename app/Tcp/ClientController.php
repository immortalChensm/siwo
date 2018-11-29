<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/21
 * Time: 20:37
 */
namespace App\Tcp;
use Siwo\Foundation\Database\Db;
use Siwo\Foundation\TcpController;
use Swoole\Coroutine;

class ClientController extends TcpController
{
    public function index(\swoole_server $server,$fd,$reactorId,$data)
    {
        $client = new \swoole_client(SWOOLE_SOCK_TCP);
        $client->connect("127.0.0.1",2346);
        $client->send("user/test:hello,world");
        $data = $client->recv(8192);
        $server->send($fd,$data);
    }

    public function showTables()
    {
       //$this->getServer()->send($this->fd,'showtables');
        go(function(){
            $db = new Coroutine\MySQL();
            $db->connect([
                'host' => app("config")['db']['host'],
                'port' => app("config")['db']['port'],
                'user' => app("config")['db']['user'],
                'password' => app("config")['db']['password'],
                'database' => app("config")['db']['database'],
            ]);
            $stmt = $db->query("show tables");


            $this->getServer()->send($this->fd,json_encode($stmt));

        });
    }

    public function addUsers()
    {

        Coroutine::create(function(){
           // $db = Db::getInstance();
            //$ret = $db->query("show tables");
            //$ret = $db->table("users")->insert(['a'=>1,'b'=>2]);
            //$ret = $db->table("users")->update(['a'=>1,'b'=>2]);
            //$condition['name'] = ['eq',1];
            //$ret = $db->table("users")->where($condition)->update(['a'=>1,'b'=>2]);
            //$ret = $db->table("users")->where($condition)->delete([1,2,3]);
            //$ret = $db->table("users")->field("name,user,id")->find();
            //$ret = $db->table("users")->field("name,user,id")->where(['name'=>'jack'])->orderby("id","asc")->orderby("name","desc")->paginate(1,15);
            //$ret = $db->table("users")->column('name');
            //$ret = $db->table("users")->where(['name'=>'jack'])->value('name');
            //$ret = Db::table("users")->paginate(1,15);
            //$ret = db()->table("users")->find();
            //$condition['name'] = ['eq',1];
            //$condition['age'] = ['eq',100];
            //$condition['age'] = ['neq',100];
            //$condition['age'] = ['lt',100];
            //$condition['age'] = ['gt',100];
            //$condition['age'] = ['egt',100];
            //$condition['name'] = ['like',"%tom%"];
//            $condition['name'] = ['not like',"%tom%"];
            //$condition['age'] = ['in','5'];
            //$condition['age'] = ['not in',[1,2,3,4]];
            //$condition['age'] = ['between',[1,2]];
            //$condition['age'] = ['not between',[1,2]];
            //$condition['age'] = ['between',[1,100]];
            //$condition['age|name'] = [['eq',1],['like','%jack%']];


            //$ret = Db::table("users")->where($condition)->where(['name','tom'])->get();
            //$ret = Db::table("users")->max('age');
            //$ret = Db::table("users")->min('age');
            //$ret = Db::table("users")->avg('age');
            //$ret = Db::table("users")->where(['age','<>',0])->avg('age');
            //$ret = Db::table("users")->where(['age','<>',0])->sum('age');
            //$condition['age'] = ['between',[1,100]];
            //$condition['age|name'] = [['eq',1],['like','%jack%']];
            //$condition['age|name'] = [['eq',1],['in',['jack','tom']]];
            //$condition['age|name'] = [['not in',[1,2,3,4]],['between',['jack','tom']]];
            //$condition['age&name'] = [['not in',[1,2,3,4]],['between',['jack','tom']]];

//            $ret = Db::table("users")->where("age","=",100)->get();
//            $ret = Db::table("users")->where("age","egt",100)->get();
            //$ret = Db::table("users")->where("age","like","%3")->get();
            //$ret = Db::table("users")->where(['age',100])->get();
            //$condition['name'] = ['eq','jack'];
            //$condition['age'] = ['not in',[1,2,3,4]];
            //$condition['id'] = ['between',[1,20]];

            //$condition['name'] = ['like',"%tom%"];
            //$ret = Db::table("users")->where(['name','jack'])->get();
//            $ret = Db::table("users")->where(['name','like','%tom%'])->get();
//            $ret = Db::table("users")->where("name","like","%jack%")->get();
            //$condition['name'] = ['eq','jack'];
            //$condition['age'] = ['not in',[1,2,3,4]];
            //$condition['id|email'] = [['eq',23],['between',[1,2]]];
            //$ret = Db::table("users")->where($condition)->where(['account','xxx'])->where("address","like","%tom")->get();
//            $ret = Db::table("users")->where(['name','like','%tom%'])->get();
            //$ret = Db::table("users")->whereIn("id",[1,2,3])->get();
            //$ret = Db::table("users")->join("profile","users.id=profile.pid",'left')->whereIn("id",[1,2,3])->get();
            //$ret = Db::table("users")->join("profile","users.id=profile.pid",'inner')->where("id","neq",22)->get();

            $this->getServer()->send($this->fd,json_encode(['a']));
        });
    }

    public function getUser()
    {

        Coroutine::create(function (){
            $redis = new Coroutine\Redis();
            $redis->connect("127.0.0.1",6379);
            $redis->set("name","tom");
            $this->getServer()->send($this->fd,json_encode($redis->keys("*")));
        });
    }
}