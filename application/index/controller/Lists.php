<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28
 * Time: 13:03
 */

namespace app\index\controller;


class Lists extends Base
{
    public function index(){
        $firstId=$sedCategorys=[];//
        $id=input('get.id',0,'intval');
        //遍历一级栏目的ID
        $categorys=model('Category')->getNormalFirstCategory();
        $data=[];//条件
        foreach ($categorys as $category){
            $firstId[]=$category['id'];
        }
        if(in_array($id,$firstId)){//是一级栏目
            $categoryId=$id;
            $data['category_id']=$id;
        }else if($id){//是二级栏目
            $data['se_category_id']=$id;
            $category=model('Category')->get($id);
            if(!$category||$category['status']!=1){
                $this->error('商品不存在');
            }
            $categoryId=$category['parent_id'];
            //向上查找父级分类
        }else{//没有值
            //返回所有父分类以及第一个分类下的子分类
            $categoryId=0;
        }
        $sedCategorys=[];
        if($categoryId){
            $sedCategorys=model('Category')->getNormalFirstCategory($categoryId);
        }

       $category=model('Category')->getNormalCategoryByParentId($sedCategorys);
        $order=input('order','default');
        $orderlist=['new','price','count'];
        if(!in_array($order,$orderlist)){
            $order='default';
        }
        //传递城市
        $data['city_id']=$this->city['id'];
        $categorydata=model('Deal')->getCatgoryByOrder($data,$order);//获取该分类下的商品
        return $this->fetch('', [
            'categorys'=>$categorys,
            'sedCategorys'=>$sedCategorys,
            'id'=>$id,
            'parentId'=>$categoryId,
            'categorydata'=>$categorydata,
            'order'=>$order,
            ]);
    }
}