<?php
namespace app\admin\controller;


class Deal extends Base
{
    private $obj;
    public function _initialize()//初始化操作
    {
        $this->obj=model('Deal');
    }
    public function index()
    {
            $sdata=[];
            $data=input('post.');
            if(!empty($data['category_id'])){
                $sdata['category_id']=$data['category_id'];
            }
            if(!empty($data['city_id'])){
                $sdata['city_id']=$data['city_id'];
            }
            if(!empty($data['start_time'])&&!empty($data['end_time'])&&strtotime($data['start_time'])<strtotime($data['end_time'])){
                $sdata['create_time']=[
                    ['gt',strtotime($data['start_time'])],
                    ['lt',strtotime($data['end_time'])
                    ],
                ];
            }
            if(!empty($data['name'])){
                $sdata['name']=['like','%'.$data['name'].'%',];
            }
            $categorys=model('Category')->getNormalFirstCategory();
            $citys = model('City')->getNomalCitysByParentId();//获取城市列表
            $deals = $this->obj->getNormalDeal($sdata);//获取团购信息
            return $this->fetch('', [
                'deals' => $deals,
                'categorys' => $categorys,
                'citys' => $citys,
                'category_id' => empty($data['category_id'])?'':$data['category_id'],
                'city_id' => empty($data['city_id'])?'':$data['city_id'],
                'start_time' => empty($data['start_time'])?'':$data['start_time'],
                'end_time' => empty($data['end_time'])?'':$data['end_time'],
            ]);

    }
    public function detail($id)//查看团购信息
    {
        $deals = $this->obj->get($id);//团购信息
        $bislocations=model('BisLocation')->getNormalLocationByBisId($deals['bis_id']);//分店信息
        $Citys=model('City')->getNomalCitysByParentId();//所属城市
        $Categorys=model('Category')->getNormalFirstCategory();//所属分类
        return $this->fetch('',[
            'citys'=>$Citys,//城市信息
            'categorys'=>$Categorys,//分类信息
            'deals'=>$deals,//团购信息
            'bislocations'=>$bislocations,//所属团购的分店信息
        ]);
    }
}
