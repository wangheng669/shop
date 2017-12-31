<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29
 * Time: 20:27
 */

namespace app\index\controller;


use think\Controller;
use think\Validate;

class Order extends Base
{
    public function index(){
        /*var_dump(input('get.'));*/
        //判断用户是否登录
        $user=$this->getLoginUser();
        if(!$user){
            $this->error("请先登录",'user/login');
        }
        //获取传入参数
        $data=input('get.');
        $validate=validate('Pay');
        if(!$validate->check($data)){
            $this->error("参数非法");
        }
        if(!$data['id']){
            $this->error("参数不合法");
        }
        if(empty($_SERVER['HTTP_REFERER'])){
            $this->error("请求不合法");
        }
        $deal_count=$data['count'];
        $deal_id=$data['id'];
        //获取团购商品信息
        $deal=model('Deal')->get($deal_id);
        $dealPrice=intval($deal['current_price']*$deal_count);
        if(!$deal||$deal['status']!=1){
            $this->error("商品不存在");
        }
        //入库操作
            //订单号
            $orderSn=setOrderSn();
            //商品名称
        $orderData=[
            'out_trade_no'=>$orderSn,
            'user_id'=>$user['id'],
            'username'=>$user['username'],
            'deal_id'=>$deal['id'],
            'deal_count'=>$deal_count,
            'total_price'=>$dealPrice,//支付金额
            'referer'=>$_SERVER['HTTP_REFERER'],//订单来源
        ];
        try{
            $orderId=model('Order')->add($orderData);
        }catch (\Exception $e){
            $this->error("订单提交失败");
        }
        $this->redirect('Pay/index',['id'=>$orderId]);
    }
    public function confirm(){
        if(!$this->getLoginUser()){
            $this->error('请登录','index/index');
        }
        $id=input('get.id',0,'intval');
        $count=input('get.count',0,'intval');
        if(!$id){
            $this->error("参数错误!!!");
        }
        $userData=session('o2o_user','','o2o');
        $dealData=model('Deal')->get($id);
        if(!$dealData||$dealData['status']!=1){
            $this->error('商品不存在');
        }
        return $this->fetch('',[
            'controller'=>'pay',
            'count'=>$count,
            'dealData'=>$dealData,
        ]);
    }
}