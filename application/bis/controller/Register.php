<?php
namespace app\bis\controller;
use think\Controller;
class Register extends Controller
{
    private $obj;
    public function _initialize()//初始化操作
    {
        $this->obj=model('City');
    }
    public function index(){//展示注册页
        $Citys=$this->obj->getNomalCitysByParentId();
        $Categorys=model('Category')->getNormalFirstCategory();
        return $this->fetch('',['Citys'=>$Citys,'Categorys'=>$Categorys]);
    }
    public function add()
    {
        if (!request()->isPost()) {
            $this->error('请求错误');
        }
        // 获取表单的值
        $data = input('post.');
        //检验数据
        $validate = validate('Bis');
        if (!$validate->scene('add')->check($data)) {
            $this->error($validate->getError());
        }
        //获取经纬度
        $lnglat=\Map::getLngLat($data['address']);
        if(empty($lnglat)||$lnglat['status']!=0){
            $this->error('无法获取数据,或者匹配地址不精确');
        }
        //商户基本信息
        $result=model('BisAccount')->get(['username'=>$data['username']]);
        if($result){
            $this->error('用户已存在');
        }
        $bisData=[
          'name'=>$data['name'],
            'city_id'=>$data['city_id'],
            'city_path'=>empty($data['se_city_id'])?$data['city_id']:$data['city_id'].','.$data['se_city_id'],
            'logo'=>$data['logo'],
            'licence_logo'=>$data['licence_logo'],
            'description'=>empty($data['description'])?'':html_entity_decode($data['description']),
            'bank_info'=>$data['bank_info'],
            'bank_name'=>$data['bank_name'],
            'bank_user'=>$data['bank_user'],
            'faren'=>$data['faren'],
            'faren_tel'=>$data['faren_tel'],
            'email'=>$data['email'],
        ];
        //商户基本信息添加
        $bisId=model('Bis')->add($bisData);
        //总店信息
        //由于$data['se_category_id']是一个数组形式所以要通过,进行分割
        if(!empty($data['se_category_id']))
        {
            $data['se_category_id']=implode('|',$data['se_category_id']);
        }

        $locationData=[
            'name'=>$data['name'],
            'logo'=>$data['logo'],
            'tel' =>$data['tel'],
            'contact' =>$data['contact'],
            'category_id' =>$data['category_id'],
            'bis_id' =>$bisId,
            'category_path'=>empty($data['se_category_id'])?'':$data['category_id'].','.$data['se_category_id'],
            'city_id'=>$data['city_id'],
            'city_path'=>empty($data['se_city_id'])?$data['city_id']:$data['city_id'].','.$data['se_city_id'],
            'api_address' =>$data['address'],
            'open_time' =>$data['open_time'],
            'content'=>empty($data['content'])?'':$data['content'],
            'is_main'=>1,
            'xpoint'=>empty($lnglat['result']['location']['lng'])?'':$lnglat['result']['location']['lng'],
            'ypoint'=>empty($lnglat['result']['location']['lat'])?'':$lnglat['result']['location']['lat'],
        ];
        //总店信息添加
        $locationd=model('BisLocation')->add($locationData);
        //账户信息
        $data['code']=mt_rand(100,10000);
        $accountData=[
            'bis_id'=>$bisId,
          'username'=>$data['username'],
            'code'=>$data['code'],
          'password'=>md5($data['password'].$data['code']),
            'is_main'=>1,
        ];
        $accountId=model('BisAccount')->add($accountData);
        if(!$accountId){
            $this->error('申请失败');
        }
        //发送邮件
        $url=request()->domain().url('bis/register/waiting',['id'=>$bisId]);
        $title='o2o注册申请通知';
        $content="你提交的申请需要等待审核,点击查看审核状态<a href=".$url." target=='_blank'>查看链接</a>查看审核状态";
        \phpmailer\Email::send($data['email'],$title,$content);

        $this->success('申请成功',url('register/waiting',['id'=>$bisId]));
    }
    public function waiting($id)
    {
        if(empty($id)){
            $this->error('参数错误');
        }
        $detail=model('BisAccount')->get($id);
        return $this->fetch('',['detail'=>$detail]);
    }
}