<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/25
 * Time: 13:36
 */

namespace app\admin\controller;


use think\Controller;

class Bis extends Controller
{
    private $obj;
    public function _initialize()//初始化操作
    {
        $this->obj=model('Bis');
    }
    public function index()//返回主页模板
    {
        $status=1;
        $Bis=$this->obj->getBisByStatus($status);
        return $this->fetch('',['bis'=>$Bis]);
    }
    public function apply()//返回审核列表页
    {
        $status=0;
        $Bis=$this->obj->getBisByStatus($status);
        return $this->fetch('',['bis'=>$Bis]);
    }
    public function dellist()//返回删除商户页
    {
        $status=-1;
        $Bis=$this->obj->getBisByStatus($status);
        return $this->fetch('',['bis'=>$Bis]);
    }
    public function detail()//返回商户信息页
    {
        $id=input('get.id');
        $bisData=model('Bis')->get($id);//商户基本信息
        $locationData=model('BisLocation')->get(['bis_id'=>$id,'is_main'=>1]);//总店信息
        $accountData=model('BisAccount')->get(['bis_id'=>$id,'is_main'=>1]);//商户账号信息
        $Citys=model('City')->getNomalCitysByParentId();
        $Categorys=model('Category')->getNormalFirstCategory();
        return $this->fetch('',[
            'Citys'=>$Citys,
            'Categorys'=>$Categorys,
            'bisData'=>$bisData,
            'locationData'=>$locationData,
            'accountData'=>$accountData,
            ]);
    }
    public function editstatus($id,$status)//修改状态
    {
        $data=[
            'id'=>$id,
            'status'=>$status
        ];
        $validate=validate('Bis');
        if(!$validate->scene('status')->check($data)){
            $this->result($_SERVER['HTTP_REFERER'],0 ,$validate->getError());
        }
        if($status==1){
            $res=$this->obj->save(['status'=>0],['id'=>$data['id']]);
            $location=model('BisLocation')->save(['status'=>0],['bis_id'=>$data['id']],['is_main'=>1]);
            $account=model('BisAccount')->save(['status'=>0],['bis_id'=>$data['id']],['is_main'=>1]);
        }else if($status==0){
            $res=$this->obj->save(['status'=>1],['id'=>$id]);
            $location=model('BisLocation')->save(['status'=>1],['bis_id'=>$data['id']],['is_main'=>1]);
            $account=model('BisAccount')->save(['status'=>1],['bis_id'=>$data['id']],['is_main'=>1]);
        }
        $email=$this->obj->where('id',$data['id'])->find();
        $url=request()->domain().url('bis/register/waiting',['id'=>$data['id']]);
        $content="你提交的申请有更新,点击查看审核状态<a href=".$url." target=='_blank'>查看链接</a>查看审核状态";
        if($res&&$account&&$location){
            \phpmailer\Email::send($email['email'],'申请状况有更新',$content);
            $this->result($_SERVER['HTTP_REFERER'],1,'success');
        }else{
            $this->result($_SERVER['HTTP_REFERER'],0,'修改失败');
        }
    }

}