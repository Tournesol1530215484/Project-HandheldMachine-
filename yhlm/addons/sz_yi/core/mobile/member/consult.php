<?php

if (!defined('IN_IA')){

    exit('Access Denied');

}
global $_W,$_GPC;

$openid=m('user')->getOpenid(); 
$popenid=m('user')->islogin(); 
$openid = $openid?$openid:$popenid;
$op=empty($_GPC['op'])?'display':$_GPC['op'];

if($op == 'message'){
	$pindex = max(1, intval($_GPC['page'])); 
	$psize = 10; 
	$member=m('member')->getMember($openid);
	$list=pdo_fetchall('select l.*,m.nickname from '.tablename('sz_yi_liuyan').' l left join '.tablename('sz_yi_member').' m on m.id = l.superior_id where l.weid = :uniacid and l.lower_id=:id group by l.superior_id limit '.($pindex-1)*$psize.','.$psize,array(':uniacid'=>$_W['uniacid'],':id'=>$member['id']));
	if($list){
		foreach($list as $k => $v){	 		 	 
			$list[$k]['time']=date('Y-m-d H:i:s',$v['time']);
			$list[$k]['content']=pdo_fetchcolumn('select content from '.tablename('sz_yi_liuyan').' where weid = :uniacid and superior_id=:sid and lower_id=:lid and sender = "superior" order by time desc ',array(':uniacid'=>$_W['uniacid'],':sid'=>$v['superior_id'],':lid'=>$v['lower_id']));
			$list[$k]['url']=$this->createPluginMobileUrl('commission/team',array('op'=>'zixun','id'=>$v['superior_id'],'sender'=>'lower'));
		}		 	 	 	
		show_json(1,array('list'=>$list));
	}
		show_json(0,'没有更多了');
	
}

include $this -> template('member/consult');
