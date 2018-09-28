<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/04
 * Time: 21:14
 */

namespace app\api\controller\validate;

class IdMustBePositiveInt extends BaseValidate
{
    //验证规则
    protected $rule = [
        'id' => 'require|isPositiveInteger',
    ];


}