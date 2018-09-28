<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/19
 * Time: 23:44
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'token已过期或者无效';
    public $errorCode = 10001;
}