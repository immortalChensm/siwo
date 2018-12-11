Siwo
======
基于swoole4.2.x版本封装的简易框架

框架实现了
----
1. http控制器,tcp控制器,udp控制器,websocket控制器的封装，只需要定义好路由编写对应的控制器即可使用
2. 简易封装了mysql协程客户端，简单实现了类似tp的DB用法

框架安装
----
1.composer create-project oldshiji/siwo siwo
目前沿无稳定版本，安装时请指定版本即可

框架和单片机通信实验
----
![logo](https://github.com/oldshiji/siwo/blob/master/tmp/mcu.png)
框架启动
----

1. 启动
php siwod start
![logo](https://github.com/oldshiji/siwo/blob/master/tmp/siwo.png)
2. 停止
php siwod stop
3. 重启
php siwod restart

### HTTP Controller
```php

/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/19
 * Time: 13:36
 */
Route::group(['prefix'=>'api','middleware'=>'web'],function(){
    Route::get("user/test","UserController@test");
    Route::get("user/lists","UserController@testa");
    Route::post("video/clip","VideoController@clip");
    Route::post("video/division","VideoController@clipFilter");


    Route::get("chan/test","ChanController@test");



});

Route::group(['middleware'=>'api'],function(){
    Route::get("user/test/a","UserController@test");
    Route::get("user/lists/a","UserController@lists");
});
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/21
 * Time: 13:28
 */
namespace App\Http\Controllers;

use Siwo\Foundation\Database\Db;
use Siwo\Foundation\HttpController;
use Swoole\Coroutine;
use Swoole\Process;

class VideoController extends HttpController
{

    public function clip()
    {

        Coroutine::create(function (){
            $from_second = $this->request->post['from_second'];//00:00:20
            $to_second   = $this->request->post['to_second'];
            if (empty($from_second))$this->response->write("视频起始时间参数未传递");
            if (empty($to_second))$this->response->write("视频结束时间参数未传递");

            $video = "/home/video/video.mp4";

            $video_name = date("Ymdhis").".mp4";
            $video_dest = "/home/video/".$video_name;
            $video_output= "https://www.itkucode.com/".$video_name;

            $cmd = "/usr/local/bin/ffmpeg -i ".$video." -vcodec copy -acodec copy -ss ".$from_second." -to ".$to_second." ".$video_dest." -y";
            $ret = Coroutine::exec($cmd);

            if (isset($ret['code']) && $ret['code'] == 0){
                $this->response->end($video_output);
            }


        });


    }
```

### Tcp Controller
```php
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


}
```

### Udp Controller
```php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/24
 * Time: 18:09
 */
namespace App\Udp\Controllers;

use Siwo\Foundation\UdpController;

class UserController extends UdpController
{
    public function index()
    {
        $this->server->sendto($this->clientInfo['address'],$this->clientInfo['port'],json_encode($this->getClientInfo()));
    }


}
```

### websocket Controller
```php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/28
 * Time: 15:11
 */
namespace App\Ws\Controllers;

use Siwo\Foundation\WebsocketController;

class UserController extends WebsocketController
{
    public function index()
    {
        echo $this->frame->data;
        $this->getServer()->push($this->fd,"hello,swoole");
    }
}
框架封装思想
----
框架封装思想来源于对Laravel的理解，实现从定义路由到控制器调度的实现封装，由于时间仓促部分功能并未完善
感谢各位同行的支持！给个star鼓励！^_^
