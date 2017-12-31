<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 12:15
 */

namespace app\admin\controller;


use think\Controller;

class Location extends Controller
{
    private $obj;
    public function _initialize()//初始化操作
    {
        $this->obj=model('BisLocation');
    }
    public function index(){
        $status=1;
        $BisLocation=$this->obj->getLocations($status);
        return $this->fetch('',['bisLocation'=>$BisLocation]);
    }
    public function apply()//返回门店审核列表页
    {
        $status=0;
        $BisLocation=$this->obj->getLocations($status);
        return $this->fetch('',['bisLocation'=>$BisLocation]);
    }
    public function dellist(){
        $status=-1;
        $BisLocation=$this->obj->getLocations($status);
        return $this->fetch('',['bisLocation'=>$BisLocation]);
    }
    public function detail(){
        $id=input('get.id');
        $locationData=model('BisLocation')->get(['id'=>$id]);//分店信息
        $Citys=model('City')->getNomalCitysByParentId();//所属城市
        $Categorys=model('Category')->getNormalFirstCategory();//所属分类
        return $this->fetch('',[
            'citys'=>$Citys,
            'categorys'=>$Categorys,
            'locationData'=>$locationData,
        ]);
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
        $res=$this->obj->save(['status'=>-1],['id'=>$data['id']]);
        if($res){
            $this->result($_SERVER['HTTP_REFERER'],1,'success');
        }else{
            $this->result($_SERVER['HTTP_REFERER'],0,'删除失败');
        }
    }
    public function editstatus()//修改状态
    {
        $data=input('post.');
        $validate=validate('Bis');
        if(!$validate->scene('status')->check($data)){
            $this->result($_SERVER['HTTP_REFERER'],0 ,$validate->getError());
        }
        if($data['status']==1){
            $res=$this->obj->save(['status'=>0],['id'=>$data['id']]);
        }else if($data['status']==0){
            $res=$this->obj->save(['status'=>1],['id'=>$data['id']]);
        }
        $email=model('Bis')->where('id',$data['id'])->find();
        $url=request()->domain().url('bis/register/waiting',['id'=>$data['id']]);
        $content="你提交的申请有更新,点击查看审核状态<a href=".$url." target=='_blank'>查看链接</a>查看审核状态";
        if($res){
            \phpmailer\Email::send($email['email'],'申请状况有更新',$content);
            $this->result($_SERVER['HTTP_REFERER'],1,'success');
        }else{
            $this->result($_SERVER['HTTP_REFERER'],0,'修改失败');
        }
    }
}