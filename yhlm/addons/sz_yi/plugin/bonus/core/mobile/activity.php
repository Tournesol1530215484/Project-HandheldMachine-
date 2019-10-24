<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$openid = m('user') -> getOpenid();
$mid = m('member') -> getMid();
$uniacid = $_W['uniacid'];

if ($_W['isajax']) {

	$pindex=max(1,intval($_GPC['page']));
	$psize=12;
	$condition=' and v.uniacid = :uniacid and v.openid = :openid ';
	$params=array(
		':uniacid'=>$_W['uniacid'],
		':openid'=>$openid,
	); 
	$total=0;

	switch ($member['bonus_area']) {
		case '1':
			/*$condition.=' and v.level = 5 ';
			$mcon=' and level = 5 ';*/
			break;
		case '2':
			/*$condition.=' and v.level = 4 ';
			$mcon=' and level = 4 ';*/

			break;
		case '3':
			// $condition.=' and v.level = 3 ';
			// $mcon=' and level = 3 ';

			break;
		default:
			break;
	}

	//充值会员收益
	$mfee=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_activity_bonus_log').' where uniacid = :uniacid and openid = :openid and cate = 1 '.$mcon,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

	//打赏收益
	$mrew=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_activity_bonus_log').' where uniacid = :uniacid and openid = :openid and cate = 2 '.$mcon,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

	//置顶收益
	$st=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_settop_bonus_log').' where uniacid = :uniacid and openid = :openid '.$mcon,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
	$total=$mfee + $mrew + $st;
	$level=array(
				0=>'无',
				1=>'推荐人',
				2=>'员工',
				3=>'省级代理',
				4=>'省级代理',
				5=>'省级代理',
			);

	switch ($_GPC['status']) {
		case '1':
			if ($_GPC['applytype'] == 1) {
				$condition.=' and rl.paytype != 3 ';
			}else{
				$condition.=' and rl.paytype = 3 ';
			}	 			
			$sql='select v.*,m.nickname,rl.paytype from '.tablename('sz_yi_activity_bonus_log').' v left join '.tablename('sz_yi_member').' m on m.openid = v.consumers left join '.tablename('sz_yi_activity').' a on a.id = atid left join '.tablename('sz_yi_activity_recharge_log').' rl on rl.id = v.logid where 1 and v.cate = 1 ';
			$sql.=$condition;

			$sql.=' order by v.id desc ';
			$sql.='limit '.($pindex - 1) * $psize.' , '.$psize;
			$list=pdo_fetchall($sql,$params);
			if ($list) {
				foreach ($list as $key => $value) {
					if ($value['atid']) {
						switch ($value['atid']) {
							case '1':	 
								$list[$key]['title']='初级会员';
								break;
							case '2':
								$list[$key]['title']='中级会员';
								break;
							case '3':
								$list[$key]['title']='高级会员';
								break;
							default:
								break;
						}
					}	 	 
					$list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
				}	 		 	 	 	 	 	 	 
				show_json(1,array('list'=>$list,'pagesize'=>$psize,'total'=>$total,'commissioncount'=>$mfee));	 	 	 	 	 
			}
			show_json(0,array('list'=>array(),'pagesize'=>$psize,'total'=>$total,'commissioncount'=>$mfee));
			
			break;

			/*$sql='select v.*,m.nickname from '.tablename('sz_yi_year_vip_log').' v left join '.tablename('sz_yi_member').' m on m.openid = v.customer where 1 ';
			$sql.=$condition;
			$sql.=' order by v.id desc ';
			$sql.='limit '.($pindex - 1) * $psize.' , '.$psize;
			$list=pdo_fetchall($sql,$params);
			if ($list) {
				foreach ($list as $key => $value) {
					$list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
					$list[$key]['title']=$level[$value['level']];
				}	 		 	 	 	 	 	 	 
				show_json(1,array('list'=>$list,'pagesize'=>$psize,'total'=>$total,'commissioncount'=>$mfee));	 	 	 	 	 
			}	 	 	 
			show_json(0,array('list'=>array(),'pagesize'=>$psize,'total'=>$total,'commissioncount'=>$mfee));
			break;*/
		case '2':
			$sql='select v.*,m.nickname,IFNULL(a.title,"活动已被删除") title from '.tablename('sz_yi_activity_bonus_log').' v left join '.tablename('sz_yi_member').' m on m.openid = v.consumers left join '.tablename('sz_yi_activity').' a on a.id = atid where 1 and v.cate = 2 ';
			$sql.=$condition;

			$sql.=' order by v.id desc ';
			$sql.='limit '.($pindex - 1) * $psize.' , '.$psize;
			$list=pdo_fetchall($sql,$params);
			if ($list) {
				foreach ($list as $key => $value) {
					$list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
				}	 		 	 	 	 	 	 	 
				show_json(1,array('list'=>$list,'pagesize'=>$psize,'total'=>$total,'commissioncount'=>$mrew));	 	 	 	 	 
			}
			show_json(0,array('list'=>array(),'pagesize'=>$psize,'total'=>$total,'commissioncount'=>$mrew));
			
			break;
		case '3':
			if ($_GPC['applytype'] == 1) {
				$condition.=' and s.paytype != 3 ';
			}else{
				$condition.=' and s.paytype = 3 ';
			}	 

			$sql='select v.ctime,m.nickname,v.money,v.level,s.type,s.actid,s.paytype from '.tablename('sz_yi_settop_bonus_log').' v left join '.tablename('sz_yi_activity_settop_log').' s on s.id = v.logid left join '.tablename('sz_yi_member').' m on m.openid = s.openid where 1 ';	 	 
			$sql.=$condition;	 	 		 	 		 	 	  	  	 
			$sql.=' order by v.id desc ';
			$sql.='limit '.($pindex - 1) * $psize.' , '.$psize;
			$list=pdo_fetchall($sql,$params);	 	 	 
			if ($list) {	 		 	 		 	 		 	 		 	 	 
				foreach ($list as $key => $value) {
					$list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
					$act=m('activity')->getact($value['actid'],$value['type']);
					$list[$key]['title']=$act['title']?:'文章或活动不存在';
				}	 		 	 	 	 	 	 	 
				show_json(1,array('list'=>$list,'pagesize'=>$psize,'total'=>$total,'commissioncount'=>$st));	 	 	 	 	 
			}
			show_json(0,array('list'=>array(),'pagesize'=>$psize,'total'=>$total,'commissioncount'=>$st));
			
			break;	
		
		default:
			# code...
			break;
	}



	show_json(0,'');
}
include $this -> template('activity');

