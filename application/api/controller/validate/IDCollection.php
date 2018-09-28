<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/12
 * Time: 23:04
 */

namespace app\api\controller\validate;


class IDCollection extends BaseValidate
{
    //验证规则
    protected $rule = [
        'ids' => 'require|checkIDs',
    ];
    //自定义报错信息
    protected $message = [
        'ids' => 'id必须是以逗号分隔且为正整数'
    ];

    protected function checkIDs($value)
    {
        $values = explode(',', $value);
        if (empty($values)) {
            return false;
        }
        foreach ($values as $id) {
            if (!$this->isPositiveInteger($id)) {
                return false;
            }
        }
        return true;
    }
}