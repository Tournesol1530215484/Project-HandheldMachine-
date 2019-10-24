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
		$item = pdo_fetch('SELECT * FROM' . tablename('sz_yi_report_type') .  ' WHERE uniacid='.$_W['uniacid'].' AND id ='.$id);
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
			pdo_insert('sz_yi_report_type', $data);
			$id = pdo_insertid();
			message('类型添加成功!',$this->createPluginWebUrl('dealmerch/report_type'), 'success');
		}else{
			pdo_update('sz_yi_report_type', $data, array('id' => $id));
			message('类型编辑成功!',$this->createPluginWebUrl('dealmerch/report_type'), 'success');
		}
	}
}else if($operation == 'display'){
	//列表
	$page     = empty($_GPC['page']) ? "" : $_GPC['page'];
    $pindex   = max(1, intval($page));
    $psize    = 10;
    $kw       = empty($_GPC['keyword']) ? "" : $_GPC['keyword'];
    $type    = pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_report_type') . ' WHERE uniacid=' . $_W['uniacid'] . ' AND title LIKE :name ' . ' ORDER BY status DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize, array(
        ':name' => "%{$kw}%"
    ));
    
    $total    = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('sz_yi_report_type') . ' WHERE uniacid=' . $_W['uniacid'] . ' AND title LIKE :name ' . 'ORDER BY display DESC ', array(
        ':name' => "%{$kw}%"
    ));
    $pager    = pagination($total, $pindex, $psize);
}
else if($operation == 'delete'){
	//删除
	if (empty($id)) {
        die('参数错误!');
    }
    $type = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_report_type') . ' WHERE uniacid=' . $_W['uniacid'] . ' AND id=:id', array(
        ':id' => $id
    ));
    if (empty($type)) {
        die('该类型未找到!');
    }
    pdo_delete('sz_yi_report_type', array(
        'id' => $id,
    ));
	message('删除成功！', referer(), 'success');
}else if($operation == 'list'){
	$pindex=max(1,intval($_GPC['page']));
	$psize=20;


	$params=array(
		':uniacid'=>$_W['uniacid']
	);
	$sql='select l.atid,l.objtype,r.title type,l.ctime,l.remark,m.realname,m.nickname from '.tablename('sz_yi_activity_report_log').' l left join '.tablename('sz_yi_report_type').' r on r.id = l.type left join '.tablename('sz_yi_member').' m on m.openid = l.openid  where l.uniacid = :uniacid';
	 		
	$sql.=' limit '.($pindex -1) * $psize.','.$psize;	 	
	$list=pdo_fetchall($sql,$params);
	foreach ($list as $key => $value) {
		if ($value['objtype'] == 1) {
			$table='sz_yi_activity';
			$str='活动';
		}else if($value['objtype'] == 2){
			$table='sz_yi_activity_article';
			$str='文章';
		}else if($value['objtype'] == 3){
			$table='sz_yi_member';
			$str='用户';
		}else if($value['objtype'] == 4){
			$table='sz_yi_match_picture';
			$str='图片';
		}else if($value['objtype'] == 5){
			$table='sz_yi_match';
			$str='比赛';
		}	 			
		$list[$key]['title']=pdo_fetchcolumn('select title from '.tablename($table).' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$value['atid']));
		if (!$list[$key]['title']) {
 	 		$list[$key]['title']=$str.'已删除';
 	 	}	 				 	 	
 	}		 		 				 		 		
	$totals=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_activity_report_log').' where uniacid = :uniacid',$params);
    $pager    = pagination($total, $pindex, $psize);	 	 
}	 	 	 	 	 			 	 	 	  	 	
load()->func('tpl');		 			 	 	 
include $this->template('report_type');