<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/23
 * Time: 23:55
 */
namespace app\common\model;
use think\Model;
class BisAccount extends BaseModel
{
    public function updateById($data,$id){
        //执行更新操作并过滤非数据库中的字段
        $this->allowField(true)->save([
            'last_login_time'=>$data['last_login_time'],
            'last_login_ip'=>$data['last_login_ip']
        ],['id'=>$id]);
    }
    public function getNormalRootById($data,$status){
        $data['status']=$status;
        $order=[
          'id'=>'desc',
        ];
        return $this->where($data)->order($order)->select();
    }
}