<?php
namespace app\admin\controller;

use think\Controller;
use think\Model;

class Category extends Base
{
    private $obj;
    public function _initialize()//初始化操作
    {
        $this->obj=model('Category');
    }
    public function index()//显示栏目
    {
        $parent_id=input('get.parent_id',0,'intval');//获取父级栏目
        $categorys=$this->obj->getFirstCategorys($parent_id);
        return $this->fetch('',['categorys'=>$categorys]);
    }
    public function add()//添加分类
    {
        $categorys=$this->obj->getNormalFirstCategory();//一级分类
        return $this->fetch('',['categorys'=>$categorys]);
    }
    public function edit()//编辑分类
    {
        $id=input('get.id',0,'intval');
        if($id<1){
            $this->error('参数不合法');
        }
        $category=$this->obj->get($id);
        $categorys=$this->obj->getNormalFirstCategory();
        return $this->fetch('',[
            'category'=>$category,
            'categorys'=>$categorys,
        ]);
    }
    public function save()//保存分类
    {
        if(!request()->isPost()){//判断是否是post提交
            $this->error('请求非法');
        }
        $data=input('post.');
        $validate=validate('Category');
        if(!$validate->scene('add')->check($data)){
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
    public function update($data)//更新分类
    {
        $res=$this->obj->save($data,['id'=>$data['id']]);
        if($res){
            $this->success('更新成功');
        }else{
            $this->error('更新失败');
        }
    }

}
