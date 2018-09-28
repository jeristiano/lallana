<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/19
 * Time: 23:20
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken()
    {
        $randChars = getRandChars(32);
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        $salt = config('secure.token_salt');
        return md5($randChars . $timestamp . $salt);
    }

    /**
     * @param $key 获取的属性
     * @param token http头部中的token
     */
    public static function getCurrentTokenVar($key)
    {
        $token = Request::instance()
            ->header('token');
        $var = Cache::get($token);
        if (!$var) {
            throw new TokenException();
        } else {
            if (!is_array($var)) {
                $var = json_decode($var, true);
            }
            if (array_key_exists($key, $var)) {
                return $var[$key];
            } else {
                throw new Exception('尝试获取的token变量并不存在');
            }
        }

    }

    public static function getCurrentUid()
    {
        return self::getCurrentTokenVar("uid");
    }

    //验证用户和管理员接口权限
    public static function checkUsrAdmScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope >= ScopeEnum::User) {
                return true;
            } else {
                throw new ForbiddenException();//无权限访问
            }
        } else {
            throw new TokenException();//token过期或者没有
        }

    }

    //验证被排除的管理员权限
    public static function checkOnlyScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope == ScopeEnum::User) {
                return true;
            } else {
                throw new ForbiddenException();//无权限访问
            }
        } else {
            throw new TokenException();//token过期或者没有
        }
    }

    //验证当前用户是否有效
    public static function isValidateOperate($checkedUID)
    {
       if(!$checkedUID){
          throw new Exception('必须传入要核查的用户ID');
       }
        $uid = self::getCurrentUid();
        if ($checkedUID == $uid) {
            return true;
        }
        return false;
    }
}