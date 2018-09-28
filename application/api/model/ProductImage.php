<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/21
 * Time: 22:10
 */

namespace app\api\model;

class ProductImage extends BaseModel
{
   protected $hidden=['id','img_id','product_id','delete_time','update_id'];
    public function imgUrl(){
        return $this->belongsTo('Image','img_id','id');
    }
}