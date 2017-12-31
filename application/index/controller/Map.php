<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28
 * Time: 23:13
 */

namespace app\index\controller;


use think\Controller;

class Map extends Controller
{
    public function getMapImage($data){
        return \Map::staticimage($data);
    }
}