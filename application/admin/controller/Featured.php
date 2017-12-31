<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 23:53
 */

namespace app\admin\controller;


use think\Controller;

class Featured extends Base
{
    private $obj;
    public function _initialize()//初始化操作
    {
        $this->obj=model('Featured');
    }
    public function index(){
        // 获取推荐位类别
        $types = config('featured.featured_type');
        $type = input('get.type', 0 ,'intval');
        // 获取列表数据
        $featured = $this->obj->getFeaturedsByType($type);
        return $this->fetch('', [
            'types' => $types,
            'featured' => $featured,
            'type'=>$type,
        ]);
    }
    public function add(){
        if(request()->isPost()){
            $data=input('post.');
            $validate=validate('Featured');
            if(!$validate->check($data)){
                $this->error($validate->getError());
            }
            $data=[
              'type'=>$data['type'],
              'title'=>$data['title'],
              'image'=>$data['image'],
              'url'=>$data['url'],
              'description'=>html_entity_decode($data['description']),
            ];
            $res=$this->obj->save($data);
            if($res){
                $this->success('添加成功','featured/index');
            }else{
                $this->error('添加失败');
            }
        }
        $types=config('featured.featured_type');
        return $this->fetch('',[
            'types'=>$types
            ]
        );
    }
}