<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$ac = $_GPC['ac']; 

$Muser=m('tools')->getMuser($_W['uid']);
         
if ($op == 'display') {
   $puinfo=p('bonus')->getMerch($_W['uid']);
   $Muser=m('tools')->getAccountInfo($puinfo['openid']);

   if ($_W['isajax']) {     
        $data=$_GPC['data'];
        // $data['province']=$_GPC['reside']['province'];
        // $data['city']=$_GPC['reside']['city'];     	 	         
        // $data['area']=$_GPC['reside']['district'];
        //修改账号信息	 		 	 	 		 	 	
        $re=m('tools')->setAccountInfo($data,array('openid'=>$Muser['openid'],'uniacid'=>$_W['uniacid']));

        if ($re) {
            show_json(1,'更新成功');
        }else{
            show_json(0,'更新失败');
        }             
   }
}else if($op == 'message'){    
  $set=m('tools')->getset();
  $fee=number_format($set['bartact']['msg'],2);
  if ($ac == 'submit') {             
    $num=intval($_GPC['num']);
    $url=$this->createPluginWebUrl('match/info',array('op'=>'message'));
    empty($num) && message('充值数量不能为空','','warning');

    $merch=pdo_fetch('select * from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and uid= :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$_W['uid']));

    $member=m('member')->getMember($merch['openid']);
    $fee=floatval($num * $fee);           
    $credit=m('member')->getCredit($member['openid'],'credit2');
    $fee > $credit && message('余额不足,充值短信失败','','warning');

    m('member')->setCredit($member['openid'],'credit2',-$fee);

    $data=array(
      'msgnum'=>$num+$Muser['msgnum']
    );          
    $params=array(
      'uniacid'=>$_W['uniacid'],     
      'openid'=>$member['openid']    
    );                                   
    $re=m('match')->setMuser($data,$params);

    $log=array(
      'uniacid'=>$_W['uniacid'],
      'openid'=>$member['openid'],
      'type'=>2,     
      'channel'=>2,    
      'num'=>$num,
      'fee'=>$fee,         
      'ctime'=>time()   
    );     
    pdo_insert('sz_yi_match_recharge_msg_log',$log);
    if ($re) {     
      message('充值短信成功',$url,'success');    
    }                              
    message('充值短信失败',$url,'warning');     
  }                    

}
include $this -> template('info');
