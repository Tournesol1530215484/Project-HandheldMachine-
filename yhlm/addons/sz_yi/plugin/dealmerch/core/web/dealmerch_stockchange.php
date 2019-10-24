<?php
	if (!defined('IN_IA')){
	    exit('Access Denied');
	}
	global $_W, $_GPC;

	$op = empty($_GPC['op']) ? 'display' : trim($_GPC['op']);
	if ($op == 'display') {

		$condition  = " where uniacid = :uniacid";
		$params = array(':uniacid'=> $_W['uniacid']);
		 
//		$starttime = time() - (60 * 60 * 24 * 30 * 12 * 18 );
//		$endtime = time();
		//时间
//		if ($_GPC['searchtime'] == 1) {
//			if (!empty($_GPC['starttime']) && !empty($_GPC['endtime'])) {
//				$condition .= " and dealtime > :starttime and dealtime < :endtime";
//				$params[':starttime'] = $_GPC['datetime']['starttime'] ;
//				$params[':endtime'] = $_GPC['datetime']['endtime'] ;
//			}
//		}
        if ($_GPC['searchtime'] == 1) {
            if (!empty($_GPC['datetime'])){
                $starttime=strtotime($_GPC['datetime']['start']);
                $endtime=strtotime($_GPC['datetime']['end']);
                $condition .= " and dealtime > :starttime and dealtime < :endtime ";
                $params[':starttime'] = $starttime;
                $params[':endtime'] = $endtime;
            }
        } 
		//名称
		if (!empty($_GPC['goodsname'])) {
			$condition .= " and goodsname like :goodsname";
			$params[':goodsname'] = "%{$_GPC['goodsname']}%";
		} 
		//操作类型
		if (!empty($_GPC['type'])) {
			$condition .= " and type = :type";
			$params[':type'] = intval($_GPC['type']);
		}
		//商品编号
		if (!empty($_GPC['goodssn'])) {
			$condition .= " and goodssn like :goodssn";
			$params[':goodssn'] = "%{$_GPC['goodssn']}%";
		}
		//规格型号
		if (!empty($_GPC['optionid'])) {
			$condition .= " and optionid like :optionid";
			$params[':optionid'] = "%{$_GPC['optionid']}%";
		}
		// print_r($_GPC);
		
		//商品类型
		// if (!empty($_GPC['goodstype'])) {
		// 	$condition .= " and goodstype = :goodstype";
		// 	$params['goodstype'] = trim($_GPC['goodstype']);
		// }

		$pindex = max(1, $_GPC['page']);

		$psize = 20;

		$total = pdo_fetchcolumn("select count(*) from ".tablename('sz_yi_stock_log') . $condition ." order by id asc ", $params);
		$pager = pagination($total, $pindex, $psize); 
		
		$info = pdo_fetchall("select *  from ".tablename('sz_yi_stock_log').$condition." order by id asc limit ".($pindex - 1 )*$psize . ',' . $psize , $params);
		
		foreach ($info as $k => $v) {

			switch ($v['type']) {
				case 1 :  $v['type']='初始上架';	continue;
				case 2 :  $v['type']='库存调整';	continue;
				case 3 :  $v['type']='用户付款';	continue;
				case 4 :  $v['type']='取消交易';	continue;
				case 5 :  $v['type']='商品下架';	continue;
				case 6 :  $v['type']='定向易货冻结';	continue;
				case 7 :  $v['type']='定向易货取消冻结';	continue;
				case 8 :  $v['type']='定向易货出库';	continue;
				default:  $v['type']='全部'; 	continue;
			}
			$optiontitle = pdo_fetch('select title, id from '.tablename('sz_yi_goods_option').' where id = :id', array(':id'=>$v['optionid']));
			
			$unit = pdo_fetch('select unit, id from '.tablename('sz_yi_goods').' where id = :id', array(':id'=>$v['goodsid']));
		
			$info[$k]['optiontitle'] = $optiontitle['title'] ;
			
			$info[$k]['stock'] = $v['stock'].$unit['unit'] ;

		}

	} else if ($op == 'post') {

		$id = intval($_GPC['id']);

		$goodsid = intval($_GPC['goodsid2']);
	
		$data = array(
			'type' => intval($_GPC['type']),
			'goodsid' => $goodsid,
			'goodssn' => trim($_GPC['goodssn']),
			'optionid' => intval($_GPC['optionid']),
			'goodsname' => trim($_GPC['goodsname']),
			'stock' => intval($_GPC['stock']),
			'dealtime' => time($_GPC['dealtime']),
			'uniacid' => $_W['uniacid']
		);
		
		if ($_GPC['status']== 'add') {
			pdo_insert('sz_yi_stock_log', $data);
			
			$insid = pdo_insertid();
				
			if (!$insid) {
				message('添加失败', $this->createPluginWebUrl('dealmerch/dealmerch_stockchange'), 'fail');
			} else {
				message('添加成功', $this->createPluginWebUrl('dealmerch/dealmerch_stockchange'), 'success');
			}
		} else if ($_GPC['status']== 'edit') {

			$res = pdo_update('sz_yi_stock_log', $data, array('id' => $id ) );

			if (!$res) {
				message('修改失败', $this->createPluginWebUrl('dealmerch/dealmerch_stockchange'), 'fail');
				
			} else {
				message('修改成功', $this->createPluginWebUrl('dealmerch/dealmerch_stockchange'), 'success');
			}
		}
	
	} else if ($op == 'get') {
        $goodsid = intval($_GPC['id']);
        if (empty($goodsid)){
            show_json(0,'非法参数!');
        }
        $option=pdo_fetchall('select og.*,g.title as goodsname from '.tablename('sz_yi_goods_option').' og left join '.tablename('sz_yi_goods').' g on g.id=og.goodsid where og.uniacid = :uniacid and og.goodsid= :goodsid ',array(':uniacid'=>$_W['uniacid'],':goodsid'=>$goodsid));

        if ($option){
            show_json(1, $option);
        }else{
            show_json(0,'该商品暂无规格');
        }

	} else if ($op == 'delete') {

        $optionid=intval($_GPC['optionid']);
        $stock=intval($_GPC['num']);
        if ($optionid <= 0 || $stock <= 0){
            show_json(0,'非法参数!');
        }

        $optioninfo=pdo_fetch('select * from '.tablename('sz_yi_goods_option').' where id = :id and uniacid = :uniacid ' ,array(':id'=>$optionid,':uniacid'=>$_W['uniacid']));
        $goodsinfo=pdo_fetch('select id,goodssn,title from '.tablename('sz_yi_goods').' where uniacid = :uniacid and id=:id',array(':uniacid'=>$_W['uniacid'],':id'=>$optioninfo['goodsid']));

        $data=array(
            'uniacid'=>$_W['uniacid'],
            'type'   =>2,
            'goodssn'=>$goodsinfo['goodssn'],
            'goodsid'=>$goodsinfo['id'],
            'optionid' => $optionid,
            'goodsname'=>$goodsinfo['title'],
            'dealtime' => time(),
        );

        $stock=intval($stock);
        $optioninfo['stock']=intval($optioninfo['stock']);
        $string='';
        if ( $stock > $optioninfo['stock']){
            $data['stock'] =$stock - $optioninfo['stock'];
            $string='+';
        }else if ($stock < $optioninfo['stock']){
            $data['stock'] =$optioninfo['stock'] - $stock;
            $string='-';
        }

        $data['stock'] = "{$string}{$data['stock']}";

        $sure=pdo_update('sz_yi_goods_option',array('stock'=>$stock),array('id'=>$optionid));
        if (!empty($sure)) {
            $sure=pdo_insert('sz_yi_stock_log',$data);                //如果修改库存suucess
            if (!empty($sure)){
                show_json(1,'库存修改成功');
            }
            show_json(0,'库存修改失败!!!');
        }
        show_json(0,'库存修改失败!!!');
	}

	load()->func('tpl');

	include $this->template('dealmerch_stockchange');