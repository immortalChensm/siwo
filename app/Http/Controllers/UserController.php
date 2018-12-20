<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/21
 * Time: 13:28
 */
namespace App\Http\Controllers;

use Siwo\Foundation\HttpController;

class UserController extends HttpController
{
    public function test()
    {
        $this->response->end("<div style='text-align:center;font-size:30px;margin:200px auto;'>Hello,Siwo!^_^<p>基于swoole4.2.x版本撸的简易渣渣框架</p></div>");
        print_r(123);

    }

    public function testa()
    {
        $this->response->end("hi,a");

    }

    public function task()
    {

    }
}
