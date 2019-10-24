<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'banner';
$ac =$_GPC['ac'];

$plugin_diyform = p('diyform');
$totals = array();
$openid=uid2openid($_W['uid']);     //获取商家openid

$muser=m('match')->getVisitingInfo($openid);
                     
if ($op == 'banner') {      
    
    $banner=unserialize($muser['banner']);	 
    if ($_GPC['debug']) {
        print_r($banner);
        exit;
    }
    $list=array();

    foreach ($banner as $key => $value) {
        $list[$key]['thumb']=$value;
    }

    if ($ac == 'sub') {                   
        $thumb=$_GPC['thumb'];
        empty($thumb) && message('请上传轮播图!','','warning');    
        if ($_GPC['num'] == '') {       
            $banner[]=$thumb;               
        }else{          
            $banner[$_GPC['num']]=$thumb;
        }        

        $data['banner']=serialize($banner);         
        $re=m('match')->setVisitingInfo($data,$openid); 

        if ($re) {       
            message('修改成功',$this->createPluginWebUrl('match/card',array('op'=>'banner')),'success');            
        }                
            message('修改失败','','warning');            
    }elseif ($ac == 'delete'){                     
        $num=$_GPC['num'];
        unset($banner[$num]);
        $data=array();
        $data['banner']=serialize($banner);
        $re=m('match')->setVisitingInfo($data,$openid); 
        if ($re) {
            message('修改成功',$this->createPluginWebUrl('match/card',array('op'=>'banner')),'success');            
        }
            message('修改失败','','warning');            
    }                                                                                          

}else if ($op == 'add'){                 
   $Muser=m('tools')->getMuser($_W['uid']);

   if ($_W['isajax']) {             
        $data=$_GPC['data'];                          
        $data['province']=$_GPC['reside']['province'];              
        $data['city']=$_GPC['reside']['city'];                            
        $data['area']=$_GPC['reside']['district'];
        $re=m('match')->setVisitingInfo($data,$openid);
        if ($re) {
            show_json(1,'更新成功');
        }else{
            show_json(0,'更新失败');
        }             
   }                       
    
}

include $this -> template('card');      