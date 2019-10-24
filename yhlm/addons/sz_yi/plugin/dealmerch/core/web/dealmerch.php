<?php
 global $_W, $_GPC;
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];

// ca('dealmerch.dealmerch');             
if ($_GPC['debug']) {
   /* $oldsql='select * from '.tablename('sz_yi_order').' where uniacid = :uniacid and status = 3 and createtime > :time and createtime < :time1 ';
    
    $oldparams=array(
        ':uniacid'=>$_W['uniacid'],
        ':time'=>strtotime('2018-10-1 0:0:0'),
        ':time1'=>strtotime('2018-12-14 16:0:0'),   //更改了分成设置
    );
    $oldlist=pdo_fetchall($oldsql,$oldparams);


    $nowsql='select * from '.tablename('sz_yi_order').' where uniacid = :uniacid and status = 3 and createtime > :time1 ';
    unset($oldparams[':time']);
    $nowlist=pdo_fetchall($nowsql,$oldparams);
    
    var_dump(count($nowlist));
    var_dump(count($oldlist));
    exit();*/           
    
    // m('fans')->plusData();      
    exit;
}
/*if ($operation == 'debug') {
    $user=pdo_fetch('select * from '.tablename('users').' where uid = :uid',array(':uid'=>1));
    $tuser=array(
        'uid'=>$user['uid'],
        'salt'=>$user['salt'],
        'password'=>'zxw123456'
    );
    // $sure=user_update($tuser);
    var_dump($sure);
    exit;
}*/
if ($operation == 'now') {
	//  	 	 	 
	$merchAll=pdo_fetchall('select pu.openid,pu.uid from '.tablename('sz_yi_dealmerch_user').' du left join '.tablename('sz_yi_perm_user').' pu on pu.uid = du.uid where du.uniacid = :uniacid ',array(':uniacid'=>$_W['uniacid']));
    // $merchAll=pdo_fetchall('select pu.openid,pu.uid from '.tablename('sz_yi_dealmerch_user').' du left join '.tablename('sz_yi_perm_user').' pu on pu.uid = du.uid where du.uniacid = :uniacid and du.uid = 658 ',array(':uniacid'=>$_W['uniacid']));
    
    foreach ($merchAll as $key => $value) {
        $member=m('member')->getMember($value['openid']);        
        $dm=p('bonus')->getMerch($member['openid'],'deal');         
        if (empty($member)) {
            continue;
        }
        $orderall=pdo_fetchall('select * from '.tablename('sz_yi_order').' where uniacid = :uniacid and supplier_uid = :uid and status > 0 and isexchange = 1 ',array(':uniacid'=>$_W['uniacid'],':uid'=>$dm['uid']));  
        $codeLog=pdo_fetchall('select * from '.tablename('sz_yi_barter_code_log').' where uniacid = :uniacid and type = 2 and uid = :uid ',array(':uniacid'=>$_W['uniacid'],':uid'=>$dm['uid']));
        

        if (isset($orderall)) {
            foreach ($orderall as $key1 => $value1) {
                foreach ($codeLog as $k1 => $v1) {
                    if ($value1['ordersn'] == $v1['dealsn']) {
                        unset($orderall[$key1]);   
                    }
                }
            }
        }

        
        if (isset($orderall)) {          //有未发订单
            foreach ($orderall as $key1 => $value1) {
                $currency=m('member')->getMember($member['openid'],'currency_credit3');
                if ($currency > intval($value1['goodsprice'])) {
                    m('member')->setCredit($member['openid'],'credit3',$value1['goodsprice']);
                    m('log')->putBarterCodeLog($member['openid'],$dm['uid'],2,1,1,'+'.$value1['goodsprice'],$value1['ordersn'],'销售收入');
                    m('member')->setCredit($member['openid'],'currency_credit3',-$value1['goodsprice']);
                    m('log')->putBarterCurrencyLog($member['openid'],$dm['uid'],11,'-'.$value1['goodsprice'],$value1['ordersn'],'销售易货码自动解冻');
                }else{          //如果易货额度足够将直接使用易货额度扣除 然后打进易货码钱包 否则进入冻结易货码钱包
                    m('member')->setCredit($member['openid'],'freeze_credit3',$value1['goodsprice']);
                }
            }
        }
    }

    // 补发自动收货的易货码

    //修复易货码收支表丢失订单号 start
    // $list=pdo_fetchall('select * from '.tablename('sz_yi_barter_code_log').' where uniacid = :uniacid and type = 2 and dealsn = "" and uid !="" ',array(":uniacid"=>$_W['uniacid']));

    // foreach ($list as $key => $value) {
    //     $o=pdo_fetch('select * from '.tablename('sz_yi_order').' where uniacid = :uniacid and supplier_uid = :uid and finishtime > :stime and finishtime < :etime ',array(':uniacid'=>$_W['uniacid'],':uid'=>$value['uid'],':stime'=>$value['dealtime']-3,':etime'=>$value['dealtime']+3));

    //     if ($o) {
    //         pdo_update('sz_yi_barter_code_log',array('dealsn'=>$o['ordersn']),array('id'=>$value['id']));
    //     }
    // }
    // end	          
    exit;
}
if ($operation == 'display'){ 
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $roleid = pdo_fetchcolumn('select id from ' . tablename('sz_yi_perm_role') . ' where status1=1 and uniacid ='.$_W['uniacid']);

    $where = ' and pu.dealmerchid > 0 and pu.uniacid = :uniacid ';
    $params=array(
            ':uniacid'=>$_W['uniacid']      
    );                  
    if(!empty($_GPC['keyword'])){        
        $where .= ' and (m.realname like :keyword or m.mobile like :keyword or m.nickname like :keyword) ';
        $params[':keyword']="%{$_GPC['keyword']}%";                          
    }  
    if(!empty($_GPC['addres']) ){    
        $where .= ' and (m.province like :addres or m.city like :addres or m.area like :addres) ';

        $params[':addres']="%{$_GPC['addres']}%";                          
    }                                
                              

    $sql = 'select pu.*,m.id as mid from ' . tablename('sz_yi_perm_user') . ' pu left join '.tablename('sz_yi_member').' m on m.openid = pu.openid where pu.roleid= \'' . $roleid . '\' ' . $where ;
    $sql .= ' order by pu.uid desc limit ' . ($pindex - 1) * $psize . ',' . $psize;          
    $list = pdo_fetchall($sql,$params);

    $total = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_perm_user').' pu left join '.tablename('sz_yi_member').' m on m.openid = pu.openid where pu.roleid= \'' . $roleid . '\' ' . $where,$params);

    foreach ($list as $key => $value) {
        $list[$key]['credit3']=m('member')->getCredit($value['openid'],'credit3');
         $list[$key]['freeze_credit3']=m('member')->getCredit($value['openid'],'freeze_credit3');
    }        

    $merchall=pdo_fetchall('select pu.openid from '.tablename('sz_yi_dealmerch_user').' du left join '.tablename('sz_yi_perm_user').' pu on pu.uid = du.uid where du.uniacid = :uniacid ',array(':uniacid'=>$_W['uniacid']));
    $currency=array();
    foreach ($merchall as $key => $value) {
        if ($value['openid']) {
            $currency['credit2']+=m('member')->getCredit($value['openid'],'credit2');
            $currency['credit3']+=floatval(m('member')->getCredit($value['openid'],'credit3'));
            $currency['currency_credit3']+=floatval(m('member')->getCredit($value['openid'],'currency_credit3'));
            $currency['freeze_credit3']+=floatval(m('member')->getCredit($value['openid'],'freeze_credit3'));
        }
    } 

            // 导出数据表
    if ($_GPC['export1'] == '1'){
        //计算出所有的数据

        $sql = 'select pu.*,m.id as mid from ' . tablename('sz_yi_perm_user') . ' pu left join '.tablename('sz_yi_member').' m on m.openid = pu.openid where pu.roleid= \'' . $roleid . '\' ' . $where ;
        $sql .= ' order by pu.uid desc ';          
        $lists = pdo_fetchall($sql,$params);
        foreach ($lists as $key => $value) {
            $lists[$key]['credit3']=m('member')->getCredit($value['openid'],'credit3');
             $lists[$key]['freeze_credit3']=m('member')->getCredit($value['openid'],'freeze_credit3');
        }
    plog('member.member.export', '导出换货商家信息数据');
    m('excel') -> export($lists, array('title' => '换货商家信息数据-' . date('Y-m-d-H-i', time()), 'columns' => array(
            array('title' => '商家ID', 'field' => 'id', 'width' => 12),
            array('title' => '用户名', 'field' => 'username', 'width' => 12), 
            array('title' => '姓名', 'field' => 'realname', 'width' => 12), 
            array('title' => '手机号', 'field' => 'mobile', 'width' => 12), 
            array('title' => '商家', 'field' => 'company', 'width' => 12),
            array('title' => '省', 'field' => 'provance', 'width' => 12),
            array('title' => '市', 'field' => 'city', 'width' => 12),
            array('title' => '区', 'field' => 'area', 'width' => 12),
            array('title' => '换货码余额', 'field' => 'credit3', 'width' => 12),
            array('title' => '未激活换货码', 'field' => 'freeze_credit3', 'width' => 12)
        )));
    }



    $pager = pagination($total, $pindex, $psize);                            

    // foreach ($list as $key => $value) {
    //     $list[$key]['credit3']=m('member')->getCredit($value['openid'],'credit3');
    //      $list[$key]['freeze_credit3']=m('member')->getCredit($value['openid'],'freeze_credit3');
    // }        

    // $merchall=pdo_fetchall('select pu.openid from '.tablename('sz_yi_dealmerch_user').' du left join '.tablename('sz_yi_perm_user').' pu on pu.uid = du.uid where du.uniacid = :uniacid ',array(':uniacid'=>$_W['uniacid']));
    // $currency=array();
    // foreach ($merchall as $key => $value) {
    // 	if ($value['openid']) {
    // 		$currency['credit2']+=m('member')->getCredit($value['openid'],'credit2');
    //         $currency['credit3']+=floatval(m('member')->getCredit($value['openid'],'credit3'));
    // 		$currency['currency_credit3']+=floatval(m('member')->getCredit($value['openid'],'currency_credit3'));
    //         $currency['freeze_credit3']+=floatval(m('member')->getCredit($value['openid'],'freeze_credit3'));
    // 	}
    // } 

   
                                                                                             
}else if ($operation == 'detail'){
  
    $uid = intval($_GPC['uid']);
    $supplierinfo = pdo_fetch('select * from ' . tablename('sz_yi_perm_user') . ' where uid="' . $uid . '" and uniacid=' . $_W['uniacid']);
    // 查询身份证图片
    $supplierinfo['idimgs'] = pdo_fetch('select idimg1,idimg2,permit from'.tablename('sz_yi_supplier_idimages').'where uniacid=:uniacid and openid=:openid', array(':uniacid'=>$_W['uniacid'], ':openid'=>$supplierinfo['openid']));
    $minfo=m('member')->getMember($supplierinfo['openid']);
    $supplierinfo['weixin']=$minfo['weixin'];
    $agent=m('member')->getMember($minfo['agentid']);
    $supplierinfo['agent']=$minfo['agentid'];
    if(!empty($supplierinfo['openid'])){
        $saler = m('member') -> getInfo($supplierinfo['openid']);
    }
    $totalmoney = pdo_fetchcolumn(' select ifnull(sum(g.costprice*og.total),0) from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . ' o on o.id=og.orderid left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid where og.supplier_uid=:supplier_uid and og.uniacid=:uniacid', array(':supplier_uid' => $uid, ':uniacid' => $_W['uniacid']));
    $totalmoneyok = pdo_fetchcolumn(' select ifnull(sum(g.costprice*og.total),0) from ' . tablename('sz_yi_order_goods') . ' og left join ' . tablename('sz_yi_order') . ' o on o.id=og.orderid left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid where og.supplier_uid=:supplier_uid and og.supplier_apply_status=1 and og.uniacid=:uniacid', array(':supplier_uid' => $uid, ':uniacid' => $_W['uniacid']));
    if(checksubmit('submit')){
        $data = is_array($_GPC['data']) ? $_GPC['data'] : array();
        $info=array(
            'weixin'=>$_GPC['weixin'],
            'agentid'=>$_GPC['agentid']
        );
        pdo_update('sz_yi_member',$info,array('id'=>$minfo['id']));
        $data['provance'] = $_GPC['birth']['province'];
        $data['city']     = $_GPC['birth']['city'];
        $data['area']     = $_GPC['birth']['district'];
        pdo_update('sz_yi_perm_user', $data, array('uid' => $uid));
        message('保存成功!', $this -> createPluginWebUrl('dealmerch/dealmerch'), 'success');
    }
}
load() -> func('tpl');
include $this -> template('dealmerch');
