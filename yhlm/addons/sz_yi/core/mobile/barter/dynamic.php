<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$openid = m('user') -> getOpenid();
$uniacid = $_W['uniacid'];

if($op=='more'){
		$today=strtotime(date('Ymd'));
		// $tmo = strtotime(date('Ymd',time()+3600*24));
		// $tmo=strtotime(date('Ymd',strtotime('+1day')));
		$sql='select m.realname,m.nickname,g.title,o.createtime,og.total from '.tablename('sz_yi_order').' o left join '.tablename('sz_yi_order_goods').' og on og.orderid = o.id left join '.tablename('sz_yi_goods').' g on g.id = og.goodsid left join '.tablename('sz_yi_member').' m on o.openid = m.openid where o.uniacid=:uniacid and o.isexchange = 1 and o.createtime > :stime';
		$params=[
			':uniacid'=>$_W['uniacid'],
			// ':etime'=>$tmo,
			':stime'=>$today
		];	 
		$list=pdo_fetchall($sql,$params);
		if ($list) {				 
			foreach ($list as $key => $value) {
				$list[$key]['time']=date('H:i:s',$list[$key]['createtime']);
			}
			show_json(1,array('list'=>$list));
		}
			show_json(0,array('list'=>array()));
}
     

    
include $this->template('barter/dynamic');
exit;