<?php
/**
 * 欢迎入口
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */

defined('IN_IA') or exit('Access Denied');
$dos = array('merchant','dealmerch','platform', 'site', 'mc', 'setting', 'ext', 'solution','mall', 'fenxiao', 'supplier', 'bonus', 'cancel', 'authority', 'finance', 'statistics','suppliermenu','agency','activity','activityba','bartact');
$do = in_array($do, $dos) ? $do : 'platform';
$title = array('商家管理'=>'merchant','易货商家管理'=>'dealmerch','platform'=>'公众平台','site'=>'微站功能','mc'=>'会员及会员营销','setting'=>'功能选项','ext'=>'扩展功能','solution'=>'行业功能','mall'=>'商城系统','fenxiao'=>'分销系统','supplier'=>'供应链管理','bonus'=>'分红管理','cancel'=>'线下核销','authority'=>'分配权限','finance'=>'财务管理','statistics'=>'数据统计','suppliermenu'=>'供应商操作','agency'=>'代理商操作','activity'=>'易活动操作台','bartact'=>'易活动管理','activityba'=>'互动吧控制台');
$_W['page']['title'] = $title[$do];
define('FRAME', $do);
$frames = buildframes(array(FRAME), $_GPC['m']);
$frames = $frames[FRAME];
if (!empty($_W['setting']['permurls']['sections']) && !in_array($do, $_W['setting']['permurls']['sections'])) {
	header('Location: '.url('home/welcome/'.$_W['setting']['permurls']['sections'][0]));
	exit;
}

if( $do != 'solution') { 
	
	$settings = uni_setting($_W['uniacid'], array('shortcuts'));
	$shorts = $settings['shortcuts'];
	if(!is_array($shorts)) {
		$shorts = array();
	}
	$shortcuts = array();
	foreach($shorts as $i => $shortcut) {
		if (!empty($_W['setting']['permurls']['modules']) && !in_array($shortcut['name'], $_W['setting']['permurls']['modules'])) {
			continue;
		}
		$module = $modules[$shortcut['name']];
		if(!empty($module)) {
			$shortcut['title'] = $module['title'];
			if(file_exists('../addons/' . $module['name'] . '/icon-custom.jpg')) {
				$shortcut['image'] = '../addons/' . $module['name'] . '/icon-custom.jpg';
			} elseif(file_exists('../addons/' . $module['name'] . '/icon.jpg')) {
				$shortcut['image'] = '../addons/' . $module['name'] . '/icon.jpg';
			} else {
				$shortcut['image'] = '../web/resource/images/nopic-small.jpg';
			}
			$shortcut['link'] = wurl('home/welcome/ext', array('m'=>$shortcut['name']));
			$shortcuts[] = $shortcut;
		}
	}
	unset($shortcut);
}

