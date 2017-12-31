<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/25
 * Time: 20:07
 */

namespace app\bis\controller;


use think\Controller;

class Base extends Controller
{
    public $account;
    //验证登录
    public function _initialize(){
        $isLogin=$this->isLogin();
        if(!$isLogin){
            $this->redirect('login/index');
        }
    }
    public function isLogin(){
        $user=$this->getLoginUser();
        if($user&&$user->id){
            return true;
        }
        return false;
    }
    //判断session是否存在
    public function getLoginUser(){
        if(!$this->account){
            $this->account=session('bisAccount','','bis');
        }
        return $this->account;
    }
    public function delete($id)//删除商户
    {
        $model=request()->controller();
        $data=[
            'id'=>$id,
        ];
        $validate=validate('Bis');
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
}