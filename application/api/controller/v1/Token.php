<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/18
 * Time: 20:20
 */

namespace app\api\controller\v1;


use app\api\controller\validate\TokenValidate;
use app\api\service\UserToken;

class Token
{
    public function getToken($code){
        (new TokenValidate())->goCheck();
        $oToken=new UserToken($code);
        $sToken= $oToken->get();
        return [
            'token'=>$sToken
        ];

    }


}