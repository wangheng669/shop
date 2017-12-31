<?php
namespace app\index\controller;

use think\Controller;

class Index extends Base
{
    public function index()
    {
        //获取推荐位数据
        $featured1=model('Featured')->getFeaturedsByType(0);//首页大图
        $featured2=model('Featured')->getFeaturedsByType(1);//左侧图
        //获取首页商品信息 [美食]
        $id=['1'];//要在首页展示的信息
        $datas=model('Deal')->getNormalDealByCategoryCityId($id,$this->city['id']);
        //获取四个子分类
        $categorys=model('Category')->getNormalRecommendCategoryByParentId(1,4);
        return $this->fetch('',
            [
                'datas'=>$datas,
                'categorys'=>$categorys,
                'category'=>empty($datas[0]['category_id'])?'':$datas[0]['category_id'],
                'featured1'=>$featured1,
                'featured2'=>$featured2,
            ]);
    }
}
