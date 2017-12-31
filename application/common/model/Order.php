<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/30
 * Time: 12:37
 */

namespace app\common\model;


use think\Model;

class Order extends Model
{
    public function add($orderData){
        return $this->save($orderData);
    }
    public function getNormalOrdersById($data,$status=0){
        $data['status']=$status;
        $order=[
          'id'=>'desc',
        ];
        return $this->where($data)->order($order)->select();
    }
}