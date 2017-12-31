<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 16:41
 */

namespace app\admin\controller;


use think\Controller;

class City extends Base
{

    private $obj;
    public function _initialize()//初始化操作
    {
        $this->obj=model('City');
    }
    public function index(){
        $citys=model('City')->getTwoCitys();//获取城市列表
        return $this->fetch('',[
            'citys'=>$citys,
        ]);
    }
    public function add(){
        $citys=model('City')->getNomalCitysByParentId();//获取城市列表
        return $this->fetch('',[
            'citys'=>$citys,
        ]);
    }
    public function save()//保存城市
    {
        if(!request()->isPost()){//判断是否是post提交
            $this->error('请求非法');
        }
        $data=input('post.');
        $validate=validate('City');
        if(!$validate->check($data)){
            $this->error($validate->getError());
        }
        if(!empty($data['id'])){
            $this->update($data);
        }
        $res=$this->obj->add($data);
        if($res){
            $this->success('新增成功');
        }else{
            $this->error('新增失败');
        }
    }
}