//平台相关数据
if($do == 'platform') {
	$title = '平台相关数据';
	$sysmodules = system_modules();
	@$modules_other = array_diff(array_keys($modules), $sysmodules);
	$settings = uni_setting($_W['uniacid'], array('stat'));
	$day_num = !empty($settings['stat']['msg_maxday']) ? $settings['stat']['msg_maxday'] : 30;
	
	/*营销统计*/
	//昨日营业额：
	$yestoday1 = strtotime(date("Y-m-d 00:00:00", strtotime("-1 day"))); 
	$yestoday2 = strtotime(date("Y-m-d 23:59:59", strtotime("-1 day")));  
	$turnover1 = pdo_fetchcolumn('SELECT SUM(goodsprice) as goodsprice FROM ' . tablename('sz_yi_order') . ' WHERE uniacid = :uniacid AND status >= :status AND paytime >= :starttime AND paytime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => $yestoday1, ':endtime' => $yestoday2, ':status' => 1));
	
	//今日营业额：
	$today1 = strtotime(date("Y-m-d 00:00:00")); 
	$turnover2 = pdo_fetchcolumn('SELECT SUM(goodsprice) as goodsprice FROM ' . tablename('sz_yi_order') . ' WHERE uniacid = :uniacid AND status >= :status AND paytime >= :starttime AND paytime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => $today1, ':endtime' => TIMESTAMP, ':status' => 1));
	
	//昨日订单量：
	$order_sum1 = pdo_fetchcolumn('SELECT COUNT(*) as goodsprice FROM ' . tablename('sz_yi_order') . ' WHERE uniacid = :uniacid AND status >= :status AND paytime >= :starttime AND paytime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => $yestoday1, ':endtime' => $yestoday2, ':status' => 1));
	
	//今日订单量：
	$order_sum2 = pdo_fetchcolumn('SELECT COUNT(*) as goodsprice FROM ' . tablename('sz_yi_order') . ' WHERE uniacid = :uniacid AND status >= :status AND paytime >= :starttime AND paytime <= :endtime', array(':uniacid' => $_W['uniacid'], ':starttime' => $today1, ':endtime' => TIMESTAMP, ':status' => 1));
	//营业额
	if(!empty($turnover2)){
		$percentage1 = ($turnover2 - $turnover1)/$turnover2 * 100;
	}
	//订单量
	if(!empty($order_sum2)){
		$percentage2 = ($order_sum2 - $order_sum1)/$order_sum2 * 100;
	}
	/*分销员粉丝增长统计*/
	//昨日分销员数：
	$yestoday1 = strtotime(date("Y-m-d 00:00:00", strtotime("-1 day"))); 
	$yestoday2 = strtotime(date("Y-m-d 23:59:59", strtotime("-1 day")));  
	$yestoday_add_agent_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime >= :starttime AND followtime <= :endtime', array(':acid' => $_W['acid'], ':uniacid' => $_W['uniacid'], ':starttime' => $yestoday1, ':endtime' => $yestoday2, ':follow' => 1));
	
	//今日分销员数：
	$today_add_agent_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime >= :starttime AND followtime <= :endtime', array(':acid' => $_W['acid'], ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP, ':follow' => 1));
	
	//昨日粉丝数：
	$yestoday_add_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime >= :starttime AND followtime <= :endtime', array(':acid' => $_W['acid'], ':uniacid' => $_W['uniacid'], ':starttime' => $yestoday1, ':endtime' => $yestoday2, ':follow' => 1));
	
	//今日粉丝数：
	$today_add_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime >= :starttime AND followtime <= :endtime', array(':acid' => $_W['acid'], ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP, ':follow' => 1));
	
	
	/*本公众号的订单状态 status -1取消状态，0普通状态，1为已付款，2为已发货，3为成功*/
	//待付款订单
	$order_num1 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' WHERE uniacid = :uniacid AND status = :status ', array(':uniacid' => $_W['uniacid'], ':status' => 0));
	//待发货订单
	$order_num2 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' WHERE uniacid = :uniacid AND status = :status ', array(':uniacid' => $_W['uniacid'], ':status' => 1));
	//待收货订单
	$order_num3 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' WHERE uniacid = :uniacid AND status = :status ', array(':uniacid' => $_W['uniacid'], ':status' => 2));
	//已关闭订单
	$order_num4 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' WHERE uniacid = :uniacid AND status = :status ', array(':uniacid' => $_W['uniacid'], ':status' => -1));
	//本月完成订单
	$starttime = strtotime(date("Y-m-01"));
	$order_num5 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' WHERE uniacid = :uniacid AND status = :status AND finishtime >= :starttime', array(':uniacid' => $_W['uniacid'], ':starttime' => $starttime, ':status' => 3));
	//总完成订单
	$order_num6 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_order') . ' WHERE uniacid = :uniacid AND status = :status ', array(':uniacid' => $_W['uniacid'], ':status' => 3));


	if($_W['ispost']) {
		$m_name = trim($_GPC['m_name']);
		$starttime = strtotime("-{$day_num} day");
		$endtime = time();
		$data_hit = pdo_fetchall("SELECT * FROM ".tablename('stat_msg_history')." WHERE uniacid = :uniacid AND module = :module AND createtime >= :starttime AND createtime <= :endtime", array(':uniacid' => $_W['uniacid'], ':module' => $m_name, ':starttime' => $starttime, ':endtime' => $endtime));
		for($i = $day_num; $i >= 0; $i--){
			$key = date('m-d', strtotime('-' . $i . 'day'));
			$days[] = $key;
			$datasets[$key] = 0;
		}
		foreach($data_hit as $da) {
			$key1 = date('m-d', $da['createtime']);
			if(in_array($key1, $days)) {
				$datasets[$key1]++;
			}
		}
		$todaytimestamp = strtotime(date('Y-m-d'));
		$monthtimestamp = strtotime(date('Y-m'));
		$stat['month'] = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('stat_msg_history')." WHERE uniacid = :uniacid AND module = :module AND createtime >= '$monthtimestamp'", array(':uniacid' => $_W['uniacid'], ':module' => $m_name));
		$stat['today'] = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('stat_msg_history')." WHERE uniacid = :uniacid AND module = :module AND createtime >= '$todaytimestamp'", array(':uniacid' => $_W['uniacid'], ':module' => $m_name));
		$stat['rule'] = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('rule')." WHERE uniacid = :uniacid AND module = :module", array(':uniacid' => $_W['uniacid'], ':module' => $m_name));
		$stat['m_name'] = $m_name;
		exit(json_encode(array('key' => $days, 'value' => array_values($datasets), 'stat' => $stat)));
	}

	$today_add_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime >= :starttime AND followtime <= :endtime', array(':acid' => $_W['acid'], ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP, ':follow' => 1));
	$today_cancel_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND unfollowtime >= :starttime AND unfollowtime <= :endtime', array(':acid' => $_W['acid'], ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP, ':follow' => 0));
	$today_jing_num = $today_add_num - $today_cancel_num;
	$today_total_num = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('mc_mapping_fans') . ' WHERE acid = :acid AND uniacid = :uniacid AND follow = :follow AND followtime <= :endtime', array(':acid' => $_W['acid'], ':uniacid' => $_W['uniacid'], ':endtime' => TIMESTAMP, ':follow' => 1));

	load()->model('reply');
	$cfg = $modules['userapi']['config'];
	$ds = reply_search("`uniacid` = 0 AND module = 'userapi' AND `status`=1");
	$apis = array();
	foreach($ds as $row) {
		$apis[$row['id']] = $row; 
	}
	$ds = array();
	foreach($apis as $row) {
		$reply = pdo_fetch('SELECT * FROM ' . tablename('userapi_reply') . ' WHERE `rid`=:rid', array(':rid' => $row['id']));
		$r = array();
		$r['title'] = $row['name'];
		$r['rid'] = $row['id'];
		$r['description'] = $reply['description'];
		$r['switch'] = $cfg[$r['rid']] ? true : false;
		$ds[] = $r;
	}
	$apis = $ds;
	
	$accounts = uni_accounts();
	$accounttypes = account_types();
	$mtypes = array();
	$mtypes['image'] = '图片消息';
	$mtypes['voice'] = '语音消息';
	$mtypes['video'] = '视频消息';
	$mtypes['location'] = '位置消息';
	$mtypes['link'] = '链接消息';
	$mtypes['subscribe'] = '粉丝开始关注';

	$setting = uni_setting($_W['uniacid'], array('default_message'));
	$ds = array();
	foreach($mtypes as $k => $v) {
		$row = array();
		$row['type'] = $k;
		$row['title'] = $v;
		$row['handles'] = array();
		if(!empty($modules)){
			foreach($modules as $m) {
				if(is_array($m['handles']) && in_array($k, $m['handles'])) {
					$row['handles'][] = array('name' => $m['name'], 'title' => $m['title']);
				}

			}
		}
		$row['empty'] = empty($row['handles']);
		$row['current'] = is_array($setting['default_message']) ? $setting['default_message'][$k] : '';
		$ds[] = $row;
	}
	$qrs = pdo_fetchall("SELECT acid, COUNT(*) as num, model FROM ".tablename('qrcode')." WHERE uniacid=:uniacid GROUP BY acid, model", array(':uniacid'=>$_W['uniacid']));
	$tyqr = array('qr1num'=>0,'qr2num'=>0);
	foreach ($qrs as $qr) {
		$acid = intval($qr['acid']);
		if(intval($accounts[$acid]['level']) < 4){
			continue;
		}
		if(intval($qr['model']) == 1){
			$accounts[$acid]['qr1num'] = $qr['num'];
			$tyqr['qr1num'] += $qr['num'];
		} else {
			$accounts[$acid]['qr2num'] = $qr['num'];
			$tyqr['qr2num'] += $qr['num'];
		}
	}
}

