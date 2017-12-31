<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/30
 * Time: 10:46
 */

namespace app\index\validate;


use think\Validate;

class Pay extends Validate
{
    //支付验证
    protected $rule=[
        'id'=>'require|number',
        'count'=>'require|number|max:999|min:1',
    ];
}