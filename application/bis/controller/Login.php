<?php
namespace app\bis\controller;

use think\Controller;
use think\Validate;

class Login extends Controller
{
    public function index(){
        if(request()->isPost()){
            //获取数据
            $data=input('post.');
            //查询是否有此用户
            $validate=validate('Bis');
            if(!$validate->scene('login')->check($data))
            {
                $this->error($validate->getError());
            }
            $ret=model('BisAccount')->get(['username'=>$data['username']]);
            if(!$ret||$ret['status']!=1)
            {
                $this->error("该用户不存在或未通过审核");
            }
            if(md5($data['password'].$ret['code'])==$ret['password']){
                //更新最后登录ip和时间
                model('BisAccount')->updateById(['last_login_time'=>time(),'last_login_ip'=>$_SERVER["REMOTE_ADDR"]],$ret['id']);
                //数据保存全局变量中
                session('bisAccount',$ret,'bis');
                $this->success('登录成功',url('index/index'));
            }else{
                $this->error('密码错误');
            }
        }else {
            $account=session('bisAccount','','bis');
            if($account&&$account->id){
                return $this->redirect('index/index');
            }
            return $this->fetch();
        }
    }
}