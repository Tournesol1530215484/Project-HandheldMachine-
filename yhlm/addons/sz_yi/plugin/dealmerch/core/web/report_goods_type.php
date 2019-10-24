<?php

if (!defined('IN_IA')) {
	print ('Access Denied');
}
global $_W, $_GPC;
//类型控制器  id、uniacid、display、title、logo、status

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$id = empty($_GPC['id']) ? "" : $_GPC['id'];

if($operation == 'post' ) {
	//添加或编辑
	if(!empty($id)){
		$item = pdo_fetch('SELECT * FROM' . tablename('sz_yi_goods_report_type') .  ' WHERE uniacid='.$_W['uniacid'].' AND id ='.$id);
	}
	
	if (checksubmit('submit')) {
		$data = array(
			'uniacid'   => $_W['uniacid'],
			'display'   => intval($_GPC['display']),
			'title'     => trim($_GPC['title']),
			'status'    => intval($_GPC['status']),
			'ctime'    => time()	 	 	
		); 	 		 	 	 	
		if(empty($id)){													//添加	
			if(empty($_GPC['title'])){
				message('请输入类型名称!');
			}
			pdo_insert('sz_yi_goods_report_type', $data);
			$id = pdo_insertid();
			message('类型添加成功!',$this->createPluginWebUrl('dealmerch/report_goods_type'), 'success');
		}else{
			pdo_update('sz_yi_goods_report_type', $data, array('id' => $id));
			message('类型编辑成功!',$this->createPluginWebUrl('dealmerch/report_goods_type'), 'success');
		}
	}
}else if($operation == 'display'){
	//列表
	$page     = empty($_GPC['page']) ? "" : $_GPC['page'];
    $pindex   = max(1, intval($page));
    $psize    = 10;
    $kw       = empty($_GPC['keyword']) ? "" : $_GPC['keyword'];
    $type    = pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_goods_report_type') . ' WHERE uniacid=' . $_W['uniacid'] . ' AND title LIKE :name ' . ' ORDER BY status DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, array(
        ':name' => "%{$kw}%"
    ));
    
    $total    = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_goods_report_type') . ' WHERE uniacid=' . $_W['uniacid'] . ' AND title LIKE :name ' . 'ORDER BY display DESC ', array(
        ':name' => "%{$kw}%"
    ));
    $pager    = pagination($total, $pindex, $psize);
}
else if($operation == 'delete'){
	//删除
	if (empty($id)) {
        die('参数错误!');
    }
    $type = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_goods_report_type') . ' WHERE uniacid=' . $_W['uniacid'] . ' AND id=:id', array(
        ':id' => $id
    ));
    if (empty($type)) {
        die('该类型未找到!');
    }
    pdo_delete('sz_yi_goods_report_type', array(
        'id' => $id,
    ));
	message('删除成功！', referer(), 'success');
}else if($operation == 'list'){
	$pindex=max(1,intval($_GPC['page']));
	$psize=20;

	$params=array(
		':uniacid'=>$_W['uniacid']
	);
	//$sql='select a.title,r.title type,l.ctime,l.remark,l.version,m.realname,m.nickname from '.tablename('sz_yi_goods_report_log').' l left join '.tablename('sz_yi_goods_report_type').' r on r.id = l.type left join '.tablename('sz_yi_member').' m on m.openid = l.openid left join '.tablename('sz_yi_ad_model').' a on a.id = l.adid where l.uniacid = :uniacid';
	//$sql='select a.title,a.thumb,r.title type,l.ctime,l.remark,m.realname,m.nickname from '.tablename('sz_yi_goods_report_log').' l left join '.tablename('sz_yi_goods_report_type').' r on r.id = l.type left join '.tablename('sz_yi_member').' m on m.openid = l.openid left join '.tablename('sz_yi_goods').' a on a.id = l.goodsid where l.uniacid = :uniacid';


	//$sql='select a.title,a.thumb,r.title type,l.ctime,l.remark,m.realname,m.nickname from '.tablename('sz_yi_goods_report_log').' l left join '.tablename('sz_yi_goods_report_type').' r on l.type in ( select id from hs_sz_yi_goods_report_type)  left join '.tablename('sz_yi_member').' m on m.openid = l.openid left join '.tablename('sz_yi_goods').' a on a.id = l.goodsid where l.uniacid = :uniacid ' ;
	//$sql='select m.realname,m.nickname,l.type,l.remark,l.ctime,g.thumb,g.title from hs_sz_yi_member m left join hs_sz_yi_goods_report_log  l on m.openid=l.openid  left join hs_sz_yi_goods g on l.goodsid=g.id';
	$sql='select m.realname,m.nickname,l.type,l.remark,l.ctime,g.title,g.thumb from hs_sz_yi_goods_report_log l left join hs_sz_yi_member  m on m.openid=l.openid AND l.uniacid=:uniacid left join hs_sz_yi_goods g on l.goodsid=g.id ORDER by l.ctime DESC';
	$sql.=' limit '.($pindex -1) * $psize.','.$psize;	 	
	$list=pdo_fetchall($sql,$params);
	//数组合并
	$temp=array();
	foreach ($list as $key => $value) {
		$type=explode ( "," , $list[$key]['type']);
		$ptn = "/\S+/i";
		$type=preg_grep($ptn, $type);	//去空操作，这里不知道为啥会有空数组。
		for($i=0;$i<count($type);$i++){
			 $sqls='select t.title FROM hs_sz_yi_goods_report_type t where t.id ='.$type[$i];
			 $temp[]=pdo_fetch($sqls);
		}
		$list[$key]['type']=$temp;
		array_splice($temp, 0, count($temp));//清空数组
	}
	$temps=array();
	foreach ($list as $key => $value) {
		foreach ($value['type'] as $k1 => $v1) {
			$temps[]=$v1['title'];
		}
	}
	foreach ($list as $key => $value) {
		$value['thumb']=unserialize($value['thumb']);
	}

	$totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_goods_report_log').' where uniacid = :uniacid',$params);
    $pager    = pagination($total, $pindex, $psize);	 	 
}	 	 	 	 	 		 	  	 	
load()->func('tpl');	 	 
include $this->template('report_goods_type');