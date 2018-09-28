<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/03
 * Time: 22:43
 */

namespace app\api\controller\v1;

use app\api\controller\validate\IdMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\MissException;

class Banner
{
    public function getBanner($id)
    {
        (new IdMustBePositiveInt())->goCheck(); //验证id是否为正整数
        $bBanner = BannerModel::getBannerById($id);
       // return $bBanner;
        if (!$bBanner) {
            throw new MissException([
                'msg'=>'请求的banner不存在',
                'errorCode'=>40000
            ]); //抛出banner异常
        }
        return $bBanner;
    }
}