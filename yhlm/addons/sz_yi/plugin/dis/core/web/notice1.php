<?php
global $_W, $_GPC; 

$operation = $_GET['op'];
$uniacid = $_W['uniacid']; 
ca('dis.notice');
$set = $this->getSet();

if ($operation == 'post'){
		$bonus_level = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_dis_level') . " WHERE uniacid = '$uniacid'");//经销商
		/*  print_r($_GET); exit; */
		
		
			$level_name3 = pdo_fetchall("select dm.commission_level,dm.bonus_level,dm.id, l.levelname as levelname1 ,b.levelname as levelname2 from " . tablename('sz_yi_dis_level') . " dm " . " left join " . tablename('sz_yi_commission_level') . " l on l.id = dm.commission_level" . " left join " . tablename('sz_yi_bonus_level') . " b on b.id = dm.bonus_level". " where dm.commission_level= l.id    and  dm.uniacid = " . $_W['uniacid'] . "      and  l.uniacid = " . $_W['uniacid'] . "    ORDER BY dm.id desc");
		
		
}


//修改数据
if ($operation == 'update') {
	ca('dis.notice.update');
	$id1 = intval($_GPC['id']);
	$row11 = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_dis_level') . ' WHERE id = :id1', array(':id1' => $id1));
	
	if (empty($row11)) {
		message('抱歉，该数据不存在或是已经被删除！');
	}
	
	$commission_level = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_commission_level') . " WHERE uniacid = '$uniacid'");//分销
	$bonus_level = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_bonus_level') . " WHERE uniacid = '$uniacid'");//分红
	$dis_level = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_dis_level') . " WHERE uniacid = '$uniacid'");//经销商查询

	if($_POST){
			if($row11['commission_level'] == $_POST['level'] ){
			   echo 'fdafdfsa';
						  $level = $_POST['level'];
				  $level_name = $_POST['level_name'];
				  $thumb = $_POST['thumb'];
				  $dis = array('uniacid' => $_W['uniacid'],'commission_level' => $level,'bonus_level' => $level_name,'thumb' => $thumb);
				 /* $distri = array_merge($dis,$cover); */
				 pdo_update('sz_yi_dis_level', $dis, array( 'id' => $id1)); 
				 message('更新成功!', $this->createPluginWebUrl('dis/notice', array('op' => 'post')));
			   }else{
			   
					  message('不能更改分销层级!', $this->createPluginWebUrl('dis/notice', array('op' => 'post')));
			   
			   }
	   }
	
} 


//删除数据
if ($operation == 'delete') {
	ca('dis.notice.delete');
	$id = intval($_GPC['id']);
	$row = pdo_fetch('SELECT id,commission_level, thumb FROM ' . tablename('sz_yi_dis_level') . ' WHERE id = :id', array(':id' => $id));
	if (empty($row)) {
		message('抱歉，该数据不存在或是已经被删除！');
	}
	pdo_delete('sz_yi_dis_level', array('id' => $id));
	plog('dis.notice.delete', "删除商品 ID: {$id} ");
	message('删除成功！', referer(), 'success');
} 

load()->func('tpl');
include $this->template('notice');
