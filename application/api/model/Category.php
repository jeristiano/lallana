<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/16
 * Time: 22:35
 */

namespace app\api\model;


class Category extends BaseModel
{
    protected $hidden=['delete_time','update_time'];
    public function img(){
        return $this->belongsTo('Image','topic_img_id','id');
    }
}