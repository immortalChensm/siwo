<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/21
 * Time: 13:28
 */
namespace App\Http\Controllers;

use Siwo\Foundation\Concrete\Channel;
use Siwo\Foundation\HttpController;

class ChanController extends HttpController
{
    public function test()
    {
        $channel = new Channel();
        go(function ()use($channel){
            $channel->set($this->request->get['data']);
        });

        \swoole_coroutine::create(function()use($channel){
            while(!$channel->getPool()->isEmpty()){
                $this->response->end($channel->get());
            }
        });
    }



}
