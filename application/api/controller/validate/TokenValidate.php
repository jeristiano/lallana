<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/18
 * Time: 20:22
 */

namespace app\api\controller\validate;


class TokenValidate extends BaseValidate
{
    //code存在但不能为空
    protected $rule=[
        'code'=>'require|isNotEmpty'
    ];
    protected $message=[
        'code'=>'没有code无法获得token哦!'
    ];
}