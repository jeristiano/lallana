<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/06
 * Time: 21:44
 */

namespace app\lib\exception;
use think\Exception;

/***
 * Class BaseException 自定义异常类积累
 * @package app\lib\exception
 *
 */

class BaseException extends Exception
{
    public $code = 400;
    public $msg = 'invalid parameters';
    public $errorCode = 999;
    //构造函数,初始化赋值给 code msg errorCode
    public function __construct($param=[])
    {
        if(!is_array($param)){
            return ;//不是数组,中断处理或者返回错误信息
        }
        //判断code是否存数组键值里
        if(array_key_exists('code',$param)){
            $this->code=$param['code'];
        }
        //判断msg是否存数组键值里
        if(array_key_exists('msg',$param)){
            $this->msg=$param['msg'];
        }
        //判断errorCode是否存数组键值里
        if(array_key_exists('errorCode',$param)){
            $this->errorCode=$param['errorCode'];
        }
    }
}