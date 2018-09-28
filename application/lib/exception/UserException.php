<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/23
 * Time: 21:53
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $msg = '用户不存在';
    public $errorCode = 60000;

}