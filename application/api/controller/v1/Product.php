<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/15
 * Time: 21:44
 */

namespace app\api\controller\v1;


use app\api\controller\validate\Count;
use app\api\controller\validate\IdMustBePositiveInt;
use app\api\model\Category;
use app\api\model\Product as ProductModel;
use app\lib\exception\ProductException;

class Product
{
    public function getRecent($count = 10)
    {
        (new Count())->goCheck();
        $result = ProductModel::getMostRecent($count);
        if (!$result) {
            throw new ProductException();
        }
       // $result->hidden(['summary']);
        $result = collection($result)->hidden(['summary']);//临时隐藏字段
        //  修改database中的result_type=collection 使得返回类型集合
       // $result->isEmpty();//对集合进行判空
        return $result;
    }

    public function getAllInCategory($id)
    {
        (new IdMustBePositiveInt())->goCheck();
        $result = ProductModel::getProductsByCateID($id);
        if (!$result) {
            throw new ProductException();
        }
        $result = collection($result)->hidden(['summary']);
        return $result;
    }

    /**
     * 通过id获得一条商品数据
     */
    public function getOneItem($id)
    {
        (new IdMustBePositiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);
        return $product;
    }
}