<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/05
 * Time: 22:52
 */

namespace app\api\model;

use think\Model;

class Banner extends BaseModel
{
    protected $hidden = ['update_time', 'delete_time'];

    public function items()
    {
        //关联表明,此表的外键,本表的主键
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

    /**
     * 根据id获得banner的信息
     * @param $id
     *
     * @return array|false|\PDOStatement|string|Model
     */
    public static function getBannerById($id)
    {
        return $result = self::with(['items', 'items.img'])->find($id); //访问静态方法
    }
}