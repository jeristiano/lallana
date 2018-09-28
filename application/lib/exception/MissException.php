<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/06
 * Time: 22:37
 */

namespace app\lib\exception;


class MissException extends BaseException
{
    /**
     * 自定义异常处理
     * @code int
     * @msg string
     * @errorCode int
     */
    public $code = 404;
    public $msg = 'global:your required resource are not found';
    public $errorCode = 10001;
}