<?php
namespace app\admin\validate;

use think\Validate;
class Category extends Validate{
    protected $rule=[
        ['name','require|max:10','分类名必须传递|分类名不能超过十个字符'],
        ['parent_id','number','分类必须为数字'],
        ['id','number'],
        ['status','number|in:-1,0,1','状态必须是数字|状态范围不合法'],
        ['listorder','number'],
    ];
    //场景设置
    protected $scene=[
        'add'=>['name','require|max:10','分类名必须传递|分类名不能超过十个字符'],
        'listorder'=>['id&listorder','number','id或排序必须是数字|id或排序范围不合法'],
        'status'=>['status','number|in:-1,0,1','状态必须是数字|状态范围不合法'],
    ];
}