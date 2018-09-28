<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/27
 * Time: 22:05
 */

namespace app\api\controller\validate;


use app\lib\exception\ParameterException;

class OrderPlace extends BaseValidate
{

    //定义规则
    protected $rule = [
        'products' => 'checkProducts'
    ];

    //自定义规则
    protected function checkProducts($values)
    {
        if (empty($values)) {
            throw new ParameterException([
                'msg' => '商品列表不能为空'
            ]);
        }
        if (!is_array($values)) {
            throw new ParameterException([
                'msg' => '商品参数不正确'
            ]);
        }
        foreach ($values as $value) {
          $this->checkProduct($value);
        }
        return true;
    }

    protected function checkProduct($value)
    {
        $validate = new BaseValidate($this->singleRule);
        $result=  $validate->check($value);
        if(!$result){
            throw new ParameterException([
                'msg'=>'商品列表参数错误'
            ]);
        }
    }

    protected $singleRule = [
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger',
    ];
}