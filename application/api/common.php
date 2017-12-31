<?php

namespace api;
//返回值
function show($status=0,$success='',$data=[])//状态,信息,数据
{
    return [
      'status'=>intval($status),
        'message'=>$success,
        'data'=>$data,
    ];
}