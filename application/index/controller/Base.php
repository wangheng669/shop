<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 22:33
 */

namespace app\index\controller;


use think\Controller;

class Base extends Controller
{
    protected $account='';
    protected $city='';
    public function _initialize(){
        //获取城市数据
        $citys=model('City')->getTwoCitys();
        $this->getCitys($citys);
        $this->assign('citys',$citys);
        $this->assign('city',$this->city);
        $this->assign('controller',strtolower(request()->controller()));
        $this->assign('user',$this->getLoginUser());
        $this->getRecommendCats();
    }
    public function getCitys($citys){
        foreach ($citys as $v){
            if($v['is_default']==1){
                $defaultuname=$v['uname'];
                break;
            }
        }
        //判断主城市是否存在
        $defaultuname=$defaultuname?$defaultuname:'nanchang';
        //判断用户是否传入参数
        if(session('cityuname','','o2o')&&!input('get.cityuname')){//在有值并且没有传入参数时
            $cityuname=session('cityuname','','o2o');
        }else{
            $cityuname=input('get.cityuname',$defaultuname,'trim');
            session('cityuname',$cityuname,'o2o');
        }
        $this->city=model('City')->where(['uname'=>$cityuname])->find();
    }
    //判断session是否存在
    public function getLoginUser(){
        if(!$this->account){
            $this->account=session('o2o_user','','o2o');
        }
        return $this->account;
    }
    //获取分类的数据
    public function getRecommendCats()
    {
        $parentIds=$sedCatAttr=[];//保存一级分类和二级分类数据
        $cats=model('Category')->getNormalRecommendCategoryByParentId(0,5);//获取一级分类下的所有数据

        //遍历一级数据的id,存储所有一级栏目的ID
        foreach($cats as $cat){
            $parentIds[]=$cat['id'];
        }
        //获取二级分类的数据
        $sedCats=model('Category')->getNormalCategoryByParentId($parentIds);
        /*//遍历二级分类的数据,通过父类id保存对应一级数据
        foreach($sedCats as $sedCat){
            $sedCatAttr['parent_id']=[
              'id'=>$sedCat['id'],
              'name'=>$sedCat['name'],
            ];
        }*/

        $this->assign('cats',$cats);
        $this->assign('sedCats',$sedCats);
    }
}