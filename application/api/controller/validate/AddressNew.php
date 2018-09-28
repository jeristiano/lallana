<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/22
 * Time: 22:26
 */

namespace app\api\controller\validate;


class AddressNew extends BaseValidate
{
    protected $rule=[
        'name'=>'require|isNotEmpty',
        'mobile'=>'require|isMobile',
        'province'=>'require|isNotEmpty',
        'city'=>'require|isNotEmpty',
        'country'=>'require|isNotEmpty',
        'detail'=>'require|isNotEmpty',
    ];
}