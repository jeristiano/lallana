<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/07/26
 * Time: 21:36
 */

namespace app\api\service;

use app\api\model\Product;
use app\lib\enum\OrderEnum;
use think\Db;
use think\Exception;
use think\Log;

Loader::import('WxPay.WxPay', EXTEND_PATH, '.Api.php');

class WxNotify extends \WxPayNotify
{
    public function NotifyProcess($data, &$msg)
    {
        if ($data['result_code'] == 'SUCCESS') {
            $orderNo = $data['out_trade_no'];
            Db::startTrans();
            try {
                $order = Order::where('order_no', '=', $orderNo)->lock(true)->find();
                if ($order->status == 1) {
                    $service = new Order();
                    $status = $service->checkProductStock($order->id);
                    if ($status['pass']) {
                        $this->updateOrderStatus($order->id, true);
                        $this->reduceStock($status);
                    } else {
                        $this->updateOrderStatus($order->id, false);
                    }
                }
                Db::commit();
            } catch (Exception $ex) {
                Db::rollback();
                Log::error($ex);
                // 如果出现异常，向微信返回false，请求重新发送通知
                return false;
            }
        }
        return true;
    }

    private function reduceStock($status)
    {
        foreach ($status['pStatusArray'] as $singlePStatus) {
            Product::where('id', '=', $singlePStatus['id'])
                ->setDec('stock', $singlePStatus['count']);
        }
    }

    private function updateOrderStatus($orderID, $success)
    {
        $status = $success ? OrderEnum::PAID : OrderEnum::PAID_BUT_OUT_OF;
        Order::where('id', '=', $orderID)
            ->update(['status' => $status]);
    }

}
