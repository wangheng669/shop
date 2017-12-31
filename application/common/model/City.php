<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/23
 * Time: 12:16
 */

namespace app\common\model;


use think\Model;

class City extends Model
{
    public function getParentIdAttr($value){
        $result=$this->where(['id'=>$value])->find();
        return $result['name'];
    }
    //获取一级城市
    public function getNomalCitysByParentId($parent_id=0){
        $data=[
            'parent_id'=>$parent_id,
            'status'=>1,
        ];
        $order=[
          'listorder'=>'desc',
        ];
        return $this->where($data)->order($order)->select();
    }
    //获取一级城市
    public function getTwoCitys($status=1){
        $data=[
            'parent_id'=>['neq',0],
        ];
        $order=[
            'listorder'=>'desc',
        ];
        return $this->where($data)->order($order)->select();
    }
    public function add($data)//添加操作
    {
        $res=$this->where([
            'name'=>$data['name'],
        ])->whereOr(['uname'=>$data['uname']])->find();
        if($res){
            return false;
        }
        $data['status']=1;
        //$data['create_time']=time();
        return $this->save($data);
    }
}