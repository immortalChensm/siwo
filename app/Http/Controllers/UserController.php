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
        $this->response->end("hi,users");

    }

    public function testa()
    {
        $this->response->end("hi,a");

    }
}
