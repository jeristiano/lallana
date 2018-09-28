<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/12
 * Time: 22:54
 */

namespace app\api\model;


class Theme extends BaseModel
{

    protected $hidden = ['delete_time', 'update_time', 'topic_img_id', 'head_img_id'];

    /**
     * 关联image模型 一对一
     * 首页图片
     */
    public function topicImg()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    /**
     * 关联image模型 一对一
     * 专题顶部大图片
     */
    public function headImg()
    {
        return $this->belongsTo('Image', 'head_img_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany('Product', 'theme_product', 'product_id', 'theme_id');
    }

    /***
     * 根据ID查询主题的商品所有信息
     */
    public static  function getThemeWithProducts($id){
        return self::with(['products','headImg','topicImg'])->find($id);
    }

}