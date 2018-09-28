<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/07/29
 * Time: 21:20
 */

namespace app\lib\exception;


use app\api\controller\validate\BaseValidate;

class PagingParameter extends BaseValidate
{
protected $rule=[
    'page'=>'isisPositiveInteger',
    'size'=>'isPositiveInteger',
];
protected $message=[
    'page'=>'分页必须是正整数',
    'size'=>'分页必须是正整数',
];

}