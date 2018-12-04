<?php
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