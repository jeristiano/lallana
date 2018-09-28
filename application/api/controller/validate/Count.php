<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/15
 * Time: 21:48
 */

namespace app\api\controller\validate;


class Count extends BaseValidate
{
    protected $rule=[
        'count'=>'isPositiveInteger|between:1,15',
    ];
}