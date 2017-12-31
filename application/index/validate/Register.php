<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 15:57
 */

namespace app\index\validate;
use think\Validate;

class Register extends Validate
{
    protected $rule=[
        'username|用户名' =>'require',
        'email|邮箱' =>'require',
        'mobile|手机号' =>'require|number',
        'password|密码' =>'require',
        'verifycode|验证码' =>'require',
    ];
}