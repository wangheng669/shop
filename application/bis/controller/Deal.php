<?php
namespace app\bis\controller;

class Deal extends Base
{
    private $obj;
    public function _initialize()//初始化操作
    {
        $this->obj=model('Deal');
    }
    public function index()
    {
        $bisId=$this->getLoginUser()->bis_id;
        $status=1;
        $deals=model('Deal')->getDeals($bisId,$status);
        return $this->fetch('',['deals'=>$deals]);
    }
    public function add()
    {
        //检测是否是post传参
        if(request()->isPost()){
            $data=input('post.');
            $validate=validate('Deal');
            $bisId=$this->getLoginUser()->bis_id;
            if(!$validate->check($data)){
                $this->error($validate->getError());
            }
            $location=model('BisLocation')->where(['name'=>$data['location_ids'][0]])->find();
            $deals=[
              'name'=>$data['name'],
              'category_id'=>$data['category_id'],
              'se_category_id'=>empty($data['se_category_id'])?0:implode(',',$data['se_category_id']),
              'bis_id'=>$bisId,
              'location_ids'=>empty($data['location_ids'])?'':implode(',',$data['location_ids']),
              'image'=>$data['image'],
              'description'=>html_entity_decode($data['description']),
              'start_time'=>strtotime($data['start_time']),
              'end_time'=>strtotime($data['end_time']),
              'total_count'=>$data['total_count'],
              'origin_price'=>$data['origin_price'],
              'current_price'=>$data['current_price'],
                'city_id'=>$data['city_id'],
              'coupons_begin_time'=>strtotime($data['coupons_begin_time']),
              'coupons_end_time'=>strtotime($data['coupons_end_time']),
              'notes'=>html_entity_decode($data['notes']),
                'bis_account_id'=>$this->getLoginUser()->id,
              'xpoint'=>$location->xpoint,
              'ypoint'=>$location->ypoint,
            ];
            $res=model('Deal')->save($deals);
            if($res){
                $this->success('添加成功',url('location/index'));
            }else{
                $this->success('添加失败');
            }
        }else{
            //获取该商户ID
            $bisId=$this->getLoginUser()->bis_id;
            $bislocations=model('BisLocation')->getNormalLocationByBisId($bisId);//分店信息
            $Citys=model('City')->getNomalCitysByParentId();//所属城市
            $Categorys=model('Category')->getNormalFirstCategory();//所属分类
            return $this->fetch('',[
                'citys'=>$Citys,//城市信息
                'categorys'=>$Categorys,//分类信息
                'bislocations'=>$bislocations,//所属该商户下分店信息
            ]);
        }
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
