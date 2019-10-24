<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$ac = !empty($_GPC['ac']) ? $_GPC['ac'] : '';
$activityId=$_GPC['activityId'];
$pu=m('tools')->getinfo($_W['uid']);
$muser=m('activity')->getMuser($_W['uid']);
$act=m('activity')->getact($activityId);

$con=$act['afterTheSignup']?' and status = 1 ':'';
$joincon=$act['afterTheSignup']?' and sg.status = 1 ':'';
m('activityba')->checkActivity($activityId);	 			

if ($act['screen_stime'] > time()) {
	m('tools')->tip('大屏幕开始时间未到!');
}
	 		 	 	 	
if ($act['screen_etime'] < time()) {
	m('tools')->tip('大屏幕结束时间已过!');
}	 		 		 		

if($op == 'display'){
    $act['background']=tomedia($act['background']);
    $act['logo']=tomedia($act['logo']);
    $url=$this->createPluginMobileUrl('activity/center',array('op'=>'join','id'=>$activityId));
	$qrcode=m('activityba')->createQrcode($url);
	$sgtotal=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :actid '.$con,array(':uniacid'=>$_W['uniacid'],':actid'=>$activityId));

}else if($op == 'qrcode'){			 		//二维码
	
	include $this -> template('qrcode');
	exit;
}else if($op == 'msg'){		//消息墙

	$pindex=intval($_GPC['lastTotal']);	//从何处开始取出数据
	$psize=20;	 			 		 	 			 	 		 	
	$params=array(
		':uniacid'=>$_W['uniacid'],
		':actid'=>$activityId,
	);	 		 	 	

	$condition=' and am.actid = :actid and am.uniacid = :uniacid and am.type != "event" and am.type != "trace" and am.status = 1';
	$sql='select am.*,m.nickname,m.avatar from '.tablename('sz_yi_activity_msg').' am left join '.tablename('sz_yi_member').' m on m.openid = am.openid where 1 ';
		 			 	
	$sql.=$condition;			 		 
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

	if ($_W['isajax']) {	 		 	 	

		if ($record) {	 	 	
			show_myjson(1,'success',array('record'=>$record,'total'=>$lastTotal));		 	 	 	 	
		}	 			 	 			 
		show_myjson(0,'error',array('record'=>array(),'total'=>count(array())));		 	 	 	 	
	}
	include $this -> template('message');
	exit;
}else if($op == 'signin'){		//签到墙

	$pindex=intval($_GPC['page']);	//从何处开始取出数据
	$psize=20;	 			 		 	 			 	 		 	
	$params=array(
		':uniacid'=>$_W['uniacid'],
		':actid'=>$activityId,
	);	 		 

	$condition=' and sg.actid = :actid and sg.uniacid = :uniacid '.$joincon;
	$sql='select m.nickname,m.avatar head from '.tablename('sz_yi_activity_signup').' sg left join '.tablename('sz_yi_member').' m on m.openid = sg.openid where 1 ';
		 	 
	$sql.=$condition;		 		 		 		 
	$sql.=' order by sg.signin desc ';		 	  	 
	$sql.=' limit '. $pindex .','.$psize;	 	 		 			 					 	 	
 			 	 			 	 	 	 	 		
	$record=pdo_fetchall($sql,$params);	 	 
		 		 			 		 	
	$page=count($record);
	if ($pindex > 0) {	 			
		$page+=$pindex;
	}	 		 		 	 	 			 

	if ($_W['isajax']) {	 		 	 		 	 	
		if ($record) {	 		 		

			show_myjson(1,'success',array('record'=>$record,'total'=>$page));		 	 	 	 	
		}	 			 	 			 	  		 	
		show_myjson(0,'error',array('record'=>array(),'total'=>count(array())));		 	 	 	 	
	}

	include $this -> template('signin');
	exit;
}else if($op == 'lottery'){		//抽奖

	if ($_W['isajax']) {
		if ($ac == 'getSigninCount') {

			$totaljoin=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :actid '.$con,array(':uniacid'=>$_W['uniacid'],':actid'=>$activityId));

			if ($totaljoin) {	 	 		 
				die(json_encode(array('ret'=>1,'total'=>$totaljoin)));
			}	 	 	
			show_myjson(0,'error');
		}else if ($ac == 'getPlayer') {	//点击抽奖时获取玩家列表	

			$prize=m('activityba')->getPrize($activityId);

			if (!$prize) {
				show_myjson(0,'success',array('msg'=>'没有更多奖品!','success'=>false,'type'=>-1));	 	 	
			}

			$params=array(
				':uniacid'=>$_W['uniacid'],
				':actid'=>$activityId,
			);	 		 	 	 	

			$condition=' and sg.actid = :actid and sg.uniacid = :uniacid and sg.status = 1 ';
			$sql='select sg.id id,m.nickname,sg.openid,m.avatar head from '.tablename('sz_yi_activity_signup').' sg left join '.tablename('sz_yi_member').' m on m.openid = sg.openid where 1 ';	 	 	
				 	 	 		 	 
			$sql.=$condition.$joincon;		 	 		 		 		 		 
			$sql.=' order by sg.signin desc ';		 	  	 
			$peoples=pdo_fetchall($sql,$params);

			$luckGuys=pdo_fetchall('select id,openid from '.tablename('sz_yi_activity_luckguys').' where uniacid = :uniacid and actid = :actid and status > 0 ',array(':uniacid'=>$_W['uniacid'],':actid'=>$activityId));
			$luckGuys=m('tools')->trarr($luckGuys,'openid');

			foreach ($peoples as $key => $value) {		//已中奖无法再继续
				if (in_array($value['openid'], $luckGuys) != false) {
					unset($peoples[$key]);
				}
			}

			if ($peoples) {
				show_myjson(1,'success',array('playerList'=>$peoples,'total'=>count($peoples),'totaljoin'=>count($peoples)));
			}	
			show_myjson(1,'success',array('msg'=>'没有更多参与者!'));	 	 	

		}else if ($ac == 'getLuckGuys'){	//获取中奖人

			$condition=' and al.status > 0 and al.actid = :actid and al.uniacid = :uniacid ';	 	
			$sql = 'select al.*,m.nickname username,m.avatar head,ap.title prizename ,ap.type prizetype  from '.tablename('sz_yi_activity_luckguys').' al left join '.tablename('sz_yi_member').' m on m.openid = al.openid left join '.tablename('sz_yi_activity_prize').' ap on ap.id = al.prizeid where 1 ';	
 		 	$sql.=$condition;
			$params=array( 			
				':uniacid'=>$_W['uniacid'],
				':actid'=>$activityId,	 	 		 	 	
			);

			$total = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :actid '.$con,array(':uniacid'=>$_W['uniacid'],':actid'=>$activityId));

			$luckGuys=pdo_fetchall($sql,$params);

			if ($luckGuys) {
				foreach ($luckGuys as $key => $value) {
					$tid=pdo_fetchcolumn('select id from '.tablename('sz_yi_activity_signup').' where uniacid = 
						:uniacid and actid = :actid and openid = :openid and status = 1 ',array(':uniacid'=>$_W['uniacid'],':actid'=>$activityId,':openid'=>$value['openid']));
					$luckGuys[$key]['signinid']=$tid;
				}
				show_myjson(1,'success',array('total'=>count($luckGuys),'totaljoin'=>$total,'luckGuys'=>$luckGuys));
			}	 	
					 			 	 	 	 	 	 	
			show_myjson(1,'暂无中奖名单',array('total'=>0,'totaljoin'=>0,'luckGuys'=>array()));	 		

		}else if ($ac == 'start') {		//开始抽奖

			$params=array(
				':uniacid'=>$_W['uniacid'],	 	
				':actid'=>$activityId,
			);	 	

			$condition=' and sg.actid = :actid and sg.uniacid = :uniacid and sg.status = 1 ';
			$sql='select m.nickname unsername,m.avatar head,sg.openid from '.tablename('sz_yi_activity_signup').' sg left join '.tablename('sz_yi_member').' m on m.openid = sg.openid where 1 ';
			
			$sql.=$condition.$joincon;		 		 		 		 
			$sql.=' order by sg.signin desc ';		 	  	 
			$peoples=pdo_fetchall($sql,$params);
			
			$luckGuys=pdo_fetchall('select id,openid from '.tablename('sz_yi_activity_luckguys').' where uniacid = :uniacid and actid = :actid and status > 0 ',array(':uniacid'=>$_W['uniacid'],':actid'=>$activityId));
			$luckGuys=m('tools')->trarr($luckGuys,'openid');

			foreach ($peoples as $key => $value) {		//已中奖无法再继续
				if (in_array($value['openid'], $luckGuys) != false) {
					unset($peoples[$key]);
				}
			}
			$peoples=array_values($peoples);		//重排数据键值

			if (empty($peoples)) {
				show_myjson(1,'success',array('type'=>-2,'msg'=>'无更多人参加'));
			}	
 	 	
			$max=count($peoples);	 		
			$luck=rand(0,$max-1); //抽取中奖人
			$luck=$peoples[$luck];
			$prize=m('activityba')->getPrize($activityId);

			$no = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_luckguys').' where uniacid = :uniacid and actid = :actid and prizeid = :prizeid and status > 0 ',array(':uniacid'=>$_W['uniacid'],':actid'=>$activityId,':prizeid'=>$prize['id']));
			$no=$no?:1;	 	 	
			$lm=m('member')->getMember($luck['openid']); 			 	

			$luck['username']=$lm['nickname'];
			$luck['img']=tomedia($prize['thumb']);
			$luck['msg']=$luck['openid'].'获得'.$prize['title'];
			$luck['prizeName']=$prize['title'];
			$luck['prizetype']=$prize['type'];
			$luck['type']=$prize['type'];
			$luck['success']=true;
			$luck['signinno']=$no;	 	 		 

			$log=array(	 		 		 	 		 	 	
				'uniacid'=>$_W['uniacid'],	 	
				'openid'=>$luck['openid'],
				'actid'=>$activityId,
				'prizeid'=>$prize['id'],
				'number'=>$no,
				'status'=>1,	 		 	
				'code'=>$activityId.$lm['id'].rand(1000000,9999999),
				'ctime'=>time()	 	 			 	 		 			 		 	 
			);	 			 	  		 


			if (rand(0,1)) {	 			 	 	 		 			 
				pdo_insert('sz_yi_activity_luckguys',$log);
				// 领取完后奖品-减去每次抽奖数量	 	 	
				pdo_update('sz_yi_activity_prize',array('num'=>$prize['num']-$prize['singleNum']),array('id'=>$prize['id'])); 		


				//发送通知
				$sendmsg = array(                 			 	 
			        'first' => array(
			            'value' => "恭喜您参加的活动中奖了!",
			            "color" => "#4a5077"
			        ),
			        'keyword1' => array(            
			            'value' => $act['title'],
			            "color" => "#4a5077"
			        ),                              
			        'keyword2' => array(
			            'value' => $prize['title'],                                             
			            "color" => "#4a5077"
			        ),
			        'keyword3' => array(            
			            'value' =>  date('Y年m月d日 H:i:s'),         
			            "color" => "#4a5077"            	 	 	 
			        ),
			        'remark' => array(           
			            'value' => '兑换号:'.$log['code'],
			            "color" => "#4a5077"
			        ) 	 	 	 	 	 	 	 	
			    );		 	 	

    			$re = m('message')->sendTplNotice($luck['openid'],'rfvh3r-jQz8if04abY99bOte1iU8JCIAjp0reP5pddE',$sendmsg);

				show_myjson(1,'success',array($luck));	 	 			 	 
			}else{	 	 		 			
				show_myjson(1,'success',array('type'=>-2,'msg'=>'该次抽奖无人中奖'));		 		 	 		 	 	 	 	 	
			}	 	 		 			
			
		}else if($ac == 'reset'){	//取消某个中奖人 下次抽奖将在候选人中
			$sgid=$_GPC['signinno'];

			$sg=pdo_fetch('select * from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and id = :id',array(':id'=>$sgid,':uniacid'=>$_W['uniacid']));
			$exists=pdo_fetch('select * from '.tablename('sz_yi_activity_luckguys').' where uniacid = :uniacid and openid = :openid and actid = :actid and status > 0 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$sg['openid'],':actid'=>$activityId));
			

			//发送通知
			$sendmsg = array(                 			 	 
		        'first' => array(
		            'value' => "活动现场中奖取消提醒!",
		            "color" => "#4a5077"
		        ),
		        'keyword1' => array(            
		            'value' => $act['title'],
		            "color" => "#4a5077"
		        ),                              
		        'keyword2' => array(
		            'value' => date('Y-m-d H:i:s'),                                             
		            "color" => "#4a5077"	 	 	
		        ),
		        // 'remark' => array(           
		        //     'value' => '兑换号:'.$log['code'],
		        //     "color" => "#4a5077"
		        // ) 	 		 		 	 		 	 	 	 	 	 	
		    );	

			if ($exists) {	 		 		 		 	 	
				$re = m('message')->sendTplNotice($sg['openid'],'OkKX0Dyst7xOJEhc_z8o6aYYF5Tb93u0whF3Iz3nE5E',$sendmsg);
				pdo_update('sz_yi_activity_luckguys',array('status'=>0),array('id'=>$exists['id'],'uniacid'=>$_W['uniacid']));
				$prize=pdo_fetch('select * from '.tablename('sz_yi_activity_prize').' where uniacid = :uniacid and id = :id ',array(
					':uniacid'=>$_W['uniacid'],':id'=>$activityId));
				// 重抽数量补回
				pdo_update('sz_yi_activity_prize',array('num'=>$prize['num']+$prize['singleNum']),array('id'=>$prize['id'],'uniacid'=>$_W['uniacid']));
				show_myjson('1','success');
			}	 	 	 		 			 		 		 	 		  		
			show_myjson(0,'没有该中奖人!');	 		 			 
		}
	}


	$sql='select * from '.tablename('sz_yi_activity_prize').' where uniacid = :uniacid and actid = :id';
    $params=array(
        ':uniacid'=>$_W['uniacid'],
        ':id'=>$_GPC['activityId']
    );

    $prize=pdo_fetchall($sql,$params);	 	 	
	$nowprize=m('activityba')->getPrize($activityId);	//当前抽奖奖品

	$totalluck=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_luckguys').' where uniacid = :uniacid and actid = :actid and status > 0 ',array(':uniacid'=>$_W['uniacid'],':actid'=>$activityId));

	$totaljoin=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :actid and status = 1',array(':uniacid'=>$_W['uniacid'],':actid'=>$activityId));

	include $this -> template('lottery');
	exit;
}else if($op == 'livemsg'){	 	 		

    $pindex=intval($_GPC['page']);	 	 	
    $psize=1;
    $id=$_GPC['activityId'];                             
        
    $params=array(
        ':uniacid'=>$_W['uniacid'],
        ':id'=>$id,
    );
    $sql='select * from '.tablename('sz_yi_activity_livemsg').' where uniacid = :uniacid and actid = :id ';
    $sql.=' order by id asc ';
    $sql.='limit '.($pindex -1).','.$psize;

    $msg=pdo_fetch($sql,$params);	 	 
    
    if ($msg) {
    	die(json_encode(array('ret'=>1,'msg'=>$msg['msg'])));	 	 	
	}	 			 	 			 	 		
    die(json_encode(array('ret'=>0,'msg'=>'')));	 	 	
}	 	 		 	 	  		 	 			
include $this -> template('wall');
