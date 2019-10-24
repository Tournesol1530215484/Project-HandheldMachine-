<?php
	if (!defined('IN_IA')){
	    exit('Access Denied');
	}
	global $_W, $_GPC;

	$op = empty($_GPC['op']) ? 'display' : trim($_GPC['op']);

	if ($op == 'display') {

		$condition  = " where uniacid = :uniacid";
		$params = array(':uniacid'=> $_W['uniacid']);

		//时间
		if (!empty($_GPC['starttime']) || !empty($_GPC['endtime'])) {
			$condition .= " and dealtime between :starttime and :endtime";
			$params['starttime'] = time($_GPC['starttime']);
			$params['endtime'] = time($_GPC['endtime']);
		}
		//名称
		if (!empty($_GPC['goodsname'])) {
			$condition .= " and goodsname = :goodsname";
			$params['goodsname'] = trim($_GPC['goodsname']);
		}
		//操作类型
		if (!empty($_GPC['type'])) {
			$condition .= " and type = :type";
			$params['type'] = intval($_GPC['type']);
		}
		//商品编号
		if (!empty($_GPC['goodsid'])) {
			$condition .= " and goodsid = :goodsid";
			$params['goodsid'] = intval($_GPC['goodsid']);
		}
		//商品类型
		if (!empty($_GPC['goodstype'])) {
			$condition .= " and goodstype = :goodstype";
			$params['goodstype'] = trim($_GPC['goodstype']);
		}
		//规格型号
		if (!empty($_GPC['goodsmodel'])) {
			$condition .= " and optionid = :goodsmodel";
			$params['goodsmodel'] = intval($_GPC['goodsmodel']);
		}
		// var_dump($condition, $params);die; 

		$pindex = max(1, $_GPC['page']);

		$psize = 3;

		$total = pdo_fetchcolumn("select count(*) from ".tablename('sz_yi_stock_log') . $condition ." order by id asc limit ".($pindex-1)*$psize.','.$psize , $params);
			
		$pager = pagination($total, $pindex, $psize);
		
		$info = pdo_fetchall("select * from ".tablename('sz_yi_stock_log') . $condition ." order by id asc limit ".($pindex - 1 )*$psize . ',' . $psize , $params);

		foreach ($info as $k => $v) {

			$optiontitle = pdo_fetch('select title, id from '.tablename('sz_yi_goods_option').' where id = :id', array(':id'=>$_v['optionid']));
		// var_dump($optiontitle);die;

			$info[$k]['optiontitle'] = $optiontitle['title'] ;

			switch ($v['type']) {
				case 1 :  $v['type']='初始上架';	break;
				case 2 :  $v['type']='库存调整';	break;
				case 3 :  $v['type']='用户付款';	break;
				case 4 :  $v['type']='取消交易';	break;
				case 5 :  $v['type']='商品下架';	break;
				case 6 :  $v['type']='定向易货冻结';	break;
				case 7 :  $v['type']='定向易货取消冻结';	break;
				case 8 :  $v['type']='定向易货出库';	break;
				default:  $v['type']='全部'; 	break;
			}

		}

	} else if ($op == 'post') {

		$id = intval($_GPC['id']);
		// echo '<pre>';var_dump($_GPC);die;
		$optionid = pdo_fetch("select id from ".tablename('sz_yi_goods_option')." where goodsid = :goodsid ", array(':goodsid'=> intval($_GPC['goodsid'])) );
		// var_dump($optionid);die;
		$data = array(
			'type' => intval($_GPC['type']),
			'goodsid' => intval($_GPC['goodsid']),
			'goodssn' => trim($_GPC['goodssn']),
			'optionid' => $optionid['id'],
			'goodsname' => trim($_GPC['goodsname']),
			'stock' => intval($_GPC['stock']),
			'dealtime' => time($_GPC['dealtime']),
			'uniacid' => $_W['uniacid']
		);
		// var_dump($data);die;
		if (empty($id)) {
			pdo_insert('sz_yi_stock_log', $data);
			$insid = pdo_insertid();
			if (!$insid) {
				message('添加失败', $this->createPluginWebUrl('dealmerch/dealmerch_stockchange'), 'fail');
			} else {
				message('添加成功', $this->createPluginWebUrl('dealmerch/dealmerch_stockchange'), 'success');
			}
		} else {
			$res = pdo_update('sz_yi_stock_log', $data, array('id' => $id, 'uniacid'=>$_W['uniacid']) );
			if (!$res) {
				message('修改失败', $this->createPluginWebUrl('dealmerch/dealmerch_stockchange'), 'fail');
				
			} else {
				message('修改成功', $this->createPluginWebUrl('dealmerch/dealmerch_stockchange'), 'success');

			}
		}
	} else if ($op == 'get') {
		//获取商品属性id 
		// $goodoptionid = pdo_fetch("select option.id as opid , option.title as optitle, option.goodsid, goods.id as gid , goods.goodssn, goods.total  from ".tablename('sz_yi_goods_option')." as option right join ".tablename('sz_yi_goods')." as goods on gid = opid where uniacid = :uniacid and gid = :gid or opid = :opid ", array(':opid'=>$_GPC['opid'], 'gid'=>intval($_GPC['id'] ), ':uniacid'=>intval($_W['uniacid']) ));
		//获取商品id
		$goodsinfo = pdo_fetch('select goods.id, goods.title, goods.unit, goods.goodssn, goods.total, goods.totalcnf, goods.type, goods.status , option.id as opid, option.title as optitle , option.stock from '.tablename('sz_yi_goods').' as goods left join '.tablename('sz_yi_goods_option').'as option on goods.id = option.goodsid where goods.id = :id and uniacid = :uniacid', array(':id'=>$_GPC['id'], ':uniacid'=>$_W['uniacid']));
		
		show_json($goodsinfo);

	} else if ($op == 'delete') {
		
		$id = intval($_GPC['id']);

		if (empty($id)) {
			message('请选择数据', $this->createPluginWebUrl('dealmerch/dealmerch_stockchange'));
		}
		
		$res = pdo_delete('sz_yi_stock_log', array('id'=>$id));

		if (!$res) {
			message('删除失败', $this->createPluginWebUrl('dealmerch/dealmerch_stockchange'), 'fail');
		} else {
			message('删除成功', $this->createPluginWebUrl('dealmerch/dealmerch_stockchange'), 'success');
		}
	}

	load()->func('tpl');

	include $this->template('dealmerch_stockchange');