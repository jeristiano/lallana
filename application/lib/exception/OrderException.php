<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/29
 * Time: 22:38
 */

namespace app\lib\exception;



class OrderException extends BaseException
{
    public $code = 404;
    public $msg = '订单商品不存在,订单创建失败';
    public $errorCode = 40001;
}