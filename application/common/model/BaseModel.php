<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/23
 * Time: 23:55
 */
namespace app\common\model;
use think\Model;
class BaseModel extends Model
{
    public function add($data)
    {
        $data['status']=0;
        $this->save($data);
        return $this->id;
    }
    //更新登录时间以及ip
    public function UpdateById($data,$id){
       return $this->allowField(true)->save($data,['id'=>$id]);
    }
}