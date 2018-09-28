<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/12
 * Time: 22:53
 */

namespace app\api\controller\v1;


use app\api\controller\validate\IDCollection;
use app\api\controller\validate\IdMustBePositiveInt;
use app\api\model\Theme as ThemeModel;
use app\lib\exception\ThemeException;

class Theme
{
    /**
     * @param string $ids
     */
    public function getSimpleList($ids = '')
    {
        (new IDCollection())->goCheck();
        $ids = explode(',', $ids);
        $result = ThemeModel::with(['topicImg', 'headImg'])->select($ids);
        if (!$result) {
            throw new ThemeException();
        }
        return $result;
    }

    public function getComplexOne($id)
    {
        (new IdMustBePositiveInt())->goCheck();
        $oReturn = ThemeModel::getThemeWithProducts($id);
        if(!$oReturn){
            throw new ThemeException();
        }
        return $oReturn;
    }

}