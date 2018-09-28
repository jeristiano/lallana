<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/07/04
 * Time: 21:52
 */

namespace app\api\controller\v1;

use app\api\controller\validate\IdMustBePositiveInt;
use app\api\controller\validate\OrderPlace;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderToken;
use app\api\service\Token;
use app\lib\exception\OrderException;
use app\lib\exception\PagingParameter;
use app\api\model\Order as OrderModel;

class Order extends BaseController
{
    /**
     *  用户在选择商品后,向API提交所选择商品的相关信息
     *  API在接收到信息后,需要检查订单相关商品的库存量
     *  有库存 订单数据存入数据库,下单成功,返回客户端消息可以支付
     *  调用支付接口进行支付
     *  还需要进行库存量检测
     * 服务器调用微信接口进行支付,微信返回支付结果
     * 成功:也需要进行库存量检测
     * 成功后 进行库存量扣除,失败返回一个失败结果
     */
    protected $beforeActionList = [
        'placeOrder' => ['only' => 'checkExclusiveScope'],
        'getSummaryByUser' => ['only' => 'checkExclusiveScope'],
        'getDetail' => ['only' => 'checkExclusiveScope'],

    ];

    public function getSummaryByUser($page = 1, $size = 15)
    {
        (new PagingParameter())->goCheck();
        $uid = Token::getCurrentUid();
        $pagenator = OrderModel::getSummaryByUser($uid, $page, $size);
        if ($pagenator->isEmpty()) {
           return [
               'data'=>[],
               'current_page'=>$pagenator->currentPage()
           ];
        }else{
            $data=$pagenator->hidden(['snap_itmes','snap_address','prepay_id'])->toArray();
            return [
                'data'=>$data,
                'current_page'=>$pagenator->currentPage()
            ];
        }
    }
    public function getDetail($id){
        (new IdMustBePositiveInt())->goCheck();
        $orderDetail=OrderModel::get($id);
        if(!$orderDetail) throw new OrderException();
        return $orderDetail->hidden('prepay_id');
    }

    public function placeOrder()
    {
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();
        $status = (new OrderToken)->place($uid, $products);
        return $status;
    }
}