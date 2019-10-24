<?php
 global $_W, $_GPC;
ca('commission.set');
$set = $this -> getSet();
$power = array(
	'suppliermenu.commission'=>'佣金权限',
	'suppliermenu.freight'=>'运费权限',
	'suppliermenu.return'=>'全反权限',
	'suppliermenu.cargo'=>'货到付款权限',
	'suppliermenu.discount'=>'会员权限及折扣设置',
	'suppliermenu.verify'=>'线下核销',
	'suppliermenu.distribution'=>'分销设置'
);
$_power = $set['power'];

if (checksubmit('submit')){

   if(!empty($_GPC['power'])){
        
        foreach ($_GPC['power'] as $key => $value) {
        	 if(!array_key_exists($value,$power)||!array_key_exists($key,$power)||$key!=$value){
        	 	 message('参数错误'.$value);
        	 }
        }

   }

    $user = pdo_fetchall('select u.id , u.perms  from  '.tablename('sz_yi_perm_user').' as u join '.tablename('sz_yi_perm_role')." as r on u.roleid = r.id  where r.status1 = 1 and u.uniacid = '{$_W['uniacid']}' and r.uniacid = '{$_W['uniacid']}'  " );

    $moneyratio = $_GPC['brokerage'];

    if(!is_numeric($moneyratio)){
        message('佣金比例输入有误！',referer(), 'success');
    }
    if(0 > $moneyratio || $moneyratio > 100){
         message('佣金比例输入有误！',referer(), 'success');
    }


    foreach ($user as $key => $value) {

    	$p = explode(',', $value['perms']);
        $p = array_unique(array_merge($p,$_GPC['power']));
        $p = array_filter($p);
        // var_dump($p);
        pdo_update( 'sz_yi_perm_user',array( 'perms'=>implode(',',$p) ),array('id'=>$value['id'],'uniacid'=>$_W['uniacid']   )   );
    }




    $data = is_array($_GPC['setdata']) ? array_merge($set, $_GPC['setdata']) : array();
    $data['texts'] = is_array($_GPC['texts']) ? $_GPC['texts'] : array();
    $data['power'] = $_GPC['power'];
    $data['moneyratio'] = $moneyratio;
    $this -> updateSet($data);
    m('cache') -> set('template_' . $this -> pluginname, $data['style']);
    plog('commission.set', '修改基本设置');
    message('设置保存成功!', referer(), 'success');
}
load() -> func('tpl');
include $this -> template('set');