//微站功能概况
if($do == 'site') {
	header('Location: index.php?c=site&a=multi&do=display&');//支付参数
	exit;
	$title = '微站功能概况';
	$setting = uni_setting($_W['uniacid'], array('default_site'));
	$default_site = intval($setting['default_site']);
	$setting = pdo_fetch('SELECT styleid,id FROM ' . tablename('site_multi') . ' WHERE uniacid =:uniacid AND id = :id ', array(':uniacid' => $_W['uniacid'], ':id' => $setting['default_site']));
	$templates_id = pdo_fetchcolumn('SELECT templateid FROM ' . tablename('site_styles') . ' WHERE id = :id', array(':id' => $setting['styleid']));
	$template = pdo_fetch('SELECT * FROM ' . tablename('site_templates') . ' WHERE id = :id ', array(':id' => $templates_id));
	$sql = "SELECT rid FROM " . tablename('cover_reply') . ' WHERE `module` = :module AND uniacid = :uniacid AND multiid = :multiid';
	$pars = array();
	$pars[':module'] = 'site';
	$pars[':uniacid'] = $_W['uniacid'];
	$pars[':multiid'] = $setting['id'];
	$cover = pdo_fetch($sql, $pars);
	if(!empty($cover['rid'])) {
		$keywords = pdo_fetchall("SELECT content FROM ".tablename('rule_keyword')." WHERE rid = :rid", array(':rid' => $cover['rid']));
	}
	load()->model('app');
	$home_navs = app_navs('home', $setting['id']);
	$profile_navs = app_navs('profile');
		$slides = pdo_fetchall("SELECT * FROM ".tablename('site_slide')." WHERE uniacid = '{$_W['uniacid']}' AND multiid = {$default_site}  ORDER BY displayorder DESC, id DESC ");
	foreach ($slides as $key=>$value) {
		$slides[$key]['thumb'] = tomedia($value['thumb']);
	}
}
 
