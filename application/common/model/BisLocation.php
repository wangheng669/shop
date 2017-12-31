<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/23
 * Time: 23:55
 */
namespace app\common\model;
use think\Model;
class BisLocation extends BaseModel
{
    public function setOpenTimeAttr($value)
    {
        return strtotime($value);
    }
    public function getBisIdAttr($value){
        $result=model('BisAccount')->where(['id'=>$value])->find();
        return $result['username'];
    }
    //获取该商户下的店铺
    public function getNormalLocationByBisId($bisId)
    {
        $data=[
          'bis_id'=>$bisId,
            'status'=>1,
        ];
        $order=[
          'id'=>'desc',
        ];
        return $this->where($data)->order($order)->select();
    }
    public function getLocations($status)//返回所有店铺
    {
        $data=[
            'is_main'=>0,
            'status'=>$status,
        ];
        $order=[
            'id'=>'desc',
        ];
        return $this->where($data)->order($order)->select();
    }
    //获取该商品下的店铺
    public function getDealLocations($location){
        $location=explode(',',$location);
        $data=[
            'name'=>['in',$location],
            'status'=>1,
        ];
        $order=[
            'id'=>'desc',
        ];
        return $this->where($data)->order($order)->select();
    }
}