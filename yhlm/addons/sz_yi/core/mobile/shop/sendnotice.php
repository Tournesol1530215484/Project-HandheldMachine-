<?php

global $_W, $_GPC;

if ($_GPC['mark'] != 'notice') {
    die('非法参数!');
}
                                   
if (date('H') == '20') {
	$time=m('time')->tomorrow();  //晚上八点找明天开始的活动
	$day='明天';                              
    $type=2;
}else if(date('H') == '07'){
    $time=m('time')->today();   //早上7点找今天开始的活动
    $day='今天';
    $type=1;
}else{
    exit('请在合适时间访问!');
}                
    $exists=pdo_fetch('select * from '.tablename('sz_yi_sendnotice_log').' where uniacid = :uniacid and date = :date and type = :type ',array(':type'=>$type,':uniacid'=>$_W['uniacid'],':date'=>date('Ymd')));
    
    !$time && exit('非法时间!');     //时间不存在则退出
    $exists && exit('当前时间点活动已提醒');      //已经发送过提醒则退出

    // $time=m('time')->today();

	$params=array(                 
        ':uniacid'=>$_W['uniacid'],
		':stime'=>$time[0],        
		':etime'=>$time[1],                        
	);         
	$condition=' and a.uniacid = :uniacid and s.status = 1 and a.stime > :stime and a.stime <= :etime ';
	$sql='select a.id,a.title,a.stime,a.address,s.openid ,s.data from '.tablename('sz_yi_activity').' a left join '.tablename('sz_yi_activity_signup').' s on a.id = s.actid where 1 ';
	$sql.=$condition;

	$list=pdo_fetchall($sql,$params);

    $log=array(
        'uniacid'=>$_W['uniacid'],
        'date'=>date('Ymd'),
        'type'=>$type,      //1 早上 2 晚上
        'total'=>count($list),  //应发送
        'ctime'=>time(),
    );
    // exit;   //暂时退出                                        
    $total=0;            
	foreach ($list as $key => $value) {
        $tinfo=unserialize($value['data']);
		$msg = array(                                  
            'first' => array(
                'value' => '你好，'.trim($tinfo['realname']['data']).'。你报名参加的'.$value['title'].'活动在'.$day.date('H:i',$value['stime']).'开始，特此通知。',       
                "color" => "#4a5077",        
            ),
            'keyword1' => array(
                'value' =>  $value['title'],                                             
                "color" => "#4a5077",                                 
            ),           
            'keyword2' => array(                         
                'value' =>date('Y年m月d日 H点i分',$value['stime']),      
                "color" => "#4a5077",                                         
            ),
            'remark' => array(                           
                'value' => "\r".'请提前安排好行程',                   
                "color" => "#4a5077",            
            )
        );                                   

        $url=$this->createPluginMobileUrl('activity/activity',array('op'=>'detail','id'=>$value['id']));
        $re = m('message')->sendTplNotice($value['openid'],'RhqMzp-Rc1VE_csfCZMF2Hr1ZHf0fmeQTvBIkTIoLzs',$msg, $url);
	    $total++; 
    }
    $log['real_total']=$total;      //真实发送
    pdo_insert('sz_yi_sendnotice_log',$log);

