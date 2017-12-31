<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/30
 * Time: 14:06
 */

namespace app\common\model;


use think\Model;

class User extends Model
{
    public function getUsersById($data,$status=0){
        $data['status']=$status;
        $order=[
            'id'=>'desc'
        ];
        return $this->where($data)->order($order)->select();
    }
}