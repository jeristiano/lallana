<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/26
 * Time: 22:38
 */

namespace app\api\controller\v1;


use app\api\service\Token as TokenService;
use think\Controller;

class BaseController extends Controller
{
    //用户和管理员权限
    protected function checkPrimaryScope()
    {
        TokenService::checkUsrAdmScope();
    }
    //只有用户访问的权限
    protected function checkExclusiveScope(){
        TokenService::checkOnlyScope();
    }
}