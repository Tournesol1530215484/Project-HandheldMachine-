<?php
global $_W, $_GPC; 

$operation = $_GET['op'];
$uniacid = $_W['uniacid']; 
ca('dis.notice');
$set = $this->getSet();

if ($operation == 'post'){
		$bonus_level = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_dis_level') . " WHERE uniacid = '$uniacid'");//经销商
		
	//分销	
	$cm_level = pdo_fetchall("select dm.id,dm.realname,dm.commission_level,l.levelname  from " . tablename('sz_yi_dis_clevel') . " dm " . " left join " . tablename('sz_yi_commission_level') . " l on l.id = dm.commission_level" . " where   dm.uniacid = " . $_W['uniacid'] . " ORDER BY dm.id desc");
	//分红
	$bo_level = pdo_fetchall("select dm.id,dm.realname,dm.bonus_level,l.levelname  from " . tablename('sz_yi_dis_blevel') . " dm " . " left join " . tablename('sz_yi_bonus_level') . " l on l.id = dm.bonus_level" . " where   dm.uniacid = " . $_W['uniacid'] . " ORDER BY dm.id desc");
	//报单
	$bd_level = pdo_fetchall("select dm.id,dm.realname,dm.bd_level,bm.levelname from " . tablename('sz_yi_dis_level') . " dm " . " left join " . tablename('bd_level') . " bm on bm.id = dm.bd_level"  . " where   dm.uniacid = " . $_W['uniacid'] . "  and bm.uniacid = " . $_W['uniacid'] . "   ORDER BY dm.id desc");
	
	
	
	if($_POST){
			
			$memberid = pdo_fetchall("SELECT id FROM " . tablename('sz_yi_member') . " WHERE  uniacid=:uniacid  ", array( ':uniacid' => $_W['uniacid']));
			foreach($memberid as  $val) { 
					foreach($val as $value) { 
						$new_arr[] = $value; 	
					} 
				} 
			$c_leveluid = pdo_fetchall("SELECT uid FROM " . tablename('sz_yi_dis_clevel') . " WHERE  uniacid=:uniacid  ", array( ':uniacid' => $_W['uniacid']));
		
			foreach($c_leveluid as  $val1) { 
				foreach($val1 as $value1) { 
					$new_arr1[] = $value1; 
				} 
			}	
				
			$result = array_intersect($new_arr,$new_arr1);
				$clevel = pdo_fetch("SELECT bg,data FROM " . tablename('sz_yi_dis_clevel') . " WHERE  uniacid=:uniacid  ", array( ':uniacid' => $_W['uniacid']));
			pdo_delete('sz_yi_dis_clevel', array('uniacid' => $_W['uniacid']));
			if(!empty($result)){
				//取原本data字段的值 
			
				
			
			
			}
				
				
				
			}
			
		
	
	}
	

			
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
			/* print_r('<pre>');
			print_r($bonus_level); */
			$bd_level = pdo_fetchall("SELECT * FROM " . tablename('bd_level') . " WHERE uniacid = '$uniacid'");//分红
			/* print_r('<pre>');
		 	print_r($bd_level); */
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

	
	$c_level = pdo_fetch('SELECT id,commission_level, thumb FROM ' . tablename('sz_yi_dis_clevel') . ' WHERE id = :id', array(':id' => $id));
	$b_level = pdo_fetch('SELECT id,commission_level, thumb FROM ' . tablename('sz_yi_dis_blevel') . ' WHERE id = :id', array(':id' => $id));
	
	$bd_level = pdo_fetch('SELECT id,commission_level, thumb FROM ' . tablename('sz_yi_dis_level') . ' WHERE id = :id', array(':id' => $id));
	
	pdo_delete('sz_yi_dis_clevel', array('id' => $id));
	pdo_delete('sz_yi_dis_blevel', array('id' => $id));
	pdo_delete('sz_yi_dis_level', array('id' => $id));
	plog('dis.notice.delete', "删除商品 ID: {$id} ");
	message('删除成功！', referer(), 'success');
} 

load()->func('tpl');
include $this->template('notice');
