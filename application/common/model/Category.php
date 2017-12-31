<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/21
 * Time: 23:45
 */

namespace app\common\model;

use think\Model;

class Category extends Model
{
/*    protected function setStatusAttr($value)
    {
        $status = ['删除'=>-1, '待审核' => 0, '正常' => 1,];
        return $status[$value];
    }*/
    protected $type = [
        // 设置birthday为时间戳类型（整型）
        'create_time' => 'timestamp:Y/m/d',
        'update_time' => 'timestamp:Y/m/d',
    ];
    public function add($data)//添加操作
    {
        $data['status']=1;
        //$data['create_time']=time();
        return $this->save($data);
    }
    public function getFirstCategorys($parent_id=0){//获取一级列表
        $data=[
          'parent_id'=>$parent_id,
        ];
        $order=[
            'listorder'=>'desc',
            'id'=>'desc'
        ];
        return $this->where($data)->order($order)->paginate(3);
    }
    public function getNormalFirstCategory($parent_id=0){//获取对应父级栏目
        $data=[
          'status'=>1,
            'parent_id'=>$parent_id,
        ];
        $order=[
          'id'=>'desc'
        ];
        return $this->where($data)->order($order)->select();
    }
    //获取一级分类
    public function getNormalRecommendCategoryByParentId($parent_id,$limit=5){
        $data=[
          'parent_id'=>$parent_id,
            'status'=>1,
        ];
        $order=[
            'listorder'=>'desc',
            'id'=>'desc',
        ];
        $result=$this->where($data)->order($order)->limit($limit)->select();
        return $result;
    }
    //获取一级分类下的所有数据
    public function getNormalCategoryByParentId($ids){
            $data=[
            'parent_id'=>['in',implode(',',$ids)],
            'status'=>1,
        ];
        $order=[
            'listorder'=>'desc',
            'id'=>'desc',
        ];
        $result=$this->where($data)->order($order)->select();
        return $result;
    }
}