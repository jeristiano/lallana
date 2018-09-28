<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/06/22
 * Time: 22:33
 */

namespace app\api\controller\v1;


use app\api\controller\validate\AddressNew;
use app\api\model\User as UserModel;
use app\api\service\Token as TokenService;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Address extends BaseController
{
    protected $beforeActionList = [
        'createOrUpdateAddress' => ['only' => 'checkPrimaryScope']
    ];

    public function createOrUpdateAddress()
    {
        $validate = new AddressNew();
        $validate->goCheck();
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if (!$user) {
            throw new UserException();
        }

        $aAddress = $validate->getDataByRule(input('post.'));//根据规则选择操作的数据更新和添加
        /**
         * 关联模型后,返回的数据中被关联模型以属性的形式存在,此处就是这种用法
         */
        $userAddress = $user->address;
        if (!$userAddress) {
            $user->address()
                ->save($aAddress);
        } else {
            //新增的save方法和更新的save方法不一样
            //新增的save来自于关联关系
            //而更新的save来自于模型
            $user->address
                ->save($aAddress);
        }
        return new SuccessMessage();
    }

}