//会员功能概况
if($do == 'mc') {
	header('Location: index.php?c=mc&a=fans&');//
	exit;
	$title = '会员功能概况';
	$accounts = uni_accounts($_W['uniacid']);
	foreach ($accounts as $acid => &$account) {
		$num = pdo_fetchcolumn('SELECT COUNT(fanid) FROM '.tablename('mc_mapping_fans').' WHERE acid=:acid AND follow=1 ', array(':acid'=> $acid));
		$account['fansnum'] = intval($num);
	}
	$uniaccount = uni_fetch();
	$num = pdo_fetchcolumn('SELECT COUNT(fanid) FROM '.tablename('mc_mapping_fans').' WHERE uniacid=:uniacid AND follow=1', array(':uniacid'=> $_W['uniacid']));
	$uniaccount['fansnum'] = intval($num);
	
	$num = pdo_fetchcolumn('SELECT COUNT(uid) FROM '.tablename('mc_members').' WHERE uniacid=:uniacid ', array(':uniacid'=> $_W['uniacid']));
	$uniaccount['membernum'] = intval($num);
	
		$coupons = pdo_fetchall('SELECT * FROM ' . tablename('activity_coupon') . " WHERE uniacid = '{$_W['uniacid']}' AND type = 1 ORDER BY couponid DESC ");
		$tokens = pdo_fetchall('SELECT * FROM ' . tablename('activity_coupon') . " WHERE uniacid = '{$_W['uniacid']}' AND type = 2 ORDER BY couponid DESC ");
}

