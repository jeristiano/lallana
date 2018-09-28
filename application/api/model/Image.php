<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/11
 * Time: 20:50
 */

namespace app\api\model;

class Image extends BaseModel
{
    protected $hidden = ['id', 'from', 'update_time', 'delete_time'];
    //获取器,自动调用该模型下的url属性
    public function getUrlAttr($value, $data)
    {
        return $this->prefixImageUrl($value, $data);
    }
}