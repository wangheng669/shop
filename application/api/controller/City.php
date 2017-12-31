<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/23
 * Time: 12:55
 */

namespace app\api\controller;


use function api\show;
use think\Controller;

class City extends Controller
{
    private $obj;
    public function _initialize()//初始化操作
    {
        $this->obj=model('City');
    }
    public function getCitysParentId(){
        $id=input('post.id');
        if(!$id){
            $this->error('ID不存在');
        }
        $result=$this->obj->getNomalCitysByParentId($id);
        if(!$result){
            return show(0,'error');
        }
        return show(1,'success',$result);
    }
}