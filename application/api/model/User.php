<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/19
 * Time: 22:04
 */

namespace app\api\model;


class User extends BaseModel
{
    public function address(){
        return $this->hasOne('UserAddress','user_id','id'); //外键在外表
    }

    /**
     * @param $openid
     * @return array 返回数据集合
     */
    public static function getByOpenID($openid)
    {
        $result = self::where('openid', '=', $openid)->find();
        return $result;
    }
}