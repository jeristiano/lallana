<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/15
 * Time: 22:04
 */

namespace app\lib\exception;


class ProductException extends BaseException
{
    public $code = 404;
    public $msg = '获取商品信息失败';
    public $errorCode = 20000;
}