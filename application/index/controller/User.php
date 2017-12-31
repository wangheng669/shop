<?php
namespace app\index\controller;

use think\Controller;
use think\Exception;
use think\Validate;

class User extends Controller
{
    public function login()//登录
    {
        $user=session('o2o_user','','o2o');
        if($user){
            $this->redirect('index/index');
        }
        return $this->fetch();
    }
    public function register()//注册
    {
        if(request()->isPost()){
            $data=input('post.');
            //进行用户输入校验
            $validate=validate('Register');
            if(!$validate->check($data)){
                $this->error($validate->getError());
            }
            //验证码校验
            if(!captcha_check($data['verifycode'])){
                $this->error("验证码错误");
            }
            //判断密码是否一致
            if($data['password']!=$data['repassword']){
                $this->error("两次密码不一致");
            }
            //校验用户是否存在
            $res=model('User')->where('username',$data['username'])->find();
            if($res){
                $this->error("用户已存在");
            }
            //注册用户密码加密
            $data['code']=mt_rand(100,10000);
            $data['password']=md5($data['password'].$data['code']);
            try{
                $res=model('User')->add($data);
            }catch (Exception $e){
                $this->error("信息重复");
            }
            if($res){
                $this->success("注册成功");
            }else{
                $this->error("注册失败");
            }
        }
        return $this->fetch();
    }
    public function logincheck(){
        //对用户数据进行检验
        $validate=validate('User');
        $data=input('post.');
        if(!$validate->check($data)){
            $this->error($validate->getError());
        }
        $user=model('User')->getUserByUsername($data['username']);
        if(!$user||$user->status!=1){
            $this->error("该用户不存在");
        }
        if(md5($data['password'].$user['code'])!=$user['password']){
            $this->error("密码错误");
        }
        //更新登录ip以及登录时间
        $data=model('User')->UpdateById(['last_login_time'=>time(),'last_login_ip'=>$_SERVER["REMOTE_ADDR"]],$user['id']);
        session('o2o_user',$user,'o2o');
        //跳转首页
        $this->success("登陆成功",'index/index');
    }
    public function loginout(){
        session(null,'o2o');
        //跳转首页
        $this->redirect('user/login');
    }
}