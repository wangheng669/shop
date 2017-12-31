<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 16:19
 */
namespace app\index\model;
use app\common\model\BaseModel;
class User extends BaseModel
{
    public function add($data){
        $data['status']=1;
        return $this->data($data)->allowField(true)->save();
    }
    public function getUserByUsername($username){
        return $this->where('username',$username)->find();
    }
}