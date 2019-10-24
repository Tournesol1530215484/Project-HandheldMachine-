<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	print ('Access Denied');
}
global $_W, $_GPC;

//全返
if(p('return')) {$set_return = p('return') -> getSet();
 } 
 

//购物返
if( p('returnmatter')) $set_returnmatter = p('returnmatter') ->getSet(); 
 

if (p('supplier')) {
	$roleid = pdo_fetchcolumn('select roleid from' . tablename('sz_yi_perm_user') . ' where uid=' . $_W['uid'] . ' and uniacid=' . $_W['uniacid']);
	if ($roleid == 0) {
		$perm_role = 0;
	} else {
		if (p('supplier')) {
			$perm_role = pdo_fetchcolumn('select status1 from' . tablename('sz_yi_perm_role') . ' where uniacid = '.$_W['uniacid'].' and id=' . $roleid);
		} else {
			$perm_role = 0;
		}
	}
}
$pluginbonus = p('bonus');
$bonus_start = 0;
if (!empty($pluginbonus)) {
	$bonus_set = $pluginbonus->getSet();
	if (!empty($bonus_set['start'])) {
		$bonus_start = 1;
	}
}
$shopset = m('common')->getSysset('shop');
$sql = 'SELECT * FROM ' . tablename('sz_yi_category') . ' WHERE `uniacid` = :uniacid ORDER BY `parentid`, `displayorder` DESC';
$category = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']), 'id');
$parent = $children = array();
if (!empty($category)) {
	foreach ($category as $cid => $cate) {
		if (!empty($cate['parentid'])) {
			$children[$cate['parentid']][] = $cate;
		} else {
			$parent[$cate['id']] = $cate;
		}
	}
}
if (p('commission')) {
	$commissionLevels = pdo_fetchall('SELECT id, levelname FROM ' . tablename('sz_yi_commission_level') . ' WHERE `uniacid` = :uniacid ORDER BY `commission1` DESC, `commission2` DESC, `commission3` DESC', array(':uniacid' => $_W['uniacid']));
}


if (p('bonus')) {
	 $bonusLevels = pdo_fetchall('SELECT id, levelname FROM ' . tablename('sz_yi_bonus_level') . ' WHERE `uniacid` = :uniacid  ', array(':uniacid' => $_W['uniacid']));
}

 
$setting = m('common')->getSetData();

$setting = unserialize($setting['plugins']);

$setting = $setting['commission'] ;


$commissionLevels[] = array('key'=>'default','id'=>'default','levelname'=>$setting['levelname']);


$commission_level = $commissionLevels;




$pv = p('virtual');
$diyform_plugin = p('diyform');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

