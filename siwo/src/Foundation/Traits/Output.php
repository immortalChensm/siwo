<?php
/**
 * Created by PhpStorm.
 * User: 1655664358@qq.com
 * Date: 2018/11/19
 * Time: 19:15
 */
namespace Siwo\Foundation\Traits;
use JakubOnderka\PhpConsoleColor\ConsoleColor;

trait Output
{
    public function showLogo()
    {
        $consoleColor = new ConsoleColor();

        $logo = <<<LOG
     _
 ___(_)_      _____
/ __| \ \ /\ / / _ \
\__ \ |\ V  V / (_) |
|___/_| \_/\_/ \___/

LOG;

        if ($consoleColor->isSupported()) {
            foreach ($consoleColor->getPossibleStyles() as $style) {
                if ($style == 'light_green'){
                    echo $consoleColor->apply($style, $logo) . "\n";
                    echo $consoleColor->apply('bg_dark_gray', $consoleColor->apply('yellow', ' Siwo is a very simple framework '.$consoleColor->apply('white', '0.0.1').'        ')) . "\n";
                    echo $consoleColor->apply('bg_dark_gray', $consoleColor->apply('yellow', ' php                             '.$consoleColor->apply('white', phpversion()).'        ')) . "\n";
                    echo $consoleColor->apply('bg_dark_gray', $consoleColor->apply('yellow', ' swoole                          '.$consoleColor->apply('white', swoole_version()).'        ')) . "\n";
                    echo $consoleColor->apply('bg_dark_gray', $consoleColor->apply('yellow', ' run at user                     '.$consoleColor->apply('white', get_current_user()).'         ')) . "\n";
                    echo $consoleColor->apply('bg_dark_gray', $consoleColor->apply('yellow', ' listen address                  '.$consoleColor->apply('white', self::$app['config']['host']).'   ')) . "\n";
                    echo $consoleColor->apply('bg_dark_gray', $consoleColor->apply('yellow', ' listen port                     '.$consoleColor->apply('white', self::$app['config']['port']).'        ')) . "\n";
                    echo $consoleColor->apply('bg_dark_gray', $consoleColor->apply('yellow', ' worker_num                      '.$consoleColor->apply('white', self::$app['config']['worker_num']).'           ')) . "\n";
                    echo $consoleColor->apply('bg_dark_gray', $consoleColor->apply('yellow', ' reactor_num                     '.$consoleColor->apply('white', self::$app['config']['reactor_num']).'           ')) . "\n";
                    echo $consoleColor->apply('bg_dark_gray', $consoleColor->apply('yellow', ' server_type                     '.$consoleColor->apply('white', self::$app['config']['type']).'        ')) . "\n";
                    echo $consoleColor->apply('bg_dark_gray', $consoleColor->apply('yellow', ' daemonize                       '.$consoleColor->apply('white', self::$app['config']['daemonize']).'          ')) . "\n";
                }

            }
        }
    }
}