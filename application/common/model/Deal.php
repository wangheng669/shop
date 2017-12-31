<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 14:33
 */

namespace app\common\model;


use think\Model;

class Deal extends Model
{
    public function getDeals($bisId,$status=0)//获取商户下的商品
    {
        $data=[
            'bis_id'=>$bisId,
            'status'=>$status,
        ];
        $order=[
          'id'=>'desc'
        ];
        return $this->where($data)->order($order)->paginate(3);
    }
    public function getNormalDeal($sdata){//获取正常的商品
        return $this->where($sdata)->paginate(3);
    }
    public function getCategoryIdAttr($value){//分类转换
        $result=model('Category')->get($value);
        return $result['name'];
    }
    public function getCityIdAttr($value){//城市转换
        $result=model('City')->get($value);
        return $result['name'];
    }
    public function getNormalDealByCategoryCityId($id,$cityId){//根据分类以及城市获取
        $data=[
            'category_id'=>['in',implode(',',$id)],
            'city_id'=>$cityId,
            'status'=>1,
            'end_time'=>['gt',time()
            ],
        ];
        $order=[
            'listorder'=>'desc',
            'id'=>'desc',
        ];
        return $this->where($data)->order($order)->limit(10)->select();
    }
    public function getCatgoryByOrder($data,$order){//获取某个分类下的商品
        if($order=='default'){
            $order=[
                'id'=>'desc',
            ];
        }
        if($order=='price'){
            $order=[
                'current_price'=>'desc',
            ];
        }
        if($order=='new'){
            $order=[
                'create_time'=>'desc',
            ];
        }
        if($order=='count'){
            $order=[
                'buy_count'=>'desc',
            ];
        }
        $datas[]="end_time > ".time();
        if(!empty($data['se_category_id'])){//如果是二级分类则进行处查询
            $datas[]="find_in_set(".$data['se_category_id'].",se_category_id)";
        }
        if(!empty($data['category_id'])){
            $datas[]='category_id='.$data['category_id'];
        }
        if(!empty($data['city_id'])){
            $datas[]='city_id='.$data['city_id'];
        }
        $datas['status']=1;
        return $this->where(implode(' AND ',$datas))->order($order)->paginate(15);
    }
}