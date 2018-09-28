<?php
/**
 * Created by PhpStorm.
 * User: JeremyK
 * Date: 2017/07/10
 * Time: 22:52
 */

namespace app\lib\enum;


class OrderEnum
{
    const UNPAID = 1; //未支付
    const PAID = 2;   //已支付
    const DELIVERED = 3; //已发货
    const PAID_BUT_OUT_OF = 4;//支付但缺货

}