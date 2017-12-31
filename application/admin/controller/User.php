<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/30
 * Time: 14:03
 */

namespace app\admin\controller;


use think\Controller;

class User extends Base
{
    public function index(){//返回列表页
        $data=input('post.');
        $sdata=[];
        if(!empty($data['start_time'])&&!empty($data['end_time'])&&strtotime($data['start_time'])<strtotime($data['end_time'])){
            $sdata['create_time']=[
                ['gt',strtotime($data['start_time'])],
                ['lt',strtotime($data['end_time'])
                ],
            ];
        }
        if(!empty($data['username'])){
            $sdata['username']=['like','%'.$data['username'].'%',];
        }
        $status=['neq',-1];
        $users=model('User')->getUsersById($sdata,$status);
        return $this->fetch('',[
            'users'=>$users,
        ]);
    }
    public function dellist(){
        $status=['eq',-1];
        $sdata=[];
        $users=model('User')->getUsersById($sdata,$status);
        return $this->fetch('',[
            'users'=>$users,
        ]);
    }
}