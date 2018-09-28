<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/06
 * Time: 21:43
 */

namespace app\lib\exception;
/**
 *  class 重构异常处理类继承tp5 Handle类
 *  config 配置config.php文件中 exception_handle中文件指向到本类
 *
 */

use Exception;
use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $codeError;


    /**
     * 异常渲染方法
     * 两种异常处理思想
     * 1 用户行为异常处理 返回具体信息 不需记录日志
     * 2 服务器自身异常  不像客户端返回具体原因 记录日志
     * @param Exception $e 异常对象
     * @return json
     */
    public function render(\Exception $e)
    {
        //自定义异常,会向用户报告具体的异常
        if ($e instanceof BaseException) {
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->codeError = $e->errorCode;

        } else {
            //加入条件控制,当处于开发环境时抛出tp5自身的异常,生产环境时抛出json异常
            if(config('app_debug')){
                $this->recordErrorLog($e);
                return parent::render($e);//继承父类,抛出异常
            }else{
                //服务器自身异常,不想告诉用户
                $this->code = 500;
                $this->msg = '服务器内部错误';
                $this->codeError = 999;
                $this->recordErrorLog($e);
            }

        }
        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'codeError' => $this->codeError,
            'requestUrl' => $request->url()
        ];
        return json($result, $this->code);
    }

    //记录内部错误的日志方法
    private function recordErrorLog(\Exception $e)
    {
        Log::init(
            [
                'type'=>'File',
                'path'=>LOG_PATH,
                'level'=>['error']
            ]);
        Log::record($e->getMessage(), 'error');
    }
}