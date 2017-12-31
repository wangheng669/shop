<?php
namespace app\index\controller;

class Detail extends Base
{
    public function index($id){
        if(!intval($id)){
            $this->error('参数错误');
        }
        $deal=model('Deal')->get($id);
        if(!$deal||$deal['status']!=1){
            $this->error('该商品不存在');
        }
        //获取时间
        $timedata='';
        $flag=0;//默认可以抢购
        if($deal['start_time']>time()){
            $flag=1;
            $dtime=$deal['start_time']-time();//计算相差的毫秒数
            $timedata='';//存储保存之后的字符串
            $d=floor($dtime/(3600*24));//获取天数
            if($d){//向下取整的情况下天数如果是0那么不算天数
                $timedata=$d."天 ";
            }
            $h=floor($dtime%(3600*24)/3600);
            if($h){
                $timedata.=$h."小时 ";
            }
            $m=floor($dtime%(3600*24)%3600/60);
            if($m){
                $timedata.=$m."分 ";
            }
            $s=floor($dtime%(3600*24)%3600%60);
                $timedata.=$s."秒 ";
        }
        //获取分店信息
        $location=model('BisLocation')->getDealLocations($deal['location_ids']);
        //获取商户信息
        $bis=model('Bis')->getBisByDeal($deal['bis_id'],1);
        return $this->fetch('',[
            'deal'=>$deal,//标题及商品名称
            'timedata'=>$timedata,//返回时间
            'flag'=>$flag,//flag
                'mapstr'=>$deal['xpoint'].','.$deal['ypoint'],
                'location'=>$location,
                'bis'=>$bis,
            ]
        );
    }
}