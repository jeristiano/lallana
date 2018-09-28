<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/23
 * Time: 22:33
 */

namespace app\lib\exception;


class SuccessMessage extends BaseException
{
    public $code = 201;
    public $msg = '操作成功';
    public $errorCode = 0;
}