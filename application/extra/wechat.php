<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/18
 * Time: 21:22
 */
return [
    'app_id'=>'wxd1932b131da472c1',
    'app_secret'=>'b67f0ae28305fb45a59d6b7fcf0d170a',
    //微信使用code获取用户的open_id和session_key
    'login_url'=>'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code'
];