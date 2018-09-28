<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/11
 * Time: 20:36
 */

namespace app\api\model;

use think\Model;

class BannerItem extends Model
{
    protected $hidden = ['update_time', 'delete_time'];

    public function img()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}