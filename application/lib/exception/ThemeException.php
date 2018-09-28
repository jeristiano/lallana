<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/13
 * Time: 21:39
 */

namespace app\lib\exception;


class ThemeException extends BaseException
{
    public $code = 404;
    public $msg = '指定主题不存在,请检查主题ID';
    public $errorCode = 30000;

}