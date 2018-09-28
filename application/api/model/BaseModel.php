<?php

namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    //定义图片路径前缀
    protected function prefixImageUrl($value, $data)
    {
        $finalUrl = $value;
        if ($data['from'] == 1) {
            $result = config('setting.img_prefix') . $value;
            return $result;
        } else {
            return $finalUrl;
        }

    }

}
