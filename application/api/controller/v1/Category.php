<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/16
 * Time: 22:33
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category
{
    public function getAllCategories()
    {
        $categories = CategoryModel::all([],'img');
        if(!$categories){  
            throw new CategoryException();
        }
        return $categories;
    }
}