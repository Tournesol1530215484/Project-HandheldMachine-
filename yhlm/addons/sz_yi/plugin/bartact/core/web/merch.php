<?php
 global $_W, $_GPC;
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];

if ($operation == 'display'){ 
    ca('bartact.merch');        
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;

    $where = ' and pu.muserid > 0 and pu.uniacid=' . $_W['uniacid'];         

    if($_GPC['uid']){        
        $where .= ' and (m.realname like "%' . $_GPC['uid'] . '%" or m.nickname like "%' . $_GPC['uid'] . '%") and pu.uniacid=' . $_W['uniacid'];
    }
        
    $sql = 'select pu.*,m.id as mid,m.mobile ,m.realname from ' . tablename('sz_yi_perm_user') . ' pu left join '.tablename('sz_yi_member').' m on m.openid = pu.openid where 1 '. $where ;
    $sql .= ' order by pu.uid desc limit ' . ($pindex - 1) * $psize . ',' . $psize; 
    $list = pdo_fetchall($sql);
    $total = pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_perm_user').' pu left join '.tablename('sz_yi_member').' m on m.openid = pu.openid where 1' . $where);
    $pager = pagination($total, $pindex, $psize); 


    $merchall=pdo_fetchall('select pu.openid from '.tablename('sz_yi_member_user').' du left join '.tablename('sz_yi_perm_user').' pu on pu.uid = du.uid where du.uniacid = :uniacid ',array(':uniacid'=>$_W['uniacid']));
    $currency=array();
    foreach ($merchall as $key => $value) {         
    	if ($value['openid']) {
    		$currency['credit2']+=m('member')->getCredit($value['openid'],'credit2');
    		$currency['credit3']+=floatval(m('member')->getCredit($value['openid'],'credit3'));
    	}
    }

}else if ($operation == 'detail'){
  
    $uid = intval($_GPC['uid']);
    $supplierinfo=m('activity')->getMuser($uid);
    $member=m('member')->getMember($supplierinfo['openid']); 		 		 
    if(checksubmit('submit')){
        $data = is_array($_GPC['data']) ? $_GPC['data'] : array();
        unset($data['openid']); //不可修改	 	
        $data['province'] = $_GPC['reside']['province'];
        $data['city']     = $_GPC['reside']['city']; 	 
        $data['area']     = $_GPC['reside']['district'];	  	
        pdo_update('sz_yi_member_user', $data, array('uid' => $uid));		 	  
        message('保存成功!', $this -> createPluginWebUrl('bartact/merch'), 'success');
    }
}
load() -> func('tpl');      
include $this -> template('merch');     