//扩展功能概况
if($do == 'ext') {
	$title = '扩展功能概况';
	$installedmodulelist = uni_modules(false);
	foreach ($installedmodulelist as $k => &$value) {
		$value['official'] = empty($value['issystem']) && (strexists($value['author'], 'WeEngine Team') || strexists($value['author'], '微擎团队'));
	}
	$m = $_GPC['m'];
	if(empty($m)) {
		foreach($installedmodulelist as $name => $module) {
			if (!empty($_W['setting']['permurls']['modules']) && !in_array($name, $_W['setting']['permurls']['modules'])) {
				continue;
			}

			if($module['issystem']) {
				$path = '../framework/builtin/' . $module['name'];
			} else {
				$path = '../addons/' . $module['name'];
			}
			$cion = $path . '/icon-custom.jpg';
			if(!file_exists($cion)) {
				$cion = $path . '/icon.jpg';
				if(!file_exists($cion)) {
					$cion = './resource/images/nopic-small.jpg';
				}
			}
			$module['icon'] = $cion;

			if($module['enabled'] == 1) {
				$enable_modules[$name] = $module;
			} else {
				$unenable_modules[$name] = $module;
			}
		}
		$moudles = true;
	} else {
		$module = $installedmodulelist[$m];
		$title .= ' - ' . $module['title'];
		$entries = module_entries($m, array('menu', 'home', 'profile', 'shortcut', 'cover', 'mine'));
		$status = uni_user_permission_exist();
		if(is_error($status)) {
			$permission = uni_user_permission($m);
			if($permission[0] != 'all') {
				if(!in_array($m.'_rule', $permission)) {
					unset($module['isrulefields']);
				}
				if(!in_array($m.'_settings', $permission)) {
					unset($module['settings']);
				}
				if(!in_array($m.'_home', $permission)) {
					unset($entries['home']);
				}
				if(!in_array($m.'_profile', $permission)) {
					unset($entries['profile']);
				}
				if(!in_array($m.'_shortcut', $permission)) {
					unset($entries['shortcut']);
				}
				if(!empty($entries['cover'])) {
					foreach($entries['cover'] as $k => $row) {
						if(!in_array($m.'_cover_'.$row['do'], $permission)) {
							unset($entries['cover'][$k]);
						}
					}
				}
				if(!empty($entries['menu'])) {
					foreach($entries['menu'] as $k => $row) {
						if(!in_array($m.'_menu_'.$row['do'], $permission)) {
							unset($entries['menu'][$k]);
						}
					}
				}
			}
		}
	}
}


//功能参数概况
if($do == 'setting') {
	header('Location: index.php?c=profile&a=payment&');//支付参数
	exit;
}
//商城系统
if($do == 'mall') {
	header('Location: index.php?c=site&a=entry&op=display&do=order&m=sz_yi');//订单
	exit;
}
//分销系统
if($do == 'fenxiao') {
	header('Location: index.php?c=site&a=entry&method=agent&p=commission&do=plugin&m=sz_yi');//代理商
	exit;
}


//供应商操作
if($do == 'suppliermenu') {
	header('Location: index.php?c=site&a=entry&method=goods&p=suppliermenu&do=plugin&m=sz_yi');//代理商
	exit;
}

//代理商操作
if($do == 'agency') {
	header('Location: index.php?c=site&a=entry&method=goods&p=agency&do=plugin&m=sz_yi');//代理商
	exit;
}

//代理商操作
if($do == 'activity') {
	header('Location: index.php?c=site&a=entry&method=activity&p=activity&do=plugin&m=sz_yi');//易活动
	exit;
}

if($do == 'activityba') {
	header('Location: index.php?c=site&a=entry&method=activity&p=activityba&do=plugin&m=sz_yi&op=basic');//易活动
	exit;	 	
}	 		 		 	 	 		 	 	

//商家管理
if($do == 'merchant') {
    header('Location: index.php?c=site&a=entry&method=merchant&p=merchant&do=plugin&m=sz_yi');//代理商
    exit;
}

//商家管理
if($do == 'bartact') {
    header('Location: index.php?c=site&a=entry&method=slide&p=bartact&do=plugin&m=sz_yi');//易货商家管理
    exit;
}

//商家管理
if($do == 'dealmerch') {
    header('Location: index.php?c=site&a=entry&method=dealmerch&p=dealmerch&do=plugin&m=sz_yi');//易货商家管理
    exit; 	 	
}

//供应链管理
if($do == 'supplier') {
	header('Location: index.php?c=site&a=entry&method=supplier&p=supplier&do=plugin&m=sz_yi');//供货商
	exit;
}
//分红管理
if($do == 'bonus') {
	header('Location: index.php?c=site&a=entry&method=agent&p=bonus&do=plugin&m=sz_yi');//代理商
	exit;
}
//线下核销
if($do == 'cancel') {
	header('Location: index.php?c=site&a=entry&method=saler&p=verify&do=plugin&m=sz_yi');//核消员
	exit;
}
//财务管理
if($do == 'finance') {
	header('Location: index.php?c=site&a=entry&p=log&type=0&do=finance&m=sz_yi');//充值
	exit;
}
//统计
if($do == 'statistics') {
	header('Location: index.php?c=site&a=entry&p=sale&do=statistics&m=sz_yi');//销售统计
	exit;
}
//分配权限
if($do == 'authority') {
	header('Location: index.php?c=site&a=entry&method=plugins&p=perm&do=plugin&m=sz_yi');//公众号权限
	exit;
}

template('home/welcome');