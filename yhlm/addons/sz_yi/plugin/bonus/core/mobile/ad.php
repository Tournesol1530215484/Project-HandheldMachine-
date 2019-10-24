<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$ac = $_GPC['ac'] ;
$openid = m('user') -> getOpenid();
$mid = m('member') -> getMid();	//自己member表id
$member=m('member')->getMember($openid);
$uniacid = $_W['uniacid'];
if ($_W['isajax']) {

	$condition=' and l.uniacid = :uniacid and l.openid = :openid ';
	$params=array(
		':uniacid'=>$_W['uniacid'],
		':openid' => $openid
	);

	switch ($member['bonus_area']) {
		case '1':
			$condition.=' and l.level = 5 ';
			$tocon=' and level  = 5 ';
			break;
		case '2':
			$condition.=' and l.level = 4 ';
			$tocon=' and level  = 4 ';
			break;
		
		case '3':
			$condition.=' and l.level = 3 ';
			$tocon=' and level  = 3 ';
			break;

		default:
			
			break;
	}


	$condition.=' and l.bonusType = :status ';
	$params[':status']=$_GPC['status'];


	$pindex=max(1,intval($_GPC['page']));
	$psize=12;
	$sql='select m.nickname,l.money,l.ctime,l.level,am.title from '.tablename('sz_yi_ad_bonus_log').' l left join '.tablename('sz_yi_obtain_bonus').' ob on ob.id = l.obid left join '.tablename('sz_yi_member').' m on m.openid = ob.openid left join '.tablename('sz_yi_ad_model').' am on l.adid = am.id  where 1';
	$sql.=$condition;	 	 
	$sql.=' order by l.ctime desc ';
	$sql.='limit '.($pindex  -1 ) * $psize.' , '.$psize;	 	 
	$list=pdo_fetchall($sql,$params);

	$total=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_ad_bonus_log').' where 1 and uniacid = :uniacid and bonusType = :status 	and openid = :openid '.$tocon,$params);	 

	unset($params[':status']);
	$commissioncount=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_ad_bonus_log').' where 1 and uniacid = :uniacid and bonusType = 1 and openid = :openid '.$tocon,$params);

	$commissioncount2=pdo_fetchcolumn('select sum(money) from '.tablename('sz_yi_ad_bonus_log').' where 1 and uniacid = :uniacid and bonusType = 2 and openid = :openid '.$tocon,$params);

	if ($list) {
		foreach ($list as $key => $value) {
			$list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
		}
		show_json(1,array('list'=>$list,'pagesize'=>$psize,'total'=>$total,'commissioncount'=>$commissioncount,'commissioncount2'=>$commissioncount2));
	}
	show_json(0,array('list'=>array(),'pagesize'=>$psize,'total'=>$total,'commissioncount'=>$commissioncount,'commissioncount2'=>$commissioncount2));

}

include $this -> template('ad');