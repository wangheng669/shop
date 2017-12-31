<?php
namespace app\bis\controller;

use think\Controller;

class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }
    public function logout(){
        //清空session返回login页面
        session(null,'bis');
        return $this->redirect('login/index');
    }
}
