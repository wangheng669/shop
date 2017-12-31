<?php
namespace app\common\validate;
use think\Validate;
class Bis extends Validate
{
    protected $rule=[
        'name|商户名称' =>'require|max:25',
        'city_id|城市Id' =>'require',
        'se_city_id||城市Id' =>'require',
        'logo|缩略图' =>'require',
        'licence_logo|营业执照' =>'require',
        'bank_info|银行信息' =>'require',
        'bank_name|银行名称' =>'require',
        'bank_user|开户人姓名' =>'require',
        'faren|法人' =>'require',
        'faren_tel|法人电话' =>'require',
        'email|邮箱' =>'require',
        'tel|总店电话' =>'require',
        'contact|联系人' =>'require',
        'category_id|所属分类' =>'require',
        'address|地址' =>'require',
        'open_time|营业时间' =>'require',
        'username|用户名' =>'require|max:25',
        'password|密码' =>'require|min:8',
    ];
    //场景设置
    protected $scene=[
        'add'=>[
            'name',
            'city_id',
            'se_city_id',
            'logo',
            'licence_logo',
            'bank_info',
            'bank_name',
            'bank_user',
            'faren',
            'faren_tel',
            'email',
            'tel',
            'contact',
            'category_id',
            'address',
            'open_time',
            'username',
            'password',
        ],
        'status'=>[
            'status'=>['status','number|in:-1,0,1','状态必须是数字|状态范围不合法'],
        ],
        'login'=>[
          'username',
          'password',
        ],
        'addlocation'=>[
            'tel',
            'contact',
            'category_id',
            'bis_id',
            'category_path',
            'city_id',
            'city_path',
            'api_address',
            'open_time',
            'content',
        ]
    ];
}