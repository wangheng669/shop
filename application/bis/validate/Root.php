<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/30
 * Time: 21:21
 */

namespace app\bis\validate;


use think\Validate;

class Root extends Validate
{
    protected $rule=[
      'name|用户名'=>'require',
      'password1|密码'=>'require|min:6',
      'password2|密码'=>'require|min:6',
    ];
}