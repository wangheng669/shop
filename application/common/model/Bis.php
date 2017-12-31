<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/23
 * Time: 23:55
 */
namespace app\common\model;
use think\Model;
class Bis extends BaseModel
{
    public function getBisByStatus($status=0){
        $data=[
          'status'=>$status,
        ];
        $order=[
          'id'=>'desc'
        ];
        return $this->where($data)->order($order)->paginate(3);
    }
    //获取指定团购信息下的数据
    public function getBisByDeal($bisId,$status=0){
        $data=[
            'id'=>$bisId,
          'status'=>$status,
        ];
        $order=[
          'id'=>'desc'
        ];
        return $this->where($data)->order($order)->find();
    }
}