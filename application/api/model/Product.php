<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/12
 * Time: 22:54
 */

namespace app\api\model;


class Product extends BaseModel
{
    protected $hidden = ['delete_time', 'create_time', 'update_time', 'pivot'];
    //protected $resultSetType = 'collection'; //返回结果为数据集,里面有visible 和toArray()等方法
    //获取器,自动调用该模型下的url属性
    public function getMainImgUrlAttr($value, $data)
    {
        return $this->prefixImageUrl($value, $data);
    }

    public static function getMostRecent($count = 15)
    {
        $product = self::limit($count)
            ->order('create_time desc')
            ->select();
        return $product;
    }

    //根据分类id获得商品
    public static function getProductsByCateID($id)
    {
        return self::where('category_id', '=', $id)
            ->select();

    }

    /**
     * 通过id获得商品的详细数据
     */
    public static function getProductDetail($id)
    {
        return self::with([
            'imgs' => function ($query) {
                $query->with(['imgUrl'])
                    ->order('order', 'asc');
            }
        ])
            ->with(['properties'])
            ->find($id);
    }


    /**
     * 一对多关联模型 一个商品对应多个图片
     */
    public function imgs()
    {
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    /**
     * 一对多关联模型,一个商品对应对个商品属性
     */
    public function properties()
    {
        return $this->hasMany('productProperty', 'product_id', 'id');
    }
}