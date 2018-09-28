<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/07/02
 * Time: 16:54
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\api\model\Order as OrderModel;
use think\Exception;

class Order
{

    protected $aOrderProducts; //订单商品列表,也就是客户端传过来的products参数
    protected $products;//真实的商品信息（包括库存量）
    protected $uid;

    public function place($uid, $aOrderProducts)
    {
        $this->aOrderProducts = $aOrderProducts;
        $this->products = $this->_getProductsByOrder($aOrderProducts);
        $this->uid = $uid;
        //库存量检测
        $status = $this->_getOrderStatus();//库存量检测
        if (!$status['isPass']) {
            $status['order_id'] = -1;//订单编号为-1,不存在此订单
            return $status;
        }

        //创建订单快照
        $orderSnap = $this->_snapOrder($status);
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return $order;

    }


    //创建订单
    private function createOrder($snap)
    {
        try {
            $orderNo = self::makeOrderNo();
            $order = new OrderModel();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);
            $order->save();
            $orderId = $order->id;
            $orderTime = $order->create_time;
            foreach ($this->aOrderProducts as &$p) { //此处需要传递引用
                $p['order_id'] = $orderId;
            }

            $orderProduct = new OrderProduct;
            $orderProduct->saveAll($this->aOrderProducts);
            return [
                'order_no' => $orderNo,
                'order_id' => $orderId,
                'create_time' => $orderTime
            ];
        } catch (Exception $ex) {
            throw $ex;
        }

    }

    //生成订单号
    public static function makeOrderNo()
    {
        $yCode = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];
        $orderSn = $yCode[intval(date('Y') - 2017)] . strtoupper(dechex(date('m'))) . date('d') . substr
            (time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
        return $orderSn;
    }

    //生成订单的快照
    private function _snapOrder($status)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,//订单中包含商品的总数量,不是商品种类的总数量
            'pStatus' => [],
            'snapAddress' => '',
            'snapName' => '',
            'snapImg' => ''
        ];
        $snap['orderPrice'] = $status['oPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->_getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];//取出第一个商品作为订单展示信息
        $snap['snapImg'] = $this->products[0]['main_img_url'];
        if (count($this->products) > 1) {
            $snap['snapName'] .= '等';
        }
        return $snap;
    }

    //获得用户订单的地址
    private function _getUserAddress()
    {
        $address = UserAddress::where('user_id', '=', $this->uid)
            ->find();
        if (!$address) {
            throw new OrderException([
                'msg' => '用户地址不存在,订单创建失败',
                'errCode' => 60001
            ]);
        }
        return $address->toArray();
    }

    /**
     * 根据订单号检测当前订单的库存量,对外的公共方法
     * @param $orderID 订单id
     * @return array
     */
    public function checkProductStock($orderID)
    {
        $oProducts = OrderProduct::where('order_id', '=', $orderID)->select();
        $this->aOrderProducts = $oProducts;
        $this->products = $this->_getProductsByOrder($oProducts);
        $status = $this->_getOrderStatus();
        return $status;
    }

    //获得订单的状态等信息

    private function _getOrderStatus()
    {
        /*
         * bool isPass 订单是否有效
         * float oPrice 订单的总价格
         * array pStatusArray 商品信息数组
         */
        $oStatus = [
            'isPass' => true,
            'oPrice' => 0,
            'totalCount' => 0,
            'pStatusArray' => []

        ];
        foreach ($this->aOrderProducts as $val) {
            $result = $this->_getProductStatus($val['product_id'], $val['count'], $this->products);
            if (!$result['hasStock']) {
                $oStatus['isPass'] = false;
            }
            $oStatus['oPrice'] += $result['totalPrice'];
            $oStatus['totalCount'] += $result['count'];
            array_push($oStatus['pStatusArray'], $result);

        }
        return $oStatus;
    }

    //获得商品的状态信息
    /**
     * @param $pID 商品id
     * @param $iCount 商品购买数量
     * @param $products 订单商品信息
     * @return array 返回商品数组信息
     * @throws OrderException
     * @internal param 商品的数组信息 $aProduct
     */
    private function _getProductStatus($pID, $iCount, $products)
    {
        $pIndex = -1; //商品序号
        $status = [
            'id' => null,
            'hasStock' => false,
            'totalPrice' => 0,
            'name' => null,
            'count' => 0
        ];
        for ($i = 0; $i < count($products); $i++) {
            if ($pID == $products[$i]['id']) {
                $pIndex = $i;
            }
        }
        if ($pIndex == -1) {
            throw new OrderException([
                'msg' => '商品ID为' . $pID . '商品不存在,订单失败'
            ]);
        } else {
            $product = $products[$pIndex];//取出此id商品信息
            $status['id'] = $product['id'];
            $status['count'] = $iCount;
            $status['name'] = $product['name'];
            $status['totalPrice'] = $product['price'] * $iCount;
            if ($product['stock'] - $iCount >= 0) {
                $status['hasStock'] = true;
            }
        }

        return $status;

    }

    //根据订单获得商品详细信息
    private function _getProductsByOrder($aOrderProducts)
    {
        $aPrudt = [];
        foreach ($aOrderProducts as $val) {
            array_push($aPrudt, $val['product_id']);
        }

        $result = Product::all($aPrudt)
            ->visible(['id', 'price', 'stock', 'name', 'main_img_url'])
            ->toArray();
        //conllection($result)->visible()->toArray();想使用数据集必须先转换成数据集合才能使用v和to方法
        return $result;
    }
}