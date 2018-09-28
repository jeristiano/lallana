<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/07/10
 * Time: 21:59
 */

namespace app\api\service;


use app\api\model\Order as OrderModel;
use app\lib\enum\OrderEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use think\Loader;
use think\Log;

Loader::import('Wxpay.WxPay', EXTEND_PATH, '.Api.php');

class Pay
{
    private $orderID;
    private $orderNO;

    public function __construct($orderID)
    {
        if (!$orderID) {
            throw new Exception('订单号不能NULL');
        }
        $this->orderID = $orderID;
    }

    public function pay()
    {
        /**
         * 1 订单号可能根本就不存在
         * 2 可能不是当前用户的订单
         * 3 订单有可能支付过了
         * 原则:最有可能发生的错误放在前面检测,对数据库消耗小的放在前面
         */
        $this->checkOrderValid();
        $objOrder = new Order();
        $status = $objOrder->checkProductStock($this->orderID);//库存量检测
        if (!$status['pass']) {
            return $status;
        }
        $this->makeWxPreOrder($status['oPrice']);
    }

    //生成微信预处理订单
    private function makeWxPreOrder($totalPrice)
    {
        //openid
        $openid = Token::getCurrentTokenVar('openid');
        if (!$openid) {
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder($totalPrice);
        $wxOrderData->SetBody('零食商贩');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTotal_fee($totalPrice * 100);//以分交易
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetNotify_url(config('secure.wx_url_cb'));
        return $this->getPaySignature($wxOrderData);


    }

    private function getPaySignature($wxOrderData)
    {
        $wxorder = \WxPayApi::unifiedOrder($wxOrderData);
        if ($wxorder['return_code'] != 'SUCCESS' || $wxorder['result_code'] != 'SUCCESS') {
            Log::record($wxorder, 'error');
            Log::record('获取预支付订单失败', 'error');
        }
        $this->recordPreOrder($wxorder);
        $signature = $this->sign($wxorder);
        return $signature;
    }

    private function recordPreOrder($wxorder)
    {
       return OrderModel::where('order_id', '=', $this->orderID)
            ->update(['prepay_id' => $wxorder['prepay_id']]);

    }
    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wechat.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $sign = $jsApiPayData->MakeSign();
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        unset($rawValues['appId']);
        return $rawValues;
    }

    //订单三种异常状态检测
    private function checkOrderValid()
    {
        $objOrder = OrderModel::where('id', '=', $this->orderID)->find();
        if (!$objOrder) {
            throw new OrderException('当前订单号不存在');
        }
        //验证用户是否有效
        if (!Token::isValidateOperate($objOrder->user_id)) {
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errCode' => 10003
            ]);
        }
        //支付过
        if ($objOrder->status != OrderEnum::UNPAID) {
            throw new OrderException([
                'msg' => '订单已支付过,请勿重复支付',
                'errCode' => 80003,
                'code' => 400
            ]);
        }
        return $this->orderNO = $objOrder->order_no;
    }

}