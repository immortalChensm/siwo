<?php
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

    public function clipFilter()
    {

        Coroutine::create(function (){
            $interval_num = $this->request->post['interval_num'];
            if (empty($interval_num))$this->response->write("分片参数未传递");
            $video = "/home/video/video.mp4";
            $cmd = "/usr/local/bin/ffmpeg -i ".$video." 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//   ";
            $ret = Coroutine::exec($cmd);

            $time = explode(":",$ret['output']);

            //总秒数 视频
            $total_second = intval($time[0])*60*60 + intval($time[1])*60+floatval($time[2]);

            //得到分片数
            $num_second = $total_second/$interval_num;

            if (empty($num_second)||$num_second<10)$this->response->write("分片参数太大");

            $interval_second = [];
            for($i=0;$i<$interval_num;$i++){
                $interval_second[] = $i*$num_second."-".($i+1)*$num_second;
            }

            $result = [];
            foreach($interval_second as $k=>$item){

                $second = explode("-",$item);

                $video_name = date("Ymdhis").$k.".mp4";
                $video_dest = "/home/video/".$video_name;
                $video_output= "https://www.itkucode.com/".$video_name;


                $cmd = "/usr/local/bin/ffmpeg -i ".$video." -vcodec copy -acodec copy -ss ".$second[0]." -to ".$second[1]." ".$video_dest." -y";
                $ret = Coroutine::exec($cmd);
                if (isset($ret['code']) && $ret['code'] == 0){
                    $result[] = $video_output;
                }
            }
            $this->response->end(json_encode($result));

        });


    }

    public function upload()
    {

        Coroutine::create(function (){
            $from_second = $this->request->post['from_second'];
            $to_second   = $this->request->post['to_second'];
            $cmd = "/usr/local/bin/ffmpeg -i /home/video/video.mp4 -vcodec copy -acodec copy -ss 00:00:20 -to 00:00:35 /home/video/video6.mp4 -y";
            $ret = Coroutine::exec($cmd);
            print_r($ret);
        });
        $this->response->end("ok");

    }
}
