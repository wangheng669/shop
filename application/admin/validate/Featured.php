<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 17:37
 */

namespace app\admin\validate;


use think\Validate;

class Featured extends Validate
{
    protected $rule=[
        'type|类型'=>'require|number',
        'title|标题'=>'require|min:2',
        'image|图片'=>'require',
        'url'=>'require',
        'description|内容'=>'require',
    ];
}