if ($operation == 'query') {
    $kwd                = trim($_GPC['keyword']);
    $params             = array();
    $params[':uniacid'] = $_W['uniacid'];
    $condition          = " and uniacid=:uniacid";
    if (!empty($kwd)) {
        if (is_numeric($kwd)){
            $condition .= " AND `mobile` LIKE :keyword";
            $params[':keyword'] = "{$kwd}";
        }else{
            $condition .= " AND `title` LIKE :keyword";
            $params[':keyword'] = "%{$kwd}%";
        }
    }

    $ds = pdo_fetchall('SELECT id,storename FROM ' . tablename('sz_yi_exchange_address') . " WHERE 1 {$condition} order by id asc", $params);
    include $this->template('query_store');
    exit;
}
if ($operation == 'change') {
	$id = intval($_GPC['id']);
	if (empty($id)) {
		exit;
	}
	$type = trim($_GPC['type']);
	$value = trim($_GPC['value']);
	if (!in_array($type, array('title', 'marketprice', 'total', 'goodssn', 'productsn'))) {
		exit;
	}
	$goods = pdo_fetch('select id from ' . tablename('sz_yi_goods') . ' where id=:id and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $id));
	if (empty($goods)) {
		exit;
	}
	
	pdo_update('sz_yi_goods', array($type => $value), array('id' => $id));
	exit;
} else if ($operation == 'post') {
 
	$id = intval($_GPC['id']); 
	$audit_log=pdo_fetchall('select * from '.tablename('sz_yi_goods_log').' where uniacid = :uniacid and goodsid = :goodsid',array(':goodsid'=>$id,':uniacid'=>$_W['uniacid']));

	if (!empty($id)) { 
		ca('dealmerch.goods');
	} else {
		ca('dealmerch.goods');
	}
	$result = pdo_fetchall('SELECT uid,realname,username FROM ' . tablename('sz_yi_perm_user') . ' where dealmerchid >0 and uniacid =' . $_W['uniacid']);
	$id = intval($_GPC['id']);
	if (!empty($id)) {
		ca('dealmerch.goods');
	} else {
		ca('dealmerch.goods');
	}
	$levels = m('member')->getLevels();
	$groups = m('member')->getGroups();
	if (!empty($id)) {
		$item = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_goods') . ' WHERE id = :id', array(':id' => $id));
		$selmerch=p('bonus')->getmerch($item['supplier_uid']);	 	 
		$commission_type = $item['commission_type'];	 	 	 	 		 

		if ($item['extnotice']) {
			$noticepeople=pdo_fetchall('select id,openid,avatar,nickname,realname,mobile from '.tablename('sz_yi_member').' where uniacid = :uniacid and id in ('.$item['extnotice'].')',array(':uniacid'=>$_W['uniacid']));
		}	 	

		$commission = $item['commission'];	 	 	 	

		$commission = json_decode($commission,true);	 	 	 	 

		if (empty($item)) {
			message('抱歉，商品不存在或是已经删除！', '', 'error');	 	 
		}
		$noticetype = explode(',', $item['noticetype']);	 	 
		if ($shopset['catlevel'] == 3) {
			$cates = explode(',', $item['tcates']);
		} else {
			$cates = explode(',', $item['ccates']);
		}
		$discounts = json_decode($item['discounts'], true);
		$allspecs = pdo_fetchall('select * from ' . tablename('sz_yi_goods_spec') . ' where goodsid=:id order by displayorder asc', array(':id' => $id));
		foreach ($allspecs as &$s) {
			$s['items'] = pdo_fetchall('select a.id,a.specid,a.title,a.thumb,a.show,a.displayorder,a.valueId,a.virtual,b.title as title2 from ' . tablename('sz_yi_goods_spec_item') . ' a left join ' . tablename('sz_yi_virtual_type') . ' b on b.id=a.virtual  where a.specid=:specid order by a.displayorder asc', array(':specid' => $s['id']));
		}
		unset($s);
		$params = pdo_fetchall('select * from ' . tablename('sz_yi_goods_param') . ' where goodsid=:id order by displayorder asc', array(':id' => $id));
		$piclist1 = unserialize($item['thumb_url']);
		$piclist = array();
		if (is_array($piclist1)) {
			foreach ($piclist1 as $p) {
				$piclist[] = is_array($p) ? $p['attachment'] : $p;
			}
		}
		$headPics1 = unserialize($item['headPic']);
        $headPics = array();
		if (is_array($headPics1)) {
			foreach ($headPics1 as $p) {
                $headPics[] = is_array($p) ? $p['attachment'] : $p;
			}
		}

		$html = "";
		$options = pdo_fetchall('select * from ' . tablename('sz_yi_goods_option') . ' where goodsid=:id order by id asc', array(':id' => $id));
		$specs = array();
		if (count($options) > 0) {
			$specitemids = explode('_', $options[0]['specs']);
			foreach ($specitemids as $itemid) {
				foreach ($allspecs as $ss) {
					$items = $ss['items'];
					foreach ($items as $it) {
						if ($it['id'] == $itemid) {
							$specs[] = $ss;
							break;
						}
					}
				}
			}
			$html = '';
			$html .= '<table class="table table-bordered table-condensed">';
			$html .= '<thead>';
			$html .= '<tr class="active">';
			$len = count($specs);
			$newlen = 1;
			$h = array();
			$rowspans = array();
			for ($i = 0; $i < $len; $i++) {
				$html .= '<th style=\'width:80px;\'>' . $specs[$i]['title'] . '</th>';
				$itemlen = count($specs[$i]['items']);
				if ($itemlen <= 0) {
					$itemlen = 1;
				}
				$newlen *= $itemlen;
				$h = array();
				for ($j = 0; $j < $newlen; $j++) {
					$h[$i][$j] = array();
				}
				$l = count($specs[$i]['items']);
				$rowspans[$i] = 1;
				for ($j = $i + 1; $j < $len; $j++) {
					$rowspans[$i] *= count($specs[$j]['items']);
				}
			}
			$canedit = ce('shop.goods', $item);
			if ($canedit) {
				$html .= '<th class="info" style="width:130px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">库存</div><div class="input-group"><input type="text" class="form-control option_stock_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_stock\');"></a></span></div></div></th>';
				$html .= '<th class="success" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">销售价格</div><div class="input-group"><input type="text" class="form-control option_marketprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_marketprice\');"></a></span></div></div></th>';
				$html .= '<th class="warning" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">市场价格</div><div class="input-group"><input type="text" class="form-control option_productprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_productprice\');"></a></span></div></div></th>';
				$html .= '<th class="danger" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">成本价格</div><div class="input-group"><input type="text" class="form-control option_costprice_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_costprice\');"></a></span></div></div></th>';
				$html .= '<th class="primary" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">商品编码</div><div class="input-group"><input type="text" class="form-control option_goodssn_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_goodssn\');"></a></span></div></div></th>';
				$html .= '<th class="danger" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">商品条码</div><div class="input-group"><input type="text" class="form-control option_productsn_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_productsn\');"></a></span></div></div></th>';
				$html .= '<th class="primary" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">重量（克）</div><div class="input-group"><input type="text" class="form-control option_weight_all"  VALUE=""/><span class="input-group-addon"><a href="javascript:;" class="fa fa-hand-o-down" title="批量设置" onclick="setCol(\'option_weight\');"></a></span></div></div></th>';
				$html .= '</tr></thead>';
			} else {
				$html .= '<th class="info" style="width:130px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">库存</div></div></th>';
				$html .= '<th class="success" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">销售价格</div></div></th>';
				$html .= '<th class="warning" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">市场价格</div></div></th>';
				$html .= '<th class="danger" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">成本价格</div></div></th>';
				$html .= '<th class="primary" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">商品编码</div></div></th>';
				$html .= '<th class="danger" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">商品条码</div></div></th>';
				$html .= '<th class="primary" style="width:150px;"><div class=""><div style="padding-bottom:10px;text-align:center;font-size:16px;">重量（克）</div></th>';
				$html .= '</tr></thead>';
			}
			for ($m = 0; $m < $len; $m++) {
				$k = 0;
				$kid = 0;
				$n = 0;
				for ($j = 0; $j < $newlen; $j++) {
					$rowspan = $rowspans[$m];
					if ($j % $rowspan == 0) {
						$h[$m][$j] = array('html' => '<td rowspan=\'' . $rowspan . '\'>' . $specs[$m]['items'][$kid]['title'] . '</td>', 'id' => $specs[$m]['items'][$kid]['id']);
					} else {
						$h[$m][$j] = array('html' => "", 'id' => $specs[$m]['items'][$kid]['id']);
					}
					$n++;
					if ($n == $rowspan) {
						$kid++;
						if ($kid > count($specs[$m]['items']) - 1) {
							$kid = 0;
						}
						$n = 0;
					}
				}
			}
			$hh = "";
			for ($i = 0; $i < $newlen; $i++) {
				$hh .= '<tr>';
				$ids = array();
				for ($j = 0; $j < $len; $j++) {
					$hh .= $h[$j][$i]['html'];
					$ids[] = $h[$j][$i]['id'];
				}
				$ids = implode('_', $ids);
				$val = array('id' => "", 'title' => "", 'stock' => "", 'costprice' => "", 'productprice' => "", 'marketprice' => "", 'weight' => "", 'virtual' => '');
				foreach ($options as $o) {
					if ($ids === $o['specs']) {
						$val = array('id' => $o['id'], 'title' => $o['title'], 'stock' => $o['stock'], 'costprice' => $o['costprice'], 'productprice' => $o['productprice'], 'marketprice' => $o['marketprice'], 'goodssn' => $o['goodssn'], 'productsn' => $o['productsn'], 'weight' => $o['weight'], 'virtual' => $o['virtual']);
						break;
					}
				}
				if ($canedit) {
					$hh .= '<td class="info">';
					$hh .= '<input name="option_stock_' . $ids . '[]"  type="text" class="form-control option_stock option_stock_' . $ids . '" value="' . $val['stock'] . '"/>';
					$hh .= '<input name="option_id_' . $ids . '[]"  type="hidden" class="form-control option_id option_id_' . $ids . '" value="' . $val['id'] . '"/>';
					$hh .= '<input name="option_ids[]"  type="hidden" class="form-control option_ids option_ids_' . $ids . '" value="' . $ids . '"/>';
					$hh .= '<input name="option_title_' . $ids . '[]"  type="hidden" class="form-control option_title option_title_' . $ids . '" value="' . $val['title'] . '"/>';
					$hh .= '<input name="option_virtual_' . $ids . '[]"  type="hidden" class="form-control option_title option_virtual_' . $ids . '" value="' . $val['virtual'] . '"/>';
					$hh .= '</td>';
					$hh .= '<td class="success"><input name="option_marketprice_' . $ids . '[]" type="text" class="form-control option_marketprice option_marketprice_' . $ids . '" value="' . $val['marketprice'] . '"/></td>';
					$hh .= '<td class="warning"><input name="option_productprice_' . $ids . '[]" type="text" class="form-control option_productprice option_productprice_' . $ids . '" " value="' . $val['productprice'] . '"/></td>';
					$hh .= '<td class="danger"><input name="option_costprice_' . $ids . '[]" type="text" class="form-control option_costprice option_costprice_' . $ids . '" " value="' . $val['costprice'] . '"/></td>';
					$hh .= '<td class="primary"><input name="option_goodssn_' . $ids . '[]" type="text" class="form-control option_goodssn option_goodssn_' . $ids . '" " value="' . $val['goodssn'] . '"/></td>';
					$hh .= '<td class="danger"><input name="option_productsn_' . $ids . '[]" type="text" class="form-control option_productsn option_productsn_' . $ids . '" " value="' . $val['productsn'] . '"/></td>';
					$hh .= '<td class="primary"><input name="option_weight_' . $ids . '[]" type="text" class="form-control option_weight option_weight_' . $ids . '" " value="' . $val['weight'] . '"/></td>';
					$hh .= '</tr>';
				} else {
					$hh .= '<td class="info">' . $val['stock'] . '</td>';
					$hh .= '<td class="success">' . $val['marketprice'] . '</td>';
					$hh .= '<td class="warning">' . $val['productprice'] . '</td>';
					$hh .= '<td class="danger">' . $val['costprice'] . '</td>';
					$hh .= '<td class="primary">' . $val['goodssn'] . '</td>';
					$hh .= '<td class="danger">' . $val['productsn'] . '</td>';
					$hh .= '<td class="primary">' . $val['weight'] . '</td>';
					$hh .= '</tr>';
				}
			}
			$html .= $hh;
			$html .= '</table>';
		}
		if ($item['showlevels'] != '') {
			$item['showlevels'] = explode(',', $item['showlevels']);
		}
		if ($item['buylevels'] != '') {
			$item['buylevels'] = explode(',', $item['buylevels']);
		}
		if ($item['showgroups'] != '') {
			$item['showgroups'] = explode(',', $item['showgroups']);
		}
		if ($item['buygroups'] != '') {
			$item['buygroups'] = explode(',', $item['buygroups']);
		}
		$stores = array();
		if (!empty($item['storeids'])) {
			$stores = pdo_fetchall('select * from ' . tablename('sz_yi_exchange_address') . ' where id in (' . $item['storeids'] . ' ) and uniacid=' . $_W['uniacid']);
		}
		if (!empty($item['noticeopenid'])) {
			$saler = m('member')->getMember($item['noticeopenid']);
		}else{
			$tempinfo=p('bonus')->getMerch($item['supplier_uid']);
			$saler = m('member')->getMember($tempinfo['openid']);
		}	 	 	
	}	 	 	
	if (empty($category)) {
		message('抱歉，请您先添加商品分类！', $this->createWebUrl('shop/category', array('op' => 'post')), 'error');
	}
	$dispatch_data = pdo_fetchall('select * from' . tablename('sz_yi_dispatch') . 'where uniacid =:uniacid and enabled = 1 order by displayorder desc', array(':uniacid' => $_W['uniacid']));
	if (checksubmit('submit')) {	 
		// print_r($_GPC);
		// exit;	 	 	 
		if ($diyform_plugin) {
			if ($_GPC['type'] == 1 && $_GPC['diyformtype'] == 2) {
				message('替换模式只试用于虚拟物品类型，实体物品无效！请重新选择！');
			}
		}
		if (empty($_GPC['goodsname'])) {
			message('请输入商品名称！');
		}
		if (empty($_GPC['category']['parentid'])) {
			message('请选择商品分类！');
		}
		if (empty($_GPC['thumbs'])) {
			$_GPC['thumbs'] = array();
		}

		$data = array(
		    'uniacid' => intval($_W['uniacid']),
            'displayorder' => intval($_GPC['displayorder']),
            'title' => trim($_GPC['goodsname']),
            'pcate' => intval($_GPC['category']['parentid']),
            'ccate' => intval($_GPC['category']['childid']),
            'tcate' => intval($_GPC['category']['thirdid']),
            'type' => 8,            //易货商品
            'thumb' => save_media($_GPC['headPic'][0]),
            'isrecommand' => intval($_GPC['isrecommand']),
            'ishot' => intval($_GPC['ishot']),
            'isnew' => intval($_GPC['isnew']),
            'isdiscount' => intval($_GPC['isdiscount']),
            'issendfree' => intval($_GPC['issendfree']),
            'isnodiscount' => intval($_GPC['isnodiscount']),
            'istime' => intval($_GPC['istime']),
            'timestart' => strtotime($_GPC['timestart']),
            'timeend' => strtotime($_GPC['timeend']),
            'description' => trim($_GPC['description']),
            'goodssn' => trim($_GPC['goodssn']),
            'unit' => trim($_GPC['unit']),
            'createtime' => TIMESTAMP,
            'total' => intval($_GPC['total']),
            'totalcnf' => intval(1),
            'marketprice' => $_GPC['marketprice'],
            'weight' => $_GPC['weight'],
            'costprice' => $_GPC['costprice'],
            'productprice' => trim($_GPC['productprice']),
            'productsn' => trim($_GPC['productsn']),
            'credit' => trim($_GPC['credit']),
            'maxbuy' => intval($_GPC['maxbuy']),
            'usermaxbuy' => intval($_GPC['usermaxbuy']),
            'hasoption' => intval($_GPC['hasoption']),
            'sales' => intval($_GPC['sales']),
            'share_icon' => trim($_GPC['share_icon']),
            'share_title' => trim($_GPC['share_title']),
            'cash' => intval($_GPC['cash']),
            'status' => 1,
            'extnotice'=>trim($_GPC['extnotice'],','),
            'showlevels' => is_array($_GPC['showlevels']) ? implode(',', $_GPC['showlevels']) : '',
            'buylevels' => is_array($_GPC['buylevels']) ? implode(',', $_GPC['buylevels']) : '',
            'showgroups' => is_array($_GPC['showgroups']) ? implode(',', $_GPC['showgroups']) : '',
            'buygroups' => is_array($_GPC['buygroups']) ? implode(',', $_GPC['buygroups']) : '',
//            'isverify' => intval($_GPC['isverify']),
            'storeids' => trim($_GPC['storeids'],','),
            'noticeopenid' => trim($_GPC['noticeopenid']),
            'noticetype' => is_array($_GPC['noticetype']) ? implode(',', $_GPC['noticetype']) : '',
            'needfollow' => intval($_GPC['needfollow']),
            'followurl' => trim($_GPC['followurl']),
            'followtip' => trim($_GPC['followtip']),
            'deduct' => $_GPC['deduct'],
            'manydeduct' => $_GPC['manydeduct'],
            'deduct2' => $_GPC['deduct2'],
            'virtual' => intval($_GPC['type']) == 3 ? intval($_GPC['virtual']) : 0,
            'ednum' => intval($_GPC['ednum']),
            'edareas' => trim($_GPC['edareas']),
            'edmoney' => trim($_GPC['edmoney']),
            'afterSalesServices' => trim($_GPC['salesServices']),      //售后服务
            'LocalFlag' => trim($_GPC['LocalFlag']),                //当面交易
            'PostFlag' => trim($_GPC['PostFlag']),                      //快递发货
            'shelves' => trim($_GPC['shelves']),                        //上架方式
            'isCheck' => 0,                                             //是否审核
            'startShelves' => strtotime($_GPC['startShelves'])          //上架时间

        );
        if(empty($_GPC['LocalFlag'])){
            $data['LocalFlag'] = 0;
        }
        if(empty($_GPC['PostFlag'])){           //没有勾选
            $data['PostFlag'] = 0;
        }
        	
		if (!empty($_GPC['ischeck'])) {			//保存草稿
        	$data['isCheck'] = $_GPC['ischeck'];
        }

		if (!empty($_GPC['bonusmoney'])) {
			$data['bonusmoney'] = $_GPC['bonusmoney'];
		}
		if (p('supplier')) {
			if ($perm_role == 1) {
				$data['supplier_uid'] = $_W['uid'];
				$data['status'] = 0;
			} else {
				$data['supplier_uid'] = $_GPC['supplier_uid'];
				$data['status'] = $_GPC['status'];
			}
		} else {
			$data['status'] = $_GPC['status'];
		}


		if ($data['noticeopenid'] == '' && empty($id) && $data['supplier_uid']) {
			$pu=p('bonus')->getMerch($data['supplier_uid']);
			$data['noticeopenid']=$pu['openid'];
			$data['noticetype']='1';
		}
		
		//全返
		if (p('return')) {
			$data['isreturn'] = intval($_GPC['isreturn']);
		}
		//购物返
		
		if (p('returnmatter')) {
			$data['returnmatter'] = intval($_GPC['returnmatter']);
		}
		$cateset = m('common')->getSysset('shop');
		$pcates = array();
		$ccates = array();
		$tcates = array();
		if (is_array($_GPC['cates'])) {
			$postcates = $_GPC['cates'];
			foreach ($postcates as $pid) {
				if ($cateset['catlevel'] == 3) {
					$tcate = pdo_fetch('select id ,parentid from ' . tablename('sz_yi_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $pid, ':uniacid' => $_W['uniacid']));
					$ccate = pdo_fetch('select id ,parentid from ' . tablename('sz_yi_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $tcate['parentid'], ':uniacid' => $_W['uniacid']));
					$tcates[] = $tcate['id'];
					$ccates[] = $ccate['id'];
					$pcates[] = $ccate['parentid'];
				} else {
					$ccate = pdo_fetch('select id ,parentid from ' . tablename('sz_yi_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $pid, ':uniacid' => $_W['uniacid']));
					$ccates[] = $ccate['id'];
					$pcates[] = $ccate['parentid'];
				}
			}
		}
		$data['pcates'] = implode(',', $pcates);
		$data['ccates'] = implode(',', $ccates);
		$data['tcates'] = implode(',', $tcates);

		$content = htmlspecialchars_decode($_GPC['content']);
		preg_match_all('/<img.*?src=[\\\'| "](.*?(?:[\\.gif|\\.jpg|\\.png|\\.jpeg]?))[\\\'|"].*?[\\/]?>/', $content, $imgs);
		$images = array();
		if (isset($imgs[1])) {
			foreach ($imgs[1] as $img) {
				$im = array('old' => $img, 'new' => save_media($img));
				$images[] = $im;
			}
		}
		foreach ($images as $img) {
			$content = str_replace($img['old'], $img['new'], $content);
		}
		$data['content'] = $content;


        $special = htmlspecialchars_decode($_GPC['special']);
        preg_match_all('/<img.*?src=[\\\'| "](.*?(?:[\\.gif|\\.jpg|\\.png|\\.jpeg]?))[\\\'|"].*?[\\/]?>/', $special, $pics);
        $images = array();
        if (isset($pics[1])) {
            foreach ($pics[1] as $img) {
                $im = array('old' => $img, 'new' => save_media($img));
                $images[] = $im;
            }
        }
        foreach ($images as $img) {
            $special = str_replace($img['old'], $img['new'], $special);
        }
        $data['special'] = $special;

		if (p('commission')) {
			$cset = p('commission')->getSet();
			if (!empty($cset['level'])) {
				$data['nocommission']      = intval($_GPC['nocommission']);
				$data['hascommission']     = intval($_GPC['hascommission']);
				$data['hidecommission']    = intval($_GPC['hidecommission']);
				$data['commission1_rate']  = $_GPC['commission1_rate'];
				$data['commission2_rate']  = $_GPC['commission2_rate'];
				$data['commission3_rate']  = $_GPC['commission3_rate'];
				$data['commission4_rate']  = $_GPC['commission4_rate'];
				$data['commission5_rate']  = $_GPC['commission5_rate'];
				$data['commission6_rate']  = $_GPC['commission6_rate'];
				$data['commission7_rate']  = $_GPC['commission7_rate'];
				$data['commission8_rate']  = $_GPC['commission8_rate'];
				$data['commission9_rate']  = $_GPC['commission9_rate'];
				$data['commission10_rate'] = $_GPC['commission10_rate'];
				$data['commission11_rate'] = $_GPC['commission11_rate'];
				$data['commission12_rate'] = $_GPC['commission12_rate'];
				$data['commission13_rate'] = $_GPC['commission13_rate'];
				$data['commission14_rate'] = $_GPC['commission14_rate'];
				$data['commission15_rate'] = $_GPC['commission15_rate'];
				
				$data['commission1_pay']  = $_GPC['commission1_pay'];
				$data['commission2_pay']  = $_GPC['commission2_pay'];
				$data['commission3_pay']  = $_GPC['commission3_pay'];
				$data['commission4_pay']  = $_GPC['commission4_pay'];
				$data['commission5_pay']  = $_GPC['commission5_pay'];
				$data['commission6_pay']  = $_GPC['commission6_pay'];
				$data['commission7_pay']  = $_GPC['commission7_pay'];
				$data['commission8_pay']  = $_GPC['commission8_pay'];
				$data['commission9_pay']  = $_GPC['commission9_pay'];
				$data['commission10_pay'] = $_GPC['commission10_pay'];
				$data['commission11_pay'] = $_GPC['commission11_pay'];
				$data['commission12_pay'] = $_GPC['commission12_pay'];
				$data['commission13_pay'] = $_GPC['commission13_pay'];
				$data['commission14_pay'] = $_GPC['commission14_pay'];
				$data['commission15_pay'] = $_GPC['commission15_pay'];
				$data['commission_thumb'] = save_media($_GPC['commission_thumb']);
				$data['commission_level_id'] = intval($_GPC['commission_level_id']);
				$data['commission'] = json_encode($_GPC['commission_level_default']);

				$data['bonus_level_id'] = intval($_GPC['bonus_level_id']);
				$data['isreturnqueue'] = intval($_GPC['isreturnqueue']);

				$data['commission_type'] = $_GPC['commission_type'];
				$data['culate_method'] = !isset($_GPC['culate_method'])||!in_array($_GPC['culate_method'],array(0,1,2,3,4,5,6))?6:$_GPC['culate_method'];
				// $data['culate_method'] = !isset($_GPC['culate_method'])||!in_array($_GPC['culate_method'],array(0,1,2,3,4,5,6))?6:$_GPC['culate_method'];
			}
		}
		if ($diyform_plugin) {
			$data['diyformtype'] = $_GPC['diyformtype'];
			$data['diyformid'] = $_GPC['diyformid'];
			$data['diymode'] = intval($_GPC['diymode']);
		}
		$data['dispatchtype'] = intval($_GPC['dispatchtype']);
		$data['dispatchprice'] = $_GPC['dispatchprice'];
		$data['dispatchid'] = $_GPC['dispatchid'];
		if ($data['total'] === -1) {
			$data['total'] = 0;
			$data['totalcnf'] = 2;
		}
		if (is_array($_GPC['thumbs'])) {
			$thumbs = $_GPC['thumbs'];
			$thumb_url = array();
			foreach ($thumbs as $th) {
				$thumb_url[] = save_media($th);
			}
			$data['thumb_url'] = serialize($thumb_url);
		}

        if (is_array($_GPC['headPic'])) {
            $headPics = $_GPC['headPic'];
            $thumb_url = array();
            foreach ($headPics as $th) {
                $thumb_url[] = save_media($th);
            }
            $data['headPic'] = serialize($thumb_url);
        }

        $data['isgoodsreturn'] = empty($_GPC['isgoodsreturn'])?0:1;

        $logid=pdo_fetchcolumn('select id from '.tablename('sz_yi_goods_log').' where uniacid = :uniacid and goodsid = :goodsid and status = 0',array(':uniacid'=>$_W['uniacid'],':goodsid'=>$id)); 
		$goodslog=[ 
			'uniacid'=>$_W['uniacid'],  
			'uid'=>$data['supplier_uid'],
			'goodsid'=>$_GPC['id'], 
			'sub_time'=>time(),
			'status'=>0 
		]; 

		$data['uptime']=time();
		if (empty($id)) { 
			pdo_insert('sz_yi_goods', $data);  
			$id = pdo_insertid();  
			$goodslog['goodsid']=$id;    
			pdo_insert('sz_yi_goods_log',$goodslog);
			plog('dealmerch.goods.add', "添加易货商品 ID: {$id}");
		} else {
			unset($data['createtime']);
			pdo_update('sz_yi_goods', $data, array('id' => $id));
			if ($logid) { 
				pdo_update('sz_yi_goods_log',$goodslog,array('id'=>$logid));
			}else{  
				pdo_insert('sz_yi_goods_log',$goodslog);
			}
			plog('dealmerch.goods.edit', "编辑商品 ID: {$id}");
		}
		$totalstocks = 0;
		$param_ids = $_POST['param_id'];
		$param_titles = $_POST['param_title'];
		$param_values = $_POST['param_value'];
		$param_displayorders = $_POST['param_displayorder'];
		$len = count($param_ids);
		$paramids = array();
		for ($k = 0; $k < $len; $k++) {
			$param_id = "";
			$get_param_id = $param_ids[$k];
			$a = array('uniacid' => $_W['uniacid'], 'title' => $param_titles[$k], 'value' => $param_values[$k], 'displayorder' => $k, 'goodsid' => $id);
			if (!is_numeric($get_param_id)) {
				pdo_insert('sz_yi_goods_param', $a);
				$param_id = pdo_insertid();
			} else {
				pdo_update('sz_yi_goods_param', $a, array('id' => $get_param_id));
				$param_id = $get_param_id;
			}
			$paramids[] = $param_id;
		}
		if (count($paramids) > 0) {
			pdo_query('delete from ' . tablename('sz_yi_goods_param') . " where goodsid=$id and id not in ( " . implode(',', $paramids) . ')');
		} else {
			pdo_query('delete from ' . tablename('sz_yi_goods_param') . " where goodsid=$id");
		}
		$files = $_FILES;
		$spec_ids = $_POST['spec_id'];
		$spec_titles = $_POST['spec_title'];
		$specids = array();
		$len = count($spec_ids);
		$specids = array();
		$spec_items = array();
		for ($k = 0; $k < $len; $k++) {
			$spec_id = "";
			$get_spec_id = $spec_ids[$k];
			$a = array('uniacid' => $_W['uniacid'], 'goodsid' => $id, 'displayorder' => $k, 'title' => $spec_titles[$get_spec_id]);
			if (is_numeric($get_spec_id)) {
				pdo_update('sz_yi_goods_spec', $a, array('id' => $get_spec_id));
				$spec_id = $get_spec_id;
			} else {
				pdo_insert('sz_yi_goods_spec', $a);
				$spec_id = pdo_insertid();
			}
			$spec_item_ids = $_POST['spec_item_id_' . $get_spec_id];
			$spec_item_titles = $_POST['spec_item_title_' . $get_spec_id];
			$spec_item_shows = $_POST['spec_item_show_' . $get_spec_id];
			$spec_item_thumbs = $_POST['spec_item_thumb_' . $get_spec_id];
			$spec_item_oldthumbs = $_POST['spec_item_oldthumb_' . $get_spec_id];
			$spec_item_virtuals = $_POST['spec_item_virtual_' . $get_spec_id];
			$itemlen = count($spec_item_ids);
			$headPics=
			$itemids = array();
			for ($n = 0; $n < $itemlen; $n++) {
				$item_id = "";
				$get_item_id = $spec_item_ids[$n];
				$d = array('uniacid' => $_W['uniacid'], 'specid' => $spec_id, 'displayorder' => $n, 'title' => $spec_item_titles[$n], 'show' => $spec_item_shows[$n], 'thumb' => save_media($spec_item_thumbs[$n]), 'virtual' => $data['type'] == 3 ? $spec_item_virtuals[$n] : 0);
				$f = 'spec_item_thumb_' . $get_item_id;
				if (is_numeric($get_item_id)) {
					pdo_update('sz_yi_goods_spec_item', $d, array('id' => $get_item_id));
					$item_id = $get_item_id;
				} else {
					pdo_insert('sz_yi_goods_spec_item', $d);
					$item_id = pdo_insertid();
				}
				$itemids[] = $item_id;
				$d['get_id'] = $get_item_id;
				$d['id'] = $item_id;
				$spec_items[] = $d;
			}
			if (count($itemids) > 0) {
				pdo_query('delete from ' . tablename('sz_yi_goods_spec_item') . " where uniacid={$_W['uniacid']} and specid=$spec_id and id not in (" . implode(',', $itemids) . ')');
			} else {
				pdo_query('delete from ' . tablename('sz_yi_goods_spec_item') . " where uniacid={$_W['uniacid']} and specid=$spec_id");
			}
			pdo_update('sz_yi_goods_spec', array('content' => serialize($itemids)), array('id' => $spec_id));
			$specids[] = $spec_id;
		}
		if (count($specids) > 0) {
			pdo_query('delete from ' . tablename('sz_yi_goods_spec') . " where uniacid={$_W['uniacid']} and goodsid=$id and id not in (" . implode(',', $specids) . ')');
		} else {
			pdo_query('delete from ' . tablename('sz_yi_goods_spec') . " where uniacid={$_W['uniacid']} and goodsid=$id");
		}
		$option_idss = $_POST['option_ids'];
		$option_productprices = $_POST['option_productprice'];
		$option_marketprices = $_POST['option_marketprice'];
		$option_costprices = $_POST['option_costprice'];
		$option_stocks = $_POST['option_stock'];
		$option_weights = $_POST['option_weight'];
		$option_goodssns = $_POST['option_goodssn'];
		$option_productssns = $_POST['option_productsn'];
		$len = count($option_idss);
		$optionids = array();
		for ($k = 0; $k < $len; $k++) {
			$option_id = "";
			$ids = $option_idss[$k];
			$get_option_id = $_GPC['option_id_' . $ids][0];
			$idsarr = explode('_', $ids);
			$newids = array();
			foreach ($idsarr as $key => $ida) {
				foreach ($spec_items as $it) { 
					if ($it['get_id'] == $ida) {
						$newids[] = $it['id'];
						break;
					} 
				}
			}
			$newids = implode('_', $newids);
			$a = array('uniacid' => $_W['uniacid'], 'title' => $_GPC['option_title_' . $ids][0], 'productprice' => $_GPC['option_productprice_' . $ids][0], 'costprice' => $_GPC['option_marketprice_' . $ids][0], 'marketprice' => $_GPC['option_marketprice_' . $ids][0], 'stock' => $_GPC['option_stock_' . $ids][0], 'weight' => $_GPC['option_weight_' . $ids][0], 'goodssn' => $_GPC['option_goodssn_' . $ids][0], 'productsn' => $_GPC['option_productsn_' . $ids][0], 'goodsid' => $id, 'specs' => $newids, 'virtual' => $data['type'] == 3 ? $_GPC['option_virtual_' . $ids][0] : 0);
			$totalstocks += $a['stock'];
			if (empty($get_option_id)) {
				pdo_insert('sz_yi_goods_option', $a);
				$option_id = pdo_insertid();
			} else {
				pdo_update('sz_yi_goods_option', $a, array('id' => $get_option_id)); 
				$option_id = $get_option_id;
			}
			$optionids[] = $option_id;
		}
		if (count($optionids) > 0) {
			pdo_query('delete from ' . tablename('sz_yi_goods_option') . " where goodsid=$id and id not in ( " . implode(',', $optionids) . ')');
		} else {
			pdo_query('delete from ' . tablename('sz_yi_goods_option') . " where goodsid=$id");
		}
		if ($data['type'] == 3 && $pv) {
			$pv->updateGoodsStock($id);
		} else {
			if (($totalstocks > 0) && ($data['totalcnf'] != 2)) {
				pdo_update('sz_yi_goods', array('total' => $totalstocks), array('id' => $id));
			}
		}
		message('商品更新成功！', $this->createPluginWebUrl('dealmerch/goods', array('op' => 'post', 'id' => $id)), 'success');
	}
	if (p('commission')) {
		$com_set = p('commission')->getSet();
	}

	if (p('bonus')) {
		$bonus_set = p('bonus')->getSet();
	}

	if ($pv) {
		$virtual_types = pdo_fetchall('select * from ' . tablename('sz_yi_virtual_type') . ' where uniacid=:uniacid order by id asc', array(':uniacid' => $_W['uniacid']));
	}
	$levels = m('member')->getLevels();
	$details = pdo_fetchall('select detail_logo,detail_shopname,detail_btntext1, detail_btnurl1 ,detail_btntext2,detail_btnurl2,detail_totaltitle from ' . tablename('sz_yi_goods') . ' where uniacid=:uniacid and detail_shopname<>\'\'', array(':uniacid' => $_W['uniacid']));
	foreach ($details as &$d) {
		$d['detail_logo_url'] = tomedia($d['detail_logo']);
	}
	unset($d);
	$areas = m('cache')->getArray('areas', 'global');
	if ($diyform_plugin) {
		$form_list = $diyform_plugin->getDiyformList();
	}
	if (!is_array($areas)) {
		require_once SZ_YI_INC . 'json/xml2json.php';
		$file = IA_ROOT . '/addons/sz_yi/static/js/dist/area/Area.xml';
		$content = file_get_contents($file);
		$json = xml2json::transformXmlStringToJson($content);
		$areas = json_decode($json, true);
		m('cache')->set('areas', $areas, 'global');
	}
	if(empty($_GPC['id'])){
        $params=array(
            ['title'=>'产品品牌'],
            ['title'=>'原产地'],
            ['title'=>'有效期'],
            ['title'=>'包装规格'],
            ['title'=>'重量'],
            ['title'=>'外观'],
            ['title'=>'主要用途']	 	
        );	 
    }
    
}elseif ($operation == 'display') {
	ca('dealmerch.goods');
	if (!empty($_GPC['displayorder'])) {
		ca('dealmerch.goods.edit');
		foreach ($_GPC['displayorder'] as $id => $displayorder) {
			pdo_update('sz_yi_goods', array('displayorder' => $displayorder), array('id' => $id));
		}
		plog('dealmerch.dealgoods.edit', '批量修改商品排序');
		message('商品排序更新成功！', $this->createPluginWebUrl('dealmerch/goods', array('op' => 'display')), 'success');
	}
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$condition = ' WHERE `uniacid` = :uniacid AND `deleted` = :deleted and type = 8 ';
	$params = array(':uniacid' => $_W['uniacid'], ':deleted' => '0'); 
	if (!empty($_GPC['title'])) { 
		$_GPC['title'] = trim($_GPC['title']);
		$condition .= ' AND `title` LIKE :title ';
		$params[':title'] = '%' . trim($_GPC['title']) . '%';
	}
	
	if (!empty($_GPC['uid'])) {
		$condition .= ' AND `supplier_uid` = :uid ';
		$params[':uid'] = intval($_GPC['uid']); 	 	 	 	
		$selmerch=p('bonus')->getmerch($_GPC['uid']);	 	 
	}	 	 

	if (!empty($_GPC['goodssn'])) {
		$condition .= ' AND `goodssn` = :goodssn ';
		$params[':goodssn'] = intval($_GPC['goodssn']); 
	}

    if (!empty($_GPC['category']['thirdid'])) {
        $condition .= ' AND `tcate` = :tcate';
        $params[':tcate'] = intval($_GPC['category']['thirdid']);

    }
    if (!empty($_GPC['category']['childid'])) {
        $condition .= ' AND `ccate` = :ccate';
        $params[':ccate'] = intval($_GPC['category']['childid']);
    }
    if (!empty($_GPC['category']['parentid'])) {
        $condition .= ' AND `pcate` = :pcate';
        $params[':pcate'] = intval($_GPC['category']['parentid']);

    }

	if (!empty($_GPC['status'])) {   
		if ($_GPC['status'] == 1) {	//出售中   
			$condition.=' and status = 1 and isCheck = 1 '; 
		}else if ($_GPC['status'] == 2){  //待上架    
			$condition.=' and shelves = 2 and startshelves > '.time().' '; 
		}else if ($_GPC['status'] == 3){	//审核中   
			$condition.=' and isCheck = 0 '; 
		}else if ($_GPC['status'] == 4){		//审核失败 
			$condition.=' and isCheck = 2 '; 
		}else if ($_GPC['status'] == 5){		//已下架 
			$condition.=' and isCheck = 0 and isCheck = 1 '; 
		}else if ($_GPC['status'] == 6){		//草稿 
			$condition.=' and isCheck = 3 '; 
		}  
	} 

	if (p('supplier')) {

		$suproleid = pdo_fetchcolumn('select id from' . tablename('sz_yi_perm_role') . ' where status1 = 1 and uniacid ='.$_W['uniacid']);
		$userroleid = pdo_fetchcolumn('select roleid from ' . tablename('sz_yi_perm_user') . ' where uid=:uid and uniacid=:uniacid', array(':uid' => $_W['uid'], ':uniacid' => $_W['uniacid']));
		if ((!empty($userroleid)) && ($userroleid == $suproleid)) {
			$sql = 'SELECT * FROM ' . tablename('sz_yi_goods') . $condition . ' and supplier_uid=' . $_W['uid'] . " ORDER BY uptime DESC, `displayorder` DESC,\r\n\t\t\t\t\t`id` DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$sqls = 'SELECT COUNT(*) FROM ' . tablename('sz_yi_goods') . $condition . ' and supplier_uid=' . $_W['uid'];
			$total = pdo_fetchcolumn($sqls, $params);
		} else {
			$sql = 'SELECT * FROM ' . tablename('sz_yi_goods') . $condition . " ORDER BY `uptime` DESC, `displayorder` DESC,`id` DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$sqls = 'SELECT COUNT(*) FROM ' . tablename('sz_yi_goods') . $condition;	 	 
			$total = pdo_fetchcolumn($sqls, $params);	 	
		}	 	 
	} else {

		$sql = 'SELECT * FROM ' . tablename('sz_yi_goods') . $condition . " ORDER BY `uptime` DESC, `displayorder` DESC,\r\n\t\t\t\t\t`id` DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
		$sqls = 'SELECT COUNT(*) FROM ' . tablename('sz_yi_goods') . $condition;
		$total = pdo_fetchcolumn($sqls, $params);
	}
//    print_r($params);exit;
	$list = pdo_fetchall($sql, $params);

	$pager = pagination($total, $pindex, $psize);
} elseif ($operation == 'delete') {
	ca('dealmerch.goods.delete');
	$id = intval($_GPC['id']);
	$row = pdo_fetch('SELECT id, title, thumb FROM ' . tablename('sz_yi_goods') . ' WHERE id = :id', array(':id' => $id));
	if (empty($row)) {
		message('抱歉，商品不存在或是已经被删除！');
	}
	pdo_update('sz_yi_goods', array('deleted' => 1), array('id' => $id));
	plog('dealmerch.dealgoods.delete', "删除商品 ID: {$id} 标题: {$row['title']} ");
	message('删除成功！', referer(), 'success');
} elseif ($operation == 'setgoodsproperty') {
	ca('dealmerch.goods.edit');	 	 	 
	$id = intval($_GPC['id']);
	$type = $_GPC['type'];	 	 
	$data = intval($_GPC['data']);	 
	if (in_array($type, array('new', 'hot', 'recommand', 'discount', 'time', 'sendfree', 'nodiscount'))) {
		$data = ($data == 1 ? '0' : '1');
		pdo_update('sz_yi_goods', array('is' . $type => $data), array('id' => $id, 'uniacid' => $_W['uniacid']));
		if ($type == 'new') {
			$typestr = '新品';
		} else if ($type == 'hot') {
			$typestr = '热卖';
		} else if ($type == 'recommand') {
			$typestr = '推荐';
		} else if ($type == 'discount') {
			$typestr = '促销';
		} else if ($type == 'time') {
			$typestr = '限时卖';
		} else if ($type == 'sendfree') {
			$typestr = '包邮';
		} else if ($type == 'nodiscount') {
			$typestr = '不参与折扣状态';
		}
		plog('dealmerch.dealgoods.edit', "修改商品{$typestr}状态   ID: {$id}");
		die(json_encode(array('result' => 1, 'data' => $data)));
	}
	if (in_array($type, array('status'))) {
		$data = ($data == 1 ? '0' : '1');
		pdo_update('sz_yi_goods', array($type => $data), array('id' => $id, 'uniacid' => $_W['uniacid']));
		plog('dealmerch.dealgoods.edit', "修改商品上下架状态   ID: {$id}");
		die(json_encode(array('result' => 1, 'data' => $data)));
	}
	if (in_array($type, array('type'))) {
		$data = ($data == 1 ? '2' : '1');
		pdo_update('sz_yi_goods', array($type => $data), array('id' => $id, 'uniacid' => $_W['uniacid']));
		plog('dealmerch.dealgoods.edit', "修改商品类型   ID: {$id}");
		die(json_encode(array('result' => 1, 'data' => $data)));
	}
	die(json_encode(array('result' => 0)));
}

load()->func('tpl'); 	 	 	 	 		 	 	 
include $this->template('goods'); 
