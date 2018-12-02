Siwo
======
基于swoole4.2.x版本封装的简易框架

框架实现了
----
1. http控制器,tcp控制器,udp控制器,websocket控制器的封装，只需要定义好路由编写对应的控制器即可使用
2. 简易封装了mysql协程客户端，简单实现了类似tp的DB用法

框架安装
----
1. git clone https://github.com/oldshiji/siwo
2. composer install

框架启动
----

1. 启动
php siwod start
![logo](https://github.com/oldshiji/siwo/blob/master/tmp/siwo.png)
2. 停止
php siwod stop
3. 重启
php siwod restart

注意事项
----
框架为本人闲职状态中独立编写的框架，封装思想来源于laravel，个人能力渣，各位同行请勿喷水
有意可互相学习

