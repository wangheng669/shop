<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 13:35
 */

namespace app\bis\validate;
use think\Validate;
class Deal extends Validate
{
    protected $rule=[
        'name|名称' =>'require|max:25',
        'city_id|城市' =>'require',
        'category_id|城市' =>'require',
        'image|缩略图' =>'require',
        'start_time|开始时间' =>'require',
        'end_time|结束时间' =>'require',
        'total_count|库存数' =>'require',
        'origin_price|原价' =>'require',
        'current_price|团购价' =>'require',
        'coupons_begin_time|消费券生效时间' =>'require',
        'coupons_end_time|消费券结束时间' =>'require',
        'notes|团购描述' =>'require',
        'description|购买须知' =>'require',
    ];
}