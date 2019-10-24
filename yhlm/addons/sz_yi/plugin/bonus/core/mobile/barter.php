<?php

 if (!defined('IN_IA')){

    exit('Access Denied');

}

global $_W, $_GPC;

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$openid = m('user') -> getOpenid();

$popenid        = m('user')->islogin();

$openid = m('user')->getOpenid();

$openid = $openid?$openid:$popenid;

$member = m('member') -> getMember($openid);	//用户信息

$uniacid = $_W['uniacid'];



if ($_GPC['debug']) {

	$list=pdo_fetchall('select * from '.tablename('sz_yi_currency_activation').' where uniacid = :uniacid and openid != :openid and paytype != 4 ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

	$exists=pdo_fetchall('select orderid from '.tablename('sz_yi_barter_services_fee').' where uniacid = :uniacid and type = 1 group by orderid ',array(':uniacid'=>$_W['uniacid']));	 	 

	$ids=m('tools')->trarr($exists,'orderid');

	foreach ($list as $key => $value) {

		if (in_array($value['id'],$ids)) {

			unset($list[$key]);

		}

	}



	foreach ($list as $key => $value) {

		// p('bonus')->oldcalculateactive($value['openid'],$value['activapay'],$value['id'],$value['ordersn']);

	}

	exit;	 		 

}

if ($_W['isajax']) {



	$pindex=max(1,intval($_GPC['page']));

	$psize=12;

	$status=$_GPC['status'];



	$condition=' and v.uniacid = :uniacid and v.openid = :openid ';

	$condition1=' and v.uniacid = :uniacid and v.mid = :mid ';

	$condition3=' and v.uniacid = :uniacid and v.mid = :mid order by v.id desc';	//新增

	$params=array(

		':uniacid'=>$_W['uniacid'],

		':openid'=>$openid,

	);



	$params1=array(

		':uniacid'=>$_W['uniacid'],

		':mid'=>$member['id'],

	);



	$total=0;



	switch ($member['bonus_area']) {

		case '1':

			$condition1.=' and v.level = 5 order by v.id desc';
			//$condition1.=' and v.level = 5 order by v.id desc';

			$condition.=' and v.level = 5 ';

			$mcon=' and level = 5 ';

			break;

		case '2':

			$condition1.=' and v.level = 4 order by v.id desc';

			$condition.=' and v.level = 4 ';

			$mcon=' and level = 4 ';

			break;

		case '3':

			$condition1.=' and v.level = 3 order by v.id desc';

			$condition.=' and v.level = 3 ';

			$mcon=' and level = 3 ';
			break;

		default:

			break;

	}
	//置顶收益

	// $mbonus=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_year_vip_log').' where uniacid = :uniacid and openid = :openid '.$mcon,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));	 
	$mbonus=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_year_vip_log').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));		

	// $svs=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_barter_services_fee').' where uniacid = :uniacid and mid = :mid '.$mcon,array(':uniacid'=>$_W['uniacid'],':mid'=>$member['id']));

	$svs=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_barter_services_fee').' where uniacid = :uniacid and mid = :mid ',array(':uniacid'=>$_W['uniacid'],':mid'=>$member['id']));



	$total=$mbonus + $svs;



	$level=array(

		0=>'无',

		1=>'开发',

		2=>'推荐',

		3=>'区级代理',

		4=>'市级代理',

		5=>'省级代理',

		7=>'员工'

	); 



	if ($status == 1) {

		$sql='select v.* from '.tablename('sz_yi_barter_services_fee').' v left join '.tablename('sz_yi_member').' m on m.id = v.mid where 1 ';

		//$sql.=$condition1;

		$sql.=$condition3;	 	 	

		$list=pdo_fetchall($sql,$params1);



		if ($list) {	 			 	 	 	 	

			foreach ($list as $key => $value) {

                $merch_name=pdo_fetch('select m.realname,m.nickname from '.tablename('sz_yi_currency_activation').'a left join'.tablename('sz_yi_member').'m on a.openid=m.openid where a.uniacid=:uniacid and a.ordersn=:ordersn',array(':uniacid'=>$_W['uniacid'],':ordersn'=>$value['ordersn']));

                $list[$key]['realname']=$merch_name['realname'];

                $list[$key]['nickname']=$merch_name['nickname'];

                $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);

				$list[$key]['title']=$level[$value['level']];

			}



			show_json(1,array('list'=>$list,'total'=>$total,'commissioncount'=>$svs,'pagesize'=>$psize));

		}

	}else if($status == 2){

		// $condition.=' and v.cate = 1 ';

		/*$sql='select v.*,m.nickname from '.tablename('sz_yi_activity_bonus_log').' v left join '.tablename('sz_yi_member').' m on m.openid = v.consumers where 1 ';*/

		$sql='select v.*,m.nickname,m.realname from '.tablename('sz_yi_year_vip_log').' v left join '.tablename('sz_yi_member').' m on m.openid = v.customer where 1 ';	 	 		 			

		$sql.=$condition;	 		

		$sql.=' order by v.id desc ';	 	

		$sql.=' limit '.($pindex - 1) * $psize.' , '.$psize;

		$list=pdo_fetchall($sql,$params);

		/*pdo_debug();

		exit;*/

		if ($list) {

			foreach ($list as $key => $value) {

				$list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);

				$list[$key]['title']=$level[$value['level']];	 	 	

			}

			show_json(1,array('list'=>$list,'total'=>$total,'commissioncount'=>$mbonus,'pagesize'=>$psize));

		}

	}	 	 		 

	show_json(0,array('list'=>array(),'total'=>0,'commissioncount'=>0,'pagesize'=>$psize));

}

include $this -> template('barter');