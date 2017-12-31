<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 11:09
 */

namespace app\common\model;


use think\Model;

class Featured extends Model
{
    public function getFeaturedsByType($type) {
        $data = [
            'type' => $type,
            'status' => ['neq', -1],
        ];

        $order = ['id'=>'desc'];

        $result = $this->where($data)
            ->order($order)
            ->paginate();
        return $result;
    }
}