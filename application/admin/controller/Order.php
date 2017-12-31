<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/30
 * Time: 20:49
 */

namespace app\admin\controller;



class Order extends Base
{
    private $obj;
    public function _initialize()//初始化操作
    {
        $this->obj=model('Order');
    }
    public function index(){
        $data=input('post.');
        $sdata=[];
        if(!empty($data['start_time'])&&!empty($data['end_time'])&&strtotime($data['start_time'])<strtotime($data['end_time'])){
            $sdata['create_time']=[
                ['gt',strtotime($data['start_time'])],
                ['lt',strtotime($data['end_time'])
                ],
            ];
        }
        if(!empty($data['username'])){
            $sdata['username']=['like','%'.$data['username'].'%',];
        }
        $status=['neq',-1];
        $orders=$this->obj->getNormalOrdersById($sdata,$status);
        return $this->fetch('',[
            'orders'=>$orders,
                'start_time' => empty($data['start_time'])?'':$data['start_time'],
                'end_time' => empty($data['end_time'])?'':$data['end_time'],
                'username' => empty($data['username'])?'':$data['username'],
                ]
        );
    }
    public function dellist(){
        $status=['eq',-1];
        $sdata=[];
        $orders=$this->obj->getNormalOrdersById($sdata,$status);
        return $this->fetch('',[
                'orders'=>$orders,
            ]
        );
    }
    public function editorder($id,$status){
        if($status==1){
            $res=model('Order')->save(['pay_status'=>1],['id'=>$id]);
        }else{
            $res=model('Order')->save(['pay_status'=>0],['id'=>$id]);
        }
        if($res){
            $this->result($_SERVER['HTTP_REFERER'],1,'success');
        }else{
            $this->result($_SERVER['HTTP_REFERER'],0,'修改失败');
        }
    }
}