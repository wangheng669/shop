<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 17:37
 */

namespace app\admin\validate;


use think\Validate;

class City extends Validate
{
    protected $rule=[
        'name'=>'require|min:2',
        'uname'=>'require|min:2',
        'parent_id'=>'require',
    ];
}