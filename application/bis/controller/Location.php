<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/25
 * Time: 20:47
 */

namespace app\bis\controller;


use think\Controller;

class Location extends Base
{
    private $bisId;
    public function _initialize()//初始化操作
    {
        $this->bisId=$this->getLoginUser()->bis_id;
    }
    public function index(){
        $locationData=model('BisLocation')->where(['bis_id'=>$this->bisId,'status'=>'1'])->paginate(3);
        return $this->fetch('',['locationData'=>$locationData]);
    }
    //查看分店
    public function detail(){
        $id=input('get.id');
        $bisData=model('Bis')->get($id);//商户基本信息
        $locationData=model('BisLocation')->get(['bis_id'=>$id,'is_main'=>1]);//分店信息
        $Citys=model('City')->getNomalCitysByParentId();//所属城市
        $Categorys=model('Category')->getNormalFirstCategory();//所属分类
        return $this->fetch('',[
            'citys'=>$Citys,
            'categorys'=>$Categorys,
            'bisData'=>$bisData,
            'locationData'=>$locationData,
        ]);
    }
    //添加分店
    public function add(){
        if(request()->isPost()){
            //调用模板添加数据
            $data = input('post.');
            //检验数据
            $validate = validate('Bis');
            if (!$validate->scene('addlocation')->check($data)) {
                $this->error($validate->getError());
            }
            //获取经纬度
            $lnglat=\Map::getLngLat($data['address']);
            if(empty($lnglat)||$lnglat['status']!=0){
                $this->error('无法获取数据,或者匹配地址不精确');
            }
            //分店基本信息
            //由于$data['se_category_id']是一个数组形式所以要通过,进行分割
            if(!empty($data['se_category_id']))
            {
                $data['se_category_id']=implode('|',$data['se_category_id']);
            }
            $locationData=[
                'name'=>$data['name'],
                'logo'=>$data['logo'],
                'tel' =>$data['tel'],
                'contact' =>$data['contact'],
                'category_id' =>$data['category_id'],
                'bis_id' =>$this->bisId,
                'category_path'=>empty($data['se_category_id'])?'':$data['category_id'].','.$data['se_category_id'],
                'city_id'=>$data['city_id'],
                'city_path'=>empty($data['se_city_id'])?$data['city_id']:$data['city_id'].','.$data['se_city_id'],
                'api_address' =>$data['address'],
                'open_time' =>$data['open_time'],
                'content'=>empty($data['content'])?'':html_entity_decode($data['content']),
                'is_main'=>0,
                'xpoint'=>empty($lnglat['result']['location']['lng'])?'':$lnglat['result']['location']['lng'],
                'ypoint'=>empty($lnglat['result']['location']['lat'])?'':$lnglat['result']['location']['lat'],
            ];
            $locationId=model('BisLocation')->add($locationData);
            if($locationId){
                $this->success('门店申请成功');
            }else{
                $this->error('门店申请失败');
            }
        }else{
            $id=input('get.id');
            $bisData=model('Bis')->get($id);//商户基本信息
            $locationData=model('BisLocation')->get(['bis_id'=>$id,'is_main'=>1]);//总店信息
            $accountData=model('BisAccount')->get(['bis_id'=>$id,'is_main'=>1]);//商户账号信息
            $Citys=model('City')->getNomalCitysByParentId();
            $Categorys=model('Category')->getNormalFirstCategory();
            return $this->fetch('',[
                'citys'=>$Citys,
                'categorys'=>$Categorys,
                'bisData'=>$bisData,
                'locationData'=>$locationData,
                'accountData'=>$accountData,
            ]);
        }
    }
    public function delete($id)//删除商户
    {
        $data=[
            'id'=>$id,
        ];
        $validate=validate('Bis');
        if(!$validate->scene('status')->check($data)){
            $this->result($_SERVER['HTTP_REFERER'],0 ,$validate->getError());
        }
        //检查该店是否为总店
        $bisLocation=model('BisLocation')->get($data['id']);
        if($bisLocation['is_main']){
            $this->result($_SERVER['HTTP_REFERER'],0,'删除失败');
        };
        $res=model('BisLocation')->save(['status'=>-1],['id'=>$data['id'],'bis_id'=>$this->bisId]);
        if($res){
            $this->result($_SERVER['HTTP_REFERER'],1,'success');
        }else{
            $this->result($_SERVER['HTTP_REFERER'],0,'删除失败');
        }
    }
}