<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/03
 * Time: 23:16
 */

namespace app\api\controller\validate;

use think\Validate;

/***
 * Class TestValidate 验证器
 * @package app\api\controller\validate
 *
 */
class TestValidate extends Validate
{
    protected $rule =   [
        'id'  => 'require|integer|max:25',
       // 'age'   => 'number|between:1,120',
      //  'email' => 'email',
    ];

    protected $message  =   [
        'id.require' => '必须有id',
        'id.integer'     => 'id必须为整形',
        'id.max'     => 'id最多不能超过18位',
       // 'age.number'   => '年龄必须是数字',
       // 'age.between'  => '年龄只能在1-120之间',
       // 'email'        => '邮箱格式错误',
    ];
}