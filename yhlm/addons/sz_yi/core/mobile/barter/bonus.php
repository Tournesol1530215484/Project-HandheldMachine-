<?php

if (!defined('IN_IA')){
    exit('Access Denied');

}

global $_W,$_GPC;



$openid=m('user')->getOpenid(); 

$popenid=m('user')->islogin(); 

$openid = $openid?$openid:$popenid;

$sets=m('tools')->getSet();  

$op=empty($_GPC['op'])?'display':$_GPC['op'];

$ac=$_GPC['ac'];

$stime=strtotime(date('Y-m-d'));

$etime=strtotime(date('Ymd',strtotime("+1day")));

if ($op == 'detail') {

	die('debug~');

}else if($op == 'center'){
	// include $this -> template('barter/');

	exit;

}else if($op == 'bonusIndex'){			//代码在这里	

	$type=$_GPC['type'];

	if ($ac == 'getBonus') {

		$pindex=max(1,intval($_GPC['page']));

		$psize=10;

		$status=empty($_GPC['status'])?'1':$_GPC['status'];

		$condition='';

		$params=array(':uniacid'=>$_W['uniacid'],':openid'=>$openid);

		switch ($status) {

			case '1':

				$condition.=' and ob.status = 0 ';

				break;

			case '2':

				$condition.=' and ob.status = 1 ';

				break;

			case '3':

				break;

			default:

				break;

		}

		$condition.=' and ob.bonustype = :type ';

		$params[':type']=$type;

		$limit=' limit '.($pindex -1) * $psize.','.$psize; 	 

		$condition.=' order by ob.id desc ';

		//设置memcache 

		$list=pdo_fetchall('select ob.id obid,ob.money,ob.ctime,ob.bonustype,am.uid,am.id,am.title,am.thumb from '.tablename('sz_yi_obtain_bonus').' ob left join '.tablename('sz_yi_ad_model').' am on ob.adid = am.id where ob.uniacid = :uniacid and ob.openid = :openid '.$condition.$limit,$params);

		

		foreach ($list as $key => $value) {

			$list[$key]['url']=$this->createMobileUrl('barter/bonus',array('op'=>'bonusDetail','id'=>$value['id'],'obid'=>$value['obid']));

			$list[$key]['thumb']=unserialize($list[$key]['thumb']);

			$list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);

			foreach ($list[$key]['thumb'] as $k1 => $v1) {

				$list[$key]['thumb'][$k1]=tomedia($v1);

			}



			$temp=p('bonus')->getMerch($value['uid']);

			if (!empty($temp['merchid'])) {	 


				$tinfo=pdo_fetch(' select * from '.tablename('sz_yi_merch_user').' where  uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$temp['merchid']));

				$list[$key]['merchname']=$tinfo['merchname'];

			}else if(!empty($temp['dealmerchid'])) { 

				$tinfo=pdo_fetch('select * from '.tablename('sz_yi_dealmerch_user').' where  uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$temp['dealmerchid']));

				$list[$key]['merchname']=$tinfo['merchname'];

			}else{

				$tinfo=pdo_fetch('select * from '.tablename('sz_yi_store_data').' where  uniacid = :uniacid and storeid = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$temp['uid']));

					$list[$key]['merchname']=$tinfo['storename'];

			}

		}

		if ($_GPC['status'] == 3) {

			$list=pdo_fetchall('select * from '.tablename('sz_yi_ad_model').' where uniacid = :uniacid and status != 3 '.$limit,array(':uniacid'=>$_W['uniacid']));

			foreach ($list as $key => $value) {

				$list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);

			}

			$list=m('tools')->trthumb($list);

		}

		if ($list) {

			show_json(1,array('list'=>$list));

		}

		show_json(0,array('list'=>$list));

	}



	$info=array();

	//是这里吗
    $info['cash']=pdo_fetchcolumn(' select sum(money) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and bonustype = 1 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));


    $info['code']=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid  and bonustype = 2 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

    $info['fanscash']=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_ad_bonus_log').' where uniacid = :uniacid and openid = :openid and bonusType = 1 and ( level = 1 or level = 2 ) ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

    $info['fanscode']=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_ad_bonus_log').' where uniacid = :uniacid and openid = :openid and bonusType = 2 and ( level = 1 or level = 2 ) ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));



    $info['totalCash']=sprintf('%.3f',$info['cash'] + $info['fanscash']); 

    $info['totalCode']=sprintf('%.3f',$info['code'] + $info['fanscode']); 

    $info['status0']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and status = 0 and bonustype = :type ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':type'=>$type));

    $info['status1']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and status = 1 and bonustype = :type ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':type'=>$type));

    $info['status2']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_ad_model').' where uniacid = :uniacid and status != 3 ',array(':uniacid'=>$_W['uniacid']));

    //var_dump($info);

	include $this -> template('barter/bonusIndex');	 	 	 

	exit;

}else if($op == 'bonusDetail'){	//红包详情

	$id=$_GPC['id'];

	$obid=$_GPC['obid'];

	if ($_W['isajax']) {

		$pindex=max(1,intval($_GPC['page']));

		$psize=49;

		empty($id) && show_json(0,'非法参数!');

	    $ad=m('tools')->getAd($id);

	    empty($ad) && show_json(0,'没有该条广告!');

	    $mylog=pdo_fetch('select * from '.tablename('sz_yi_obtain_bonus').' where id = :id and uniacid = :uniacid and adid = :adid and openid = :openid and version = :version',array(':uniacid'=>$_W['uniacid'],':adid'=>$id,':openid'=>$openid,':version'=>$ad['version'],':id'=>$obid));

	    $no=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and adid = :adid and id < :id and version = :version',array(':uniacid'=>$_W['uniacid'],':adid'=>$id,':id'=>$mylog['id'],':version'=>$ad['version']));

	    $mylog['no']='我是第'.intval($no + 1).'位拆开的';

	    $mylog['realname']='我';	  

	    // show_json(0,$mylog);

	    $limit=' limit '.($pindex - 1)* $psize .','.$psize; 	 	

	    $log=pdo_fetchall('select ob.id,ob.money,ob.ctime,m.realname,m.mobile from '.tablename('sz_yi_obtain_bonus').' ob left join '.tablename('sz_yi_member').' m on m.openid = ob.openid where ob.uniacid = :uniacid and ob.adid = :adid and ob.id < :id and ob.version = :version order by ob.id desc '.$limit,array(':uniacid'=>$_W['uniacid'],':adid'=>$id,':id'=>$mylog['id'],':version'=>$ad['version'])); 		

	   if ($pindex == 1) {

	   	array_unshift($log,$mylog);

	   } 	 

	   foreach ($log as $key => $value) {

	        $log[$key]['realname']=m('tools')->strReplace($value['realname']);

	   		$log[$key]['mobile']=$value['mobile'] ? m('tools')->mobileReplace($value['mobile']) : '';

	   		$log[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);

	   }

	   show_json(1,array('list'=>$log,'total'=>count($log),'pagesize'=>$psize));

	}

    $myad=m('tools')->getAd($id);

    $myad['thumb']=unserialize($myad['thumb']);

    foreach ($myad['thumb'] as $key => $value) {

    	$myad['thumb'][$key]=tomedia($value);

    }

	include $this -> template('barter/bonusDetail');

	exit;

}elseif($op == 'bonusAd'){	     //红包赚的



	if ($_W['isajax']) {

		//获取本月开始的时间戳

		$pindex=max(1,intval($_GPC['page']));

		$psize=5;	 	 	

		$condition=' and ob.uniacid  = :uniacid  and ob.openid = :openid and ob.status = 1 ';

		$params=array(':uniacid'=>$_W['uniacid'],':openid'=>$openid);

		$timearr=explode('-',$_GPC['search_time']);

		$searchTime=m('tools')->getFastAndLastTime($timearr[0],$timearr[1]);

		$condition.=' and ob.ctime > :stime and ob.ctime < :etime';

		$params[':stime']=$searchTime['firstday'];

		$params[':etime']=$searchTime['lastday'];

		if($_GPC['who'] == 'cash'){

			$condition.=' and ob.bonustype = 1 ';	 	 

		}else if($_GPC['who'] == 'code'){

			$condition.=' and ob.bonustype = 2 ';

		}	 	 

		$condition.=' order by ob.ctime desc ';	 	 	 

		$condition.=' limit '.($pindex -1) * $psize.','.$psize;

		$list=pdo_fetchall('select ob.*,am.thumb,am.title from '.tablename('sz_yi_obtain_bonus').' ob left join '.tablename('sz_yi_ad_model').' am on am.id = ob.adid where 1 '.$condition,$params);

		$list=m('tools')->trthumb($list);

		$total=0; 		  	 	 	 	 		 	  	 	

		foreach ($list as $key => $value) {

			$list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);

			$total+=floatval($value['money']);

		}

		show_json(1,array('list'=>$list,'total'=>$total,'pagesize'=>$psize));

	}

	include $this -> template('barter/bonusAd');

	exit;

}elseif($op == 'fans'){			//粉丝帮我赚

	if ($ac == 'getdetail') {	//粉丝明细

		$condition='';

		if (!empty($_GPC['stime'] && !empty($_GPC['etime']))) {

			$stime=strtotime(trim($_GPC['stime']));

			$etime=strtotime(trim($_GPC['etime']))+24*3600-1;

		}else{

			$stime=strtotime('-3 month');  //这4月的时间戳

			$etime=time();

		} 	 	  	 	

		$member=m('member')->getMember($openid);

		$params=array(

			':uniacid'=>$_W['uniacid'],

			':openid'=>$openid,

			':stime'=>$stime,

			':etime'=>$etime

		);

		if($_GPC['who'] == 'cash'){

			$condition.=' and bonusType = 1';

		}else if($_GPC['who'] == 'code'){

			$condition.=' and bonusType = 2';

		}

		$info[0]['level']=m('tools')->getChild($member['id']);

		$info[1]['level']=m('tools')->getCountChild($member['id']);

		$info[0]['level_bonus']=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_ad_bonus_log').' where uniacid = :uniacid and openid = :openid and level = 1 and ctime > :stime and ctime < :etime '.$condition,$params);

		$info[1]['level_bonus']=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_ad_bonus_log').' where uniacid = :uniacid and openid = :openid and level = 2 and ctime > :stime and ctime < :etime '.$condition,$params);

		$totalall=$info[0]['level_bonus']+$info[1]['level_bonus'];

		$people=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_member').' where uniacid = :uniacid and agentid = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$member['id']));

		if ($people) {

			$people.='人';

		}

		show_json(1,array('list'=>$info,'total'=>$info[0]['level_bonus'],'totalall'=>$totalall,'people'=>$people));

	}else if($ac == 'getFansDetail'){

		$pindex=max(1,intval($_GPC['page']));

		$psize=10;	

		$searchtime=trim($_GPC['search_time']);

		$searchtime=explode('-',$searchtime);

		$time=m('tools')->getFastAndLastTime($searchtime[0],$searchtime[1]);

		$params=array(

			':uniacid'=>$_W['uniacid'],

			':openid'=>$openid,

			':stime'=>$time['firstday'],

			':etime'=>$time['lastday']

		);

		$condition.=' and l.ctime > :stime and l.ctime < :etime ';

		if($_GPC['who'] == 'cash'){

			$condition.=' and l.bonusType = 1';

		}else if($_GPC['who'] == 'code'){

			$condition.=' and l.bonusType = 2';

		}



		$sql = 'select l.*,am.thumb,am.title from '.tablename('sz_yi_ad_bonus_log').' l left join '.tablename('sz_yi_ad_model').' am on l.adid = am.id where l.uniacid = :uniacid and l.openid = :openid and (l.level = 1 or l.level = 2) ';

		$sql.=$condition;

		$sql.=' order by l.id desc ';

		$sql.=' limit '.($pindex - 1) * $psize.','.$psize;

		$list=pdo_fetchall($sql,$params);

		$list=m('tools')->trthumb($list);

		// show_json(0,$params);

		$total=0;

		foreach ($list as $key => $value) {

			$obdata=pdo_fetchcolumn('select openid from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$value['obid']));

			if ($obdata) {

				$putmember=m('member')->getMember($obdata);

				$list[$key]['realname']=$putmember['realname'];

				$list[$key]['nickname']=$putmember['nickname'];

				$list[$key]['mobile']=$putmember['mobile'];

			} 	 	 

			$list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);

			$total+=$value['money'];

		}

		if ($list) {

			show_json(1,array('list'=>$list,'total'=>$total,'pagesize'=>$psize));

		}

		show_json(0,array('list'=>array()));

	}  		

	include $this -> template('barter/bonusFans');

	exit;

}else if($op == 'draw'){	//领取红包

	$core=trim($_GPC['core']);

	$id=$_GPC['id'];

	$ad=m('tools')->getAd($id);

	$todaytotal=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and adid = :adid and ctime > :stime and ctime < :etime and status = 1',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':adid'=>$id,'stime'=>$stime,'etime'=>$etime)); 	

	// 广告可以重复投放 version 当前广告的版本 每发布一次+1 发放分红记录

	$maxtotal=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and adid = :adid and status = 1 and version = :version',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':adid'=>$id,':version'=>$ad['version']));

	$todaytotal=$todaytotal?:0;

	$maxtotal=$maxtotal?:0;



	if ($maxtotal <= $ad['usermax']) {		//用户最大领取

		if ($todaytotal <= $ad['daymax']) {	//每天最大领取

			if ($core === trim($ad['core'])) {	 	 	 	 	 

				$data=array(	

					'got'=>intval($ad['got'])+1,	 

					'residual'=>intval($ad['residual'])-1

				);

				if ($data['got'] == $ad['bonus'] || $ad['residual'] == 0) {

					$data['status']=4;

					$data['now']=0;	//红包领取完 广告关闭

				}	 	 	

				p('bonus')->calcAdBonus($openid,$id);

				pdo_update('sz_yi_ad_model',$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));	

				show_json(1,'领取成功!');

		    }else{ 	 	 

		    	show_json(0,'领取失败!');

		    }

		}else{

			show_json(0,'你今天已经超过最多领取次数');

		} 	 

	}else{

		show_json(0,'你已经超过最多领取次数');

	}

}else if($op == 'jump'){

	$id=intval($_GPC['id']);

	$tempad=m('tools')->getAd($id);

	if (empty($tempad)) {

		show_json(0,'没有该条广告');

	}else{

		$exists=pdo_fetch('select * from '.tablename('sz_yi_obtain_bonus').' where uniacid = :uniacid and openid = :openid and adid = :adid and ctime > :stime and ctime < :etime and version = :version',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':adid'=>$id,'stime'=>$stime,'etime'=>$etime,':version'=>$tempad['version'])); 	 	  	 	

		$log=array( 	 		 	 		  	

			'uniacid'=>$_W['uniacid'],

			'adid'=>$id,

			'openid'=>$openid,

			'ctime'=>time(),

			'status'=>0,	 

			'date'=>date('Ymd'),

			'version'=>$tempad['version'],

			'bonustype'=>$tempad['putInType']

		);

		if (empty($exists)) { 		

			pdo_insert('sz_yi_obtain_bonus',$log);

			if (pdo_insertid()) {

				show_json(1,'成功');

			}else{

				show_json(0,'失败');

			}

		}else{

			show_json(1,'已跳过');

		}

	}

}

