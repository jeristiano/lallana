<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/18
 * Time: 20:29
 */

namespace app\api\service;

use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WechatException;
use think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wechat.app_id');
        $this->wxAppSecret = config('wechat.app_secret');
        //拼接url,使用sprintf方法
        $this->wxLoginUrl = sprintf(config('wechat.login_url'), $this->wxAppID, $this->wxAppSecret, $this->code);
    }

    public function get()
    {
        $oResult = curl_post($this->wxLoginUrl);
        $result = json_decode($oResult, true);
        if (empty($result)) {
            throw new Exception('获取open_id和session_key失败,微信内部错误');
        } else {
            $loginFail = array_key_exists('errcode', $result);
            if ($loginFail) {
                $this->processLoginError($result);
            } else {
              return  $this->grantToken($result);
            }
        }
    }


    private function processLoginError($message)
    {
        throw new WechatException([
            'msg' => $message['errmsg'],
            'errcode' => $message['errcode']
        ]);
    }

    /**
     * token授权处理
     */
    private function grantToken($wxResult)
    {
        //拿到openid
        //数据库判断openid是否存在
        //如果存在不处理,不存在新增一条记录
        //生成令牌,准备储存数据,写入缓存
        //令牌返回客户端
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if ($user) {
            $uid = $user->id;
        } else {
            $uid = $this->newUser($openid);
        }
        $preCacheVault = $this->preCacheValue($wxResult, $uid); //预处理缓存
        $token = $this->saveToCache($preCacheVault);
        return $token;
    }

    //新增用户并且获取id
    private function newUser($openid)
    {
        $user = UserModel::create([
            'openid' => $openid
        ]);
        return $uid = $user->id;
    }

    //缓存信息预处理
    private function preCacheValue($wxResult, $uid)
    {
        $cacheValue = $wxResult;
        $cacheValue['uid'] = $uid;
        $cacheValue['scope'] = ScopeEnum::User;
        return $cacheValue;
    }

    //保存到缓存
    private function saveToCache($preCacheVault)
    {
        $key = self::generateToken();
        $preCacheVault = json_encode($preCacheVault);
        $expire_in = config('setting.token_expire_in');
        $request = cache($key, $preCacheVault, $expire_in);
        if (!$request) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'er rorCode' => 10005
            ]);
        }
        return $key;
    }
}