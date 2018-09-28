<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/24
 * Time: 23:05
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = '没有权限访问此接口';
    public $errorCode = 10001;
}