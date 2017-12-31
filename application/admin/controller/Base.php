<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 13:20
 */

namespace app\admin\controller;


use think\Controller;

class Base extends Controller
{
    public function editstatus($id,$status){//修改状态
        $data=[
            'id'=>$id,
            'status'=>$status
        ];
        $validate=validate('Category');
        if(!$validate->scene('status')->check($data)){
            $this->result($_SERVER['HTTP_REFERER'],0 ,$validate->getError());
        }
        $model=request()->controller();
        if($status==1){
            $res=model($model)->save(['status'=>0],['id'=>$data['id']]);
        }else if($status==0){
            $res=model($model)->save(['status'=>1],['id'=>$data['id']]);
        }
        if($res){
            $this->result($_SERVER['HTTP_REFERER'],1,'success');
        }else{
            $this->result($_SERVER['HTTP_REFERER'],0,'修改失败');
        }
    }
    public function delete($id)//删除分类
    {
        $model=request()->controller();
        $data=[
            'id'=>$id,
        ];
        $validate=validate('Category');
        if(!$validate->scene('status')->check($data)){
            $this->result($_SERVER['HTTP_REFERER'],0 ,$validate->getError());
        }
        $res=model($model)->save(['status'=>-1],['id'=>$data['id']]);
        if($res){
            $this->result($_SERVER['HTTP_REFERER'],1,'success');
        }else{
            $this->result($_SERVER['HTTP_REFERER'],0,'删除失败');
        }
    }
    public function listorder($id,$listorder)//排序
    {
        $model=request()->controller();
        $res=model($model)->save(['listorder'=>$listorder],['id'=>$id]);
        if($res){
            $this->result($_SERVER['HTTP_REFERER'],1,'success');
        }else{
            $this->result($_SERVER['HTTP_REFERER'],0,'更新失败');
        }
    }
}