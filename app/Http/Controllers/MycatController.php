<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/12/20
 * Time: 17:11
 */
namespace App\Http\Controllers;

use Siwo\Foundation\HttpController;
use Swoole\Coroutine;

class MycatController extends HttpController
{
    public function client()
    {

        //mycat数据测试
        go(function(){
            $db = new Coroutine\MySQL();
            $db->connect([
                'host' => "118.24.77.117",
                'port' => "8066",
                'user' => "root",
                'password' => "123456",
                'database' => "mycatdb",
            ]);

            $sqlHeader="INSERT INTO shopes(id,name) VALUES";
            $sqlBody = "";
            $startTime = microtime(true);
            echo $startTime.PHP_EOL;
            for($i=2;$i<200;$i++){
                $sqlBody.="(".$i.",'"."1655664358".$i."@qq.com'),";
            }
            $sql = $sqlHeader.$sqlBody;

            $stmt = $db->query(substr($sql,0,-1));

            $endTime = microtime(true);
            echo $endTime.PHP_EOL;
            echo "totalTime:".($endTime-$startTime)."s";

            $this->response->end(json_encode($stmt));

        });
    }
}