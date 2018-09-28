<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/16
 * Time: 22:39
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code = 404;
    public $msg = '获取分类信息失败';
    public $errorCode = 50000;
}