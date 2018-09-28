<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/28
 * Time: 21:58
 */

namespace app\api\model;

class Order extends BaseModel

{
    protected $autoWriteTimestamp = true;

    public function getSnapItemsAttr($value)
    {
        if (empty($value)) return null;
        return json_encode($value);
    }

    public function getSnapAddressAttr($value)
    {
        if (empty($value)) return null;
        return json_encode($value);
    }

    public static function getSummaryByUser($uid, $page = 1, $size = 15)
    {

        //pagenate 返回的是对象而不是对象 Pagenator::
        $pageDate = self::where('user_id', '=', $uid)
            ->order('create_time desc')
            ->paginate($size, true, ['page' => $page]);
        return $pageDate;
    }
}