<?php
namespace Swoole;

/**
 * @since 4.2.7
 */
class RingQueue
{


    /**
     * @param $len[required]
     * @return mixed
     */
    public function __construct($len){}

    /**
     * @return mixed
     */
    public function __destruct(){}

    /**
     * @param $data[required]
     * @return mixed
     */
    public function push($data){}

    /**
     * @return mixed
     */
    public function pop(){}

    /**
     * @return mixed
     */
    public function count(){}

    /**
     * @return mixed
     */
    public function isFull(){}

    /**
     * @return mixed
     */
    public function isEmpty(){}


}
