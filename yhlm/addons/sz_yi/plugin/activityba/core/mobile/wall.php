<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$ac = !empty($_GPC['ac']) ? $_GPC['ac'] : '';
var_dump('success!!!!!!!!!');
exit;	 			 		 	 		
$activityId=$_GPC['activityId'];
// $pu=m('tools')->getinfo($_W['uid']);	 		 	
// $muser=m('activity')->getMuser($_W['uid']);
	 	 	

if($op == 'display'){
    $act=m('activity')->getact($activityId);
    $act['background']=tomedia($act['background']);
    $act['logo']=tomedia($act['logo']);

}else if($op == 'qrcode'){		//二维码

	include $this -> template('qrcode');
	exit;
}else if($op == 'msg'){		//消息墙

	$pindex=intval($_GPC['lastTotal']);	//从何处开始取出数据
	$psize=20;	 			 		 	 			 	 		 	
	$params=array(
		':uniacid'=>$_W['uniacid'],
		':actid'=>$activityId,
	);	 		 

	$condition=' and am.actid = :actid and am.uniacid = :uniacid ';
	$sql='select am.*,m.nickname,m.avatar from '.tablename('sz_yi_activity_msg').' am left join '.tablename('sz_yi_member').' m on m.openid = am.openid ';
	
					 		 
	$sql.=' order by am.id asc ';
	$sql.=' limit '. $pindex .','.$psize;
 			 	 		
	$record=pdo_fetchall($sql,$params);

	$lastTotal=count($record);
	if ($pindex > 0) {
		$lastTotal+=$pindex;
	}


	foreach ($record as $key => $value) {
		$temp=unserialize($value['detail']);

		if ($value['msgtype'] == 'text') {
			$record[$key]['content']=$temp['content'];

		}else if($value['msgtype'] == 'image'){
			$record[$key]['content']=$temp['picurl'];

		}else{
			$record[$key]['content']='消息内容不支持';
		}	 	 	
	}

	if ($ac == 'get') {	 	

		if ($record) {	 	 	
			show_myjson(1,'success',array('record'=>$record,'total'=>count($record)));		 	 	 	 	
		}
		show_myjson(0,'error',array('record'=>array(),'total'=>count(array())));		 	 	 	 	
	}
	include $this -> template('message');
	exit;
}else if($op == 'signin'){		//签到墙

	include $this -> template('signin');
	exit;
}else if($op == 'lottery'){		//抽奖

	include $this -> template('lottery');
	exit;
}
include $this -> template('wall');
