<?php
global $_W, $_GPC;
$operation = $_GET['op'];
$uniacid = $_W['uniacid']; 
$operation1 = $_GPC['insert']; 
$id = intval($_GPC['id']);
$commission_level = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_commission_level') . " WHERE uniacid = '$uniacid'");//分销
/* $updatelevel = @json_decode($level['updatelevel'], true); */
$bonus_level = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_bonus_level') . " WHERE uniacid = '$uniacid'");//分红
$dis_level = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_dis_level') . " WHERE uniacid = '$uniacid'");//经销商查询

//开启与关闭前端开关
$rule = pdo_fetch("select * from " . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'cover', ':name' => "sz_yi经销商中心入口设置")); 

if ($operation == 'update') {
	ca('dis.notice.update');
	if($_POST){
		$browser       = intval($_GPC['browser']);
		pdo_update('rule', array( 'status' => $browser), array( 'id' => $rule['id'],'uniacid' => $_W['uniacid'] ));
		$rule = pdo_fetch("select * from " . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name limit 1', array(':uniacid' => $_W['uniacid'], ':module' => 'cover', ':name' => "sz_yi经销商中心入口设置"));
		/* file_put_contents(dirname(__FILE__).'/dasdsadsa',json_encode($browser ));   */
	}
}



//添加证件

$member_1 = pdo_fetchall("SELECT id FROM " . tablename('sz_yi_member') . " WHERE  uniacid=:uniacid  ", array( ':uniacid' => $_W['uniacid']));
	
		/* $member_2 = array_column($member_1,'id'); */
		/* 	print_r('<pre>');
		print_r($member_1);
		print_r('<pre>');
		$dis_level1 = pdo_fetchall("SELECT uid FROM " . tablename('sz_yi_dis_level') . " WHERE  uniacid=:uniacid  ", array( ':uniacid' => $_W['uniacid']));
		print_r($dis_level1);
		/* $dis_level2 = array_column($dis_level1,'uid'); */	

		/* $result = array_diff($member_1,$dis_level1);
		print_r($result);
		exit; */
/* array_merge() */



$agentlevel = pdo_fetchall("SELECT id,realname,mobile,weixin,avatar,agentlevel FROM " . tablename('sz_yi_member') . " WHERE  uniacid=:uniacid  ", array( ':uniacid' => $_W['uniacid']));

if ($operation1 == 'insert') {

	foreach($agentlevel as $ro){

		if($ro['agentlevel'] ==$_POST['level']){
			$a=2;
		}
	}
	if($a==2){
			
			if (checksubmit('submit')) {
        load()->model('account');	
		
		$member = pdo_fetchall("SELECT id,realname,mobile,weixin,avatar,agentlevel FROM " . tablename('sz_yi_member') . " WHERE  uniacid=:uniacid and agentlevel=:agentlevel ", array( ':uniacid' => $_W['uniacid'],':agentlevel' => $_POST['level']));
		
		
		
		foreach($member as $row){
				 $data = array(
					'uniacid' => $_W['uniacid'],
					'uid' => $row['id'],
					'mobile' => $row['mobile'],
					'weixin' => $row['weixin'],
					'commission_level' => $_POST['level'],
					'bonus_level'  => $_POST['level_name'],
					'bg' => save_media($_GPC['bg']), 
					'data' => htmlspecialchars_decode($_GPC['data']),
					'createtime' => time(),
				 );	
		 	
					$data11 = json_decode(str_replace('&quot;', "'", $data['data']), true);
					/* print_r($data11);exit; */
					$ychar="A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
					$list=explode(",",$ychar);
					$authnum='';
					for($i=0;$i<16;$i++){
					$randnum=rand(0,36); // 10+26;
					$authnum.=$list[$randnum];
					}
					
					foreach($data11 as $value){
						if($value['src']){
						   $value1 = $value['src'];
						   break;
						}
					}
			
				if(!empty($_GPC['bg'])){
				
					  $dst_path = ATTACHMENT_ROOT.$_GPC['bg'];//背景图
					  $src_path = ATTACHMENT_ROOT . $value1;
					  
					 
					//创建图片的实例
					  $dst = imagecreatefromstring(file_get_contents($dst_path));
					  $src = imagecreatefromstring(file_get_contents($src_path)); 
					//打上文字
					  $font = dirname(__FILE__).'/simsun.ttc';//字体
					/* print_r($font);exit; */
					  list($dst_w, $dst_h) = getimagesize($dst_path);
					//获取水印图片的宽高
					 list($src_w, $src_h) = getimagesize($src_path); 
				
					$left1 = $dst_w * $data11[0]['left']/300;
					$top1 = $dst_h * $data11[0]['top']/450;
					$left2 = $dst_w * $data11[1]['left']/300;
					$top2 = $dst_h * $data11[1]['top']/450;
					$left3 = $dst_w * $data11[2]['left']/300;
					$top3 = $dst_h * $data11[2]['top']/450;
					
					$black = imagecolorallocate($dst, 0x00, 0x00, 0x00);//字体颜色
					imagefttext($dst, $data11[0]['size'], 0, $left1, $top1, $black, $font, $row['realname']);
					imagefttext($dst, $data11[1]['size'], 0, $left2, $top2, $black, $font, $row['mobile']);
					imagefttext($dst, $data11[2]['size'], 0, $left3, $top3, $black, $font, $row['weixin']);
					//图片水印
					imagecopymerge($dst, $src, $data11[0]['left'], $data11[0]['top'],0, 0,$data11[0]['width']  ,$data11[0]['height'], 100); 
				
					list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);//输出图片
			
					$thume = "dis_photo/".$authnum.$row['id'];
					$user_photo = ATTACHMENT_ROOT.$thume;
					 switch ($dst_type) {
							case 1://GIF
								imagegif($dst,$user_photo.".gif");
								$a = ".gif";
								break;
							case 2://JPG
								imagejpeg($dst,$user_photo.".jpg");
								$a = ".jpg";
								break;
							case 3://PNG
								imagepng($dst,$user_photo.".png");
								$a = ".png";
								break;
							default:
								break;
						  }
					
					imagedestroy($dst);
					imagedestroy($src); 
					  
					$data111 = array('thumb'   =>  $thume.$a);
					$dis_level2 = array_merge($data,$data111);
			
					pdo_insert('sz_yi_dis_level', $dis_level2);  
				
				}
			
				  
			}
				 
	    }	
	 message('证件设计成功！', $this->createPluginWebUrl('dis/notice', array('op' => 'post' )), 'success');  
	
	}

    	
		
}

ca('dis.set');
$set = $this->getSet(); 
$dir    = IA_ROOT . "/addons/sz_yi/plugin/" . $this->pluginname . "/template/mobile/";
/*  print_r($this->pluginname);  */
//Author:Y.yang Date:2016-04-08 Content:成为分销商条件（购买条件）
$goods = false;
if (!empty($set['become_goodsid'])) {
    $goods = pdo_fetch('select id,title from ' . tablename('sz_yi_goods') . ' where id=:id and uniacid=:uniacid limit 1 ', array(
        ':id' => $set['become_goodsid'],
        ':uniacid' => $_W['uniacid']
    ));
}
// END





load()->func('tpl');
include $this->template('set');
