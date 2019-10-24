<?php

//decode by QQ:270656184 http://www.yunlu99.com/

if (!defined('IN_IA')) {

	exit('Access Denied');

}

global $_W, $_GPC;

$set =$this->model->getset();

$openid = m('user')->getOpenid();

$member = m('member')->getInfo($openid);

$ca = empty($_GPC['op']) ? '' : $_GPC['op'];

if ($ca == 'update') {

	$to = $_GPC['to'];

	$data = array();

	// 1是正面，2是反面, 3是营业执照

	if ($to == 1) {

		$data['idimg1'] = $_GPC['url'];

	} elseif ($to == 2) {

		$data['idimg2'] = $_GPC['url'];

	} elseif ($to == 3) {

		$data['permit'] = $_GPC['url'];

	}



	$a = pdo_fetch('select id from'.tablename('sz_yi_supplier_idimages').'where uniacid='.$_W['uniacid'].' and openid='."'$openid'");



	$data['uniacid'] = $_W['uniacid'];

	$data['openid'] = $openid;



	if (empty($a)) {

		// insert

		pdo_insert('sz_yi_supplier_idimages', $data);

	} else {

		// update

		pdo_update('sz_yi_supplier_idimages', $data, array('openid' => $openid));

	}

}



$af_supplier = pdo_fetch('select * from ' . tablename('sz_yi_af_supplier') . " where openid='{$openid}' and uniacid={$_W['uniacid']}");

if ($af_supplier['status'] == 2) {

	header('Location:'.$this->createPluginMobileUrl('suppliermenu/index'));

	exit;

}

$af_supplier = pdo_fetch('select * from ' . tablename('sz_yi_af_supplier') . " where openid='{$openid}' and uniacid={$_W['uniacid']}");

$diyform_plugin = p('diyform');

$order_formInfo = false;

if ($diyform_plugin) {

	$diyform_set = $diyform_plugin->getSet();

	if (!empty($diyform_set['supplier_diyform_open'])) {

		$supplierdiyformid = intval($diyform_set['supplier_diyform']);

		if (!empty($supplierdiyformid)) {

			$supplier_formInfo = $diyform_plugin->getDiyformInfo($supplierdiyformid);

			$fields = $supplier_formInfo['fields'];

			$f_data = $diyform_plugin->getLastOrderData($supplierdiyformid, $member);

		}

	}

}

// 查询商品分类 (行业类别)

$sql = 'select name from'.tablename('sz_yi_category').'where uniacid = :uniacid and parentid = 0 and enabled = 1';

$category = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));

if ($_W['isajax']) {

	if ($_W['ispost']) {

		$memberdata = array(

			'realname'    => $_GPC['memberdata']['realname'],

			'mobile'      => $_GPC['memberdata']['mobile'],

			'weixin'      => $_GPC['memberdata']['weixin'],

			'qq'          => $_GPC['memberdata']['qq'],

			'productname' => $_GPC['memberdata']['productname'],

			'username'    => $_W['uniacid'].'_'.trim( $_GPC['memberdata']['username'] ),

			'password'    => $_GPC['memberdata']['password'],

			'openid'      => $openid,

			'uniacid'     => $_W['uniacid'],

			'type'        => $_GPC['memberdata']['applytype'],       //本地商家

			//'ctime'		  =>time()

		);

		$result = pdo_fetch('select * from ' . tablename('users') . ' where username=\'' . $memberdata['username'] . '\'');

		if (!empty($result)) {

			show_json(2);

		}

		if(intval($_GPC['memberdata']['applytype'])==3){      //type=3   全国商家

		    $memberdata['merch']=1;

		    $memberdata['type']=2;

		}

		

		if (empty($af_supplier)) {

			$res = pdo_insert('sz_yi_af_supplier', $memberdata);

			$res && show_json(1);

		} else {

			$memberdata['status'] = 0;

			$memberdata['account'] = '';

			unset($memberdata['username']); // 不允许修改

			$res = pdo_update('sz_yi_af_supplier', $memberdata, array('openid' => $openid));

			$res && show_json(1);

		}

	}

	show_json(1, array('member' => $member));

}

include $this->template('af_supplier');