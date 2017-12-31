<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//审核状态
function status($status){
    switch ($status){
        case 1:
            $str="<span class='label label-success radius'>正常</span>";
            return $str;
        case 0:
            $str="<span class='label label-warning radius'>待审核</span>";
            return $str;
        case -1:
            $str="<span class='label label-danger radius'>删除</span>";
            return $str;
    }
}
//订单状态
function pay_status($status){
    switch ($status){
        case 1:
            $str="<span class='label label-success radius'>已发货</span>";
            return $str;
        case 0:
            $str="<span class='label label-warning radius'>待发货</span>";
            return $str;
        case -1:
            $str="<span class='label label-danger radius'>未付款</span>";
            return $str;
    }
}
//是否是总店
function ismain($isMain){
    switch ($isMain){
        case 1:
            $str="<span class='label label-success radius'>是</span>";
            return $str;
        case 0:
            $str="<span class='label label-warning radius'>不是</span>";
            return $str;
    }
}
//Url转换
function doCurl($url,$type=0,$data=[]){
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,0);
    if($type==1){
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
    }
    $output=curl_exec($ch);
    curl_close($ch);
    return $output;
}
//注册状态
function bisRegister($status){
    switch ($status){
        case 1:
            $str="审核已通过";
            return $str;
        case 0:
            $str="待审核,请关注邮件";
            return $str;
        case -1:
            $str="审核未通过,请重新申请";
            return $str;
    }
}
//分页
function pagination($obj){
    $params=request()->param();
    return '<div class="tp5-page">'.$obj->appends($params)->render().'</div>';
}
//城市
function getSeCityName($path){
    if(empty($path)){
        return "";
    }
    $arr=explode(',',$path);
    $result=model('City')->get($arr[1]);
    return $result['name'];
}
//分类
function getSeCategoryName($path){
    if(empty($path)){
        return "";
    }
    $arr=explode(',',$path);
    $result=model('Category')->get($arr[1]);
    return '<input type="checkbox" />'.$result['name'];
}
//排序
function listorder($id,$listorder)
{
    $res=$this->obj->save(['listorder'=>$listorder],['id'=>$id]);
    if($res){
        $this->result($_SERVER['HTTP_REFERER'],1,'success');
    }else{
        $this->result($_SERVER['HTTP_REFERER'],0,'更新失败');
    }
}
//修改状态
function editstatus($id,$status){
    $data=[
        'id'=>$id,
        'status'=>$status
    ];
    $validate=validate('Category');
    if(!$validate->scene('status')->check($data)){
        $this->result($_SERVER['HTTP_REFERER'],0 ,$validate->getError());
    }
    if($status==1){
        $res=$this->obj->save(['status'=>0],['id'=>$data['id']]);
    }else if($status==0){
        $res=$this->obj->save(['status'=>1],['id'=>$data['id']]);
    }
    if($res){
        $this->result($_SERVER['HTTP_REFERER'],1,'success');
    }else{
        $this->result($_SERVER['HTTP_REFERER'],0,'修改失败');
    }
}
//分店计数
function countlocation($ids,$type=0){
    $arr=explode(',',$ids);
    if($type==1){
        return $arr[0];
    }
    return count($arr);
}
//随机订单号 意义:最大限度防止订单号重复
function setOrderSn(){
    list($t1,$t2)=explode(' ',microtime());
    $t3=explode('.',$t1*10000);
    return $t2.$t3[0].(rand(10000,99999));
}