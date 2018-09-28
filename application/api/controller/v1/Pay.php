<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/07/25
 * Time: 22:18
 */

namespace app\api\controller\v1;


use app\api\controller\validate\IdMustBePositiveInt;
use app\api\service\Pay as PayService;
use app\api\service\WxNotify;
class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder']
    ];
    public function getPreOrder($id='')
    {
        (new IdMustBePositiveInt()) -> goCheck();
        $pay= new PayService($id);
        return $pay->pay();
    }

    public function receiveNotify()
    {
        $notify = new WxNotify();
        $notify->handle();
    }
}