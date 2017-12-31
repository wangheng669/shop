<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/30
 * Time: 21:07
 */

namespace app\bis\controller;


use think\Controller;

class Root extends Controller
{

    private $bisData;//初始化
    public function _initialize()
    {
         $this->bisData=session('bisAccount','','bis');
    }
    public function index(){
        //获取该商户下的所有管理员
        $data=[
            'is_main'=>0,
            'bis_id'=>$this->bisData['id'],
        ];
        $bisRoot=model('BisAccount')->getNormalRootById($data,1);
        return $this->fetch('',[
            'bisroot'=>$bisRoot,
        ]);
    }
    public function add(){

        if(request()->post()){
            $rootData=input('post.');
            $validate=validate('Root');
            //检测名字是否输入
            if(!$validate->check($rootData)){
                $this->error($validate->getError());
            }
            if($rootData['password1']!=$rootData['password2']){
                $this->error("密码不一致!!");
            }
            $username=$rootData['name'];
            $code=mt_rand(100,10000);
            $password=md5($rootData['password1'].$code);
            $data=[
                'username'=>$username,
                'password'=>$password,
                'bis_id'=>$this->bisData['id'],
                'status'=>1,
                'code'=>$code,
                'is_main'=>0,
            ];
            $res=model('BisAccount')->save($data);
            if($res){
                $this->success("添加成功");
            }else{
                $this->error("添加失败");
            }
        }
        return $this->fetch();
    }
}