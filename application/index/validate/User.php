<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 19:56
 */

namespace app\index\validate;


use think\Validate;

class User extends Validate
{
    protected $rule=[
        'username|用户名'=>'require',
        'password|密码'=>'require|min:6',
    ];
}