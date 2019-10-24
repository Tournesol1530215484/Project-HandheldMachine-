<?php
global $_W, $_GPC;
$operation = $_GET['op'];
$uniacid = $_W['uniacid']; 

$operation1 = $_GPC['insert'];   
$id = intval($_GPC['id']);   


$m = date('m',time());
$day = date('d',time()); 


//自定义添加资材
$diyform_member = pdo_fetchall("select diymemberdata from " .tablename('sz_yi_member') . " where uniacid=:uniacid order by id asc" , array(':uniacid' => $_W['uniacid'])); 
$diyform_plugin = p('diyform');
if ($diyform_plugin) {
    $set_config        = $diyform_plugin -> getSet();
    $user_diyform_open = $set_config['user_diyform_open'];
    if ($user_diyform_open == 1) {//判断是否开启
        $template_flag = 1;
        $diyform_id    = $set_config['user_diyform'];
        if (!empty($diyform_id)) {
            $formInfo     = $diyform_plugin -> getDiyformInfo($diyform_id);
            $fields       = $formInfo['fields'];
			//所填写的数据资材
            $diyform_data = iunserializer($diyform_member[2]['diymemberdata']);
            $f_data       = $diyform_plugin -> getDiyformData($diyform_data, $fields, $diyform_member);
			// $f_data值，是$diyform_data值
        }
    }
}



$commission_level = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_commission_level') . " WHERE uniacid = '$uniacid'");//分销
/* $updatelevel = @json_decode($level['updatelevel'], true); */
$bonus_level = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_bonus_level') . " WHERE uniacid = '$uniacid'"); //分红
/* $bd_level = pdo_fetchall("SELECT * FROM " . tablename('bd_level') . " WHERE uniacid = '$uniacid'");//报单  */

$dis_level = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_dis_level') . " WHERE uniacid = '$uniacid'");//经销商查询  

//开启与关闭前端开关
$rule = pdo_fetch("select * from " . tablename('rule') . ' where uniacid=:uniacid and module=:module and name=:name limit 1', array(':uniacid' => $uniacid , ':module' => 'cover', ':name' => "sz_yi经销商中心入口设置")); 
if(empty($rule)){
	pdo_insert('rule',array('uniacid'=>$uniacid, 'module' => 'cover','name' => "sz_yi经销商中心入口设置"));

}

if ($operation == 'update') {
	ca('dis.notice.update');
	if($_POST){
		$browser       = intval($_GPC['browser']);
		pdo_update('rule', array( 'status' => $browser), array( 'id' => $rule['id'],'uniacid' =>  $_W['uniacid']));
		$rule = pdo_fetch("select * from " . tablename('rule') . ' where  uniacid=:uniacid and  module=:module and name=:name limit 1', array(':uniacid' => $uniacid ,':module' => 'cover', ':name' => "sz_yi经销商中心入口设置"));
		/* file_put_contents(dirname(__FILE__).'/dasdsadsa',json_encode($browser ));   */
	}
}

//添加证件
	
//会员表
$agentlevel = pdo_fetchall("SELECT id,realname,mobile,weixin,avatar,agentlevel,bonuslevel FROM " . tablename('sz_yi_member') . " WHERE  uniacid=:uniacid  ", array( ':uniacid' => $_W['uniacid']));

		foreach($agentlevel as  $val) { 
	
			$val = array('id' => $val['id']);
		
				foreach($val as $value) { 
					$new_arr[] = $value; 
						
				} 
			} 
/* 		 	 print_r("<pre>");
			print_r($new_arr);  */

//分销证件表
$b_level = pdo_fetchall("SELECT uid FROM " . tablename('sz_yi_dis_blevel') . " WHERE  uniacid=:uniacid  ", array( ':uniacid' => $_W['uniacid']));

//分红证件表
$c_level = pdo_fetchall("SELECT uid FROM " . tablename('sz_yi_dis_clevel') . " WHERE  uniacid=:uniacid  ", array( ':uniacid' => $_W['uniacid']));

//报单证件表
/* $bd_level1 = pdo_fetchall("SELECT uid FROM " . tablename('sz_yi_dis_level') . " WHERE  uniacid=:uniacid  ", array( ':uniacid' => $_W['uniacid']));

$bd_name3 = pdo_fetchall("select m.id from " . tablename('sz_yi_member') . " m" . " left join " . tablename('bd_member') . " bm on bm.openid = m.openid" . " where    m.uniacid = " . $_W['uniacid'] . "  and  bm.uniacid = " . $_W['uniacid'] . "    ORDER BY m.id asc");	 */


		foreach($bd_name3 as  $val) { 
				foreach($val as $value) { 
					$new_arrbd[] = $value; 	
				} 
			}  

if ($operation1 == 'insert') {
	
	
		
			if($_POST['level'] !=0){

				
				if($c_level){
			  		foreach($c_level as  $val1) { 
					foreach($val1 as $value1) { 
						$new_arr1[] = $value1; 
					} 
				}

				$result = array_diff($new_arr,$new_arr1);
				
				if(!empty($result)){
					$member_1 = pdo_fetchall("SELECT agentlevel FROM " . tablename('sz_yi_member') . " WHERE  uniacid=:uniacid  ", array( ':uniacid' => $_W['uniacid']));
					foreach($member_1 as $ro){
							
						if($ro['agentlevel'] ==$_POST['level']){
							
							$a=2;
						}
					}
						if($a==2){
						
							if (checksubmit('submit')) {
								load()->model('account');	
							
									foreach($result as $result_id){
									$member = pdo_fetchall("SELECT id,realname,mobile,weixin,avatar,agentlevel,diymemberdata FROM " . tablename('sz_yi_member') . " WHERE  uniacid=:uniacid and agentlevel=:agentlevel  and id=:id", array( ':uniacid' => $_W['uniacid'],':agentlevel' => $_POST['level'],':id' => $result_id));
										if(!empty($member)){
												foreach($member as $row){
												
												$diymemberdata = iunserializer($row['diymemberdata']);
												
													 $data = array(
														'uniacid' => $_W['uniacid'],
														'uid' => $row['id'],
														'mobile' => $row['mobile'],
														'realname' => $row['realname'],
														'weixin' => $row['weixin'],
														'commission_level' => $_POST['level'],
														'bonus_level'  => '-',
														'bd_level'  => '-',
														'bg' => save_media($_GPC['bg']), 
														'data' => htmlspecialchars_decode($_GPC['data']),
														'createtime' => time(),
													 );	
														$data11 = json_decode(str_replace('&quot;', "'", $data['data']), true);
														
														$ychar="A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
														$list=explode(",",$ychar);
														$authnum='';
														
														for($i=0;$i<16;$i++){
														$randnum=rand(0,36); // 10+26;
														$authnum.=$list[$randnum];
														}
														
														foreach($data11 as $src_k =>$value){
															if($value['src']){
																$src1 = $src_k;
															   $value1 = $value['src'];
															   break;
															}
														}
														foreach($data11 as $realname_k =>$value){
															if($value['type']=="nickname"){
															$realname = $realname_k;
															   break;
															}
														}
														foreach($data11 as $mobile_k =>$value){
															if($value['type']=="title"){
																$mobile = $mobile_k;
															  	 break;
															}
														}
														foreach($data11 as $weixin_k =>$value){
															if($value['type']=="marketprice"){
																$weixin = $weixin_k;
															    break;
															}
														}
												
													if(!empty($_GPC['bg'])){
													
														$dst_path = ATTACHMENT_ROOT.$_GPC['bg'];//背景图
														 $dst = imagecreatefromstring(file_get_contents($dst_path));//创建图片的实例
														 $font = dirname(__FILE__).'/simsun.ttc';//字体
														 list($dst_w, $dst_h) = getimagesize($dst_path);
														//判断是不是添加图片水印
														if($value1){
														   $src_path = ATTACHMENT_ROOT . $value1;
														   $src = imagecreatefromstring(file_get_contents($src_path)); 
														   list($src_w, $src_h) = getimagesize($src_path); 
														 }

														$left1 = $dst_w *  $data11[$realname]['left']/300;
														$top1 = $dst_h * $data11[$realname]['top']/450;
														$left2 = $dst_w *  $data11[$mobile]['left']/300;
														$top2 = $dst_h *  $data11[$mobile]['top']/450;
														$left3 = $dst_w *  $data11[$weixin]['left']/300;
														$top3 = $dst_h *  $data11[$weixin]['top']/450;
													
														$r_color = $this -> model ->getcolor($data11[$realname]['color']); 
														$m_color = $this -> model ->getcolor($data11[$mobile]['color']); 
														$w_color = $this -> model ->getcolor($data11[$weixin]['color']); 
														$r_black = imagecolorallocate($dst, $r_color['red'],$r_color['green'],$r_color['blue']);//字体颜色
														$m_black = imagecolorallocate($dst, $m_color['red'],$m_color['green'],$m_color['blue']);//字体颜色
														$w_black = imagecolorallocate($dst, $w_color['red'],$w_color['green'],$w_color['blue']);//字体颜色
														
														imagefttext($dst, $data11[$realname]['size'], 0, $left1, $top1,$r_black, $font, $row['realname']);
														imagefttext($dst, $data11[$mobile]['size'], 0, $left2, $top2, $m_black, $font, $row['mobile']);
														imagefttext($dst, $data11[$weixin]['size'], 0, $left3, $top3, $w_black, $font, $row['weixin']);
														
														
														if($user_diyform_open==1){
															foreach($data11 as $val333 ){
																
																foreach($diymemberdata as $k1 => $valu){
																
																	if($val333['type'] == $k1){
																	
																	$color = $this -> model ->getcolor($val333['color']); 
																	$colour = imagecolorallocate($dst, $color['red'],$color['green'],$color['blue']);//字体颜色
																	$left = $dst_w *  $val333['left']/300;
																	$top = $dst_h *  $val333['top']/450;
																	imagefttext($dst, $val333['size'], 0, $left, $top, $colour, $font, $valu);
																	
																	}
																
																}
															
															}
														}
														
														
														
														//设置图片水印边距
														if($value1){
															$s_left = $data11[$src1]['left'] * 2.1;
															$s_top = $data11[$src1]['top']  * 2.3;
															$d_width =	$data11[$src1]['width'] * 1.8;
															$d_height =	$data11[$src1]['height'] * 1.8;
															imagecopymerge($dst, $src, $s_left, $s_top,0, 0,$d_width,$d_height, 100); 
														 }
														 
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
														if($value1){
															imagedestroy($src); 
														}
										  
														 $data111 = array('thumb'   =>  $thume.$a);
														$dis_level2 = array_merge($data,$data111);
												
														pdo_insert('sz_yi_dis_clevel', $dis_level2);  
													
													}
												
													  
												}
											
										}
									
									
									}
								  message('新添加分销用户证件成功！', $this->createPluginWebUrl('dis/notice', array('op' => 'post' )), 'success'); 
							}	
					 
		
						}
			
				}
				
		
				}else{

					foreach($agentlevel as $ro){
					if($ro['agentlevel'] ==$_POST['level']){
							$a=2;
						}
					}

					if($a==2){
						if (checksubmit('submit')) {
							load()->model('account');	
							$member = pdo_fetchall("SELECT id,realname,mobile,weixin,avatar,agentlevel,diymemberdata FROM " . tablename('sz_yi_member') . " WHERE  uniacid=:uniacid and agentlevel=:agentlevel", array( ':uniacid' => $_W['uniacid'],':agentlevel' => $_POST['level']));
							
						
							foreach($member as $row){
							/* print_r("<pre>");
							print_r($row); */
							$diymemberdata = iunserializer($row['diymemberdata']);
							
									 $data = array(
										'uniacid' => $_W['uniacid'],
										'uid' => $row['id'],
										'mobile' => $row['mobile'],
										'realname' => $row['realname'],
										'weixin' => $row['weixin'],
										'commission_level' => $_POST['level'],
										'bonus_level'  => '-',
										'bd_level'  => '-',
										'bg' => save_media($_GPC['bg']), 
										'data' => htmlspecialchars_decode($_GPC['data']),
										'createtime' => time(),
									 );	
								
								
								
										$data11 = json_decode(str_replace('&quot;', "'", $data['data']), true);

										$ychar="A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
										$list=explode(",",$ychar);
										$authnum='';
										for($i=0;$i<16;$i++){
										$randnum=rand(0,36); // 10+26;
										$authnum.=$list[$randnum];
										}
										 
										foreach($data11 as $src_k =>$value){
									
											if($value['src']){
												$src1 = $src_k;
											   $value1 = $value['src'];
											   break;
											}
											
											
										}
										foreach($data11 as $realname_k =>$value){
											if($value['type']=="nickname"){
											$realname = $realname_k;
											   break;
											}
										}
										foreach($data11 as $mobile_k =>$value){
											if($value['type']=="title"){
												$mobile = $mobile_k;
											  	 break;
											}
										}
										foreach($data11 as $weixin_k =>$value){
											if($value['type']=="marketprice"){
												$weixin = $weixin_k;
											    break;
											}
										}
									
									if(!empty($_GPC['bg'])){
									
										 $dst_path = ATTACHMENT_ROOT.$_GPC['bg'];//背景图
										 $dst = imagecreatefromstring(file_get_contents($dst_path));//创建图片的实例
										 $font = dirname(__FILE__).'/simsun.ttc';//字体
										 list($dst_w, $dst_h) = getimagesize($dst_path);
										//判断是不是添加图片水印
										if($value1){
										   $src_path = ATTACHMENT_ROOT . $value1;
										   $src = imagecreatefromstring(file_get_contents($src_path)); 
										   list($src_w, $src_h) = getimagesize($src_path); 
										  }
										$left1 = $dst_w *  $data11[$realname]['left']/300;
										$top1 = $dst_h * $data11[$realname]['top']/450;
										$left2 = $dst_w *  $data11[$mobile]['left']/300;
										$top2 = $dst_h *  $data11[$mobile]['top']/450;
										$left3 = $dst_w *  $data11[$weixin]['left']/300;
										$top3 = $dst_h *  $data11[$weixin]['top']/450;
										
										$r_color = $this -> model ->getcolor($data11[$realname]['color']); 
										$m_color = $this -> model ->getcolor($data11[$mobile]['color']); 
										$w_color = $this -> model ->getcolor($data11[$weixin]['color']); 
										$r_black = imagecolorallocate($dst, $r_color['red'],$r_color['green'],$r_color['blue']);//字体颜色
										$m_black = imagecolorallocate($dst, $m_color['red'],$m_color['green'],$m_color['blue']);//字体颜色
										$w_black = imagecolorallocate($dst, $w_color['red'],$w_color['green'],$w_color['blue']);//字体颜色
										
										imagefttext($dst, $data11[$realname]['size'], 0, $left1, $top1,$r_black, $font, $row['realname']);
										imagefttext($dst, $data11[$mobile]['size'], 0, $left2, $top2, $m_black, $font, $row['mobile']);
										imagefttext($dst, $data11[$weixin]['size'], 0, $left3, $top3, $w_black, $font, $row['weixin']);
										
										//print_r("<pre>");
										//print_r($diymemberdata);
										
										
										if($user_diyform_open==1){
											foreach($data11 as $val333 ){
												
												foreach($diymemberdata as $k1 => $valu){
												
													if($val333['type'] == $k1){
													
													$color = $this -> model ->getcolor($val333['color']); 
													$colour = imagecolorallocate($dst, $color['red'],$color['green'],$color['blue']);//字体颜色
													$left = $dst_w *  $val333['left']/300;
													$top = $dst_h *  $val333['top']/450;
													imagefttext($dst, $val333['size'], 0, $left, $top, $colour, $font, $valu);
													
													}
												
												}
											
											}
										}
											
										
										//设置图片水印边距
										if($value1){
											$s_left = $data11[$src1]['left'] * 2.1;
											$s_top = $data11[$src1]['top']  * 2.3;
											$d_width =	$data11[$src1]['width'] * 1.8;
											$d_height =	$data11[$src1]['height'] * 1.8;
											imagecopymerge($dst, $src, $s_left, $s_top,0, 0,$d_width,$d_height, 100); 
										 }
										 
										 
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
										if($value1){
											imagedestroy($src); 
										}
										  
										$data111 = array('thumb'   =>  $thume.$a);
										$dis_level2 = array_merge($data,$data111);	
									pdo_insert('sz_yi_dis_clevel', $dis_level2);  
									
									}
								
									  
								}
							 
						}	
						 message('分销证件设计成功！', $this->createPluginWebUrl('dis/notice', array('op' => 'post' )), 'success');   
	
					}
		
				}
			}

			elseif($_POST['level_name'] !=0){

				if($b_level){
			  		foreach($b_level as  $val1) { 
					foreach($val1 as $value1) { 
						$new_arr1[] = $value1; 
					} 
				}
				$result = array_diff($new_arr,$new_arr1);
				
				if(!empty($result)){
					$member_1 = pdo_fetchall("SELECT bonuslevel FROM " . tablename('sz_yi_member') . " WHERE  uniacid=:uniacid  ", array( ':uniacid' => $_W['uniacid']));
					foreach($member_1 as $ro){
							
						if($ro['bonuslevel'] ==$_POST['level_name']){
							
							$a=2;
						}
					}
						if($a==2){
						
							if (checksubmit('submit')) {
								load()->model('account');	
							
									foreach($result as $result_id){
									$member = pdo_fetchall("SELECT id,realname,mobile,weixin,avatar,bonuslevel,diymemberdata FROM " . tablename('sz_yi_member') . " WHERE  uniacid=:uniacid and bonuslevel=:bonuslevel  and id=:id", array( ':uniacid' => $_W['uniacid'],':bonuslevel' => $_POST['level_name'],':id' => $result_id));
										if(!empty($member)){
												foreach($member as $row){
												
												
												$diymemberdata = iunserializer($row['diymemberdata']);
												
													 $data = array(
														'uniacid' => $_W['uniacid'],
														'uid' => $row['id'],
														'mobile' => $row['mobile'],
														'realname' => $row['realname'],
														'weixin' => $row['weixin'],
														'commission_level' => '-',
														'bonus_level'  => $_POST['level_name'],
														'bd_level'  => '-',
														'bg' => save_media($_GPC['bg']), 
														'data' => htmlspecialchars_decode($_GPC['data']),
														'createtime' => time(),
													 );	
														$data11 = json_decode(str_replace('&quot;', "'", $data['data']), true);

														$ychar="A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
														$list=explode(",",$ychar);
														$authnum='';
														
														for($i=0;$i<20;$i++){
														$randnum=rand(0,36); // 10+26;
														$authnum.=$list[$randnum];
														}
														
														foreach($data11 as $src_k =>$value){
															if($value['src']){
																$src1 = $src_k;
															   $value1 = $value['src'];
															   break;
															}
														}
														foreach($data11 as $realname_k =>$value){
															if($value['type']=="nickname"){
															$realname = $realname_k;
															   break;
															}
														}
														foreach($data11 as $mobile_k =>$value){
															if($value['type']=="title"){
																$mobile = $mobile_k;
															  	 break;
															}
														}
														foreach($data11 as $weixin_k =>$value){
															if($value['type']=="marketprice"){
																$weixin = $weixin_k;
															    break;
															}
														}
												
													if(!empty($_GPC['bg'])){
													
														$dst_path = ATTACHMENT_ROOT.$_GPC['bg'];//背景图
														 $dst = imagecreatefromstring(file_get_contents($dst_path));//创建图片的实例
														 $font = dirname(__FILE__).'/simsun.ttc';//字体
														 list($dst_w, $dst_h) = getimagesize($dst_path);
														//判断是不是添加图片水印
														if($value1){
														   $src_path = ATTACHMENT_ROOT . $value1;
														   $src = imagecreatefromstring(file_get_contents($src_path)); 
														   list($src_w, $src_h) = getimagesize($src_path); 
														 }

														$left1 = $dst_w *  $data11[$realname]['left']/300;
														$top1 = $dst_h * $data11[$realname]['top']/450;
														$left2 = $dst_w *  $data11[$mobile]['left']/300;
														$top2 = $dst_h *  $data11[$mobile]['top']/450;
														$left3 = $dst_w *  $data11[$weixin]['left']/300;
														$top3 = $dst_h *  $data11[$weixin]['top']/450;
													
														$r_color = $this -> model ->getcolor($data11[$realname]['color']); 
														$m_color = $this -> model ->getcolor($data11[$mobile]['color']); 
														$w_color = $this -> model ->getcolor($data11[$weixin]['color']); 
														$r_black = imagecolorallocate($dst, $r_color['red'],$r_color['green'],$r_color['blue']);//字体颜色
														$m_black = imagecolorallocate($dst, $m_color['red'],$m_color['green'],$m_color['blue']);//字体颜色
														$w_black = imagecolorallocate($dst, $w_color['red'],$w_color['green'],$w_color['blue']);//字体颜色
														
														imagefttext($dst, $data11[$realname]['size'], 0, $left1, $top1,$r_black, $font, $row['realname']);
														imagefttext($dst, $data11[$mobile]['size'], 0, $left2, $top2, $m_black, $font, $row['mobile']);
														imagefttext($dst, $data11[$weixin]['size'], 0, $left3, $top3, $w_black, $font, $row['weixin']);
														
													if($user_diyform_open==1){
															foreach($data11 as $val333 ){
																
																foreach($diymemberdata as $k1 => $valu){
																
																	if($val333['type'] == $k1){
																	
																	$color = $this -> model ->getcolor($val333['color']); 
																	$colour = imagecolorallocate($dst, $color['red'],$color['green'],$color['blue']);//字体颜色
																	$left = $dst_w *  $val333['left']/300;
																	$top = $dst_h *  $val333['top']/450;
																	imagefttext($dst, $val333['size'], 0, $left, $top, $colour, $font, $valu);
																	
																	}
																
																}
															
															}
														}
														
														
														//设置图片水印边距
														if($value1){
															$s_left = $data11[$src1]['left'] * 2.1;
															$s_top = $data11[$src1]['top']  * 2.3;
															$d_width =	$data11[$src1]['width'] * 1.8;
															$d_height =	$data11[$src1]['height'] * 1.8;
															imagecopymerge($dst, $src, $s_left, $s_top,0, 0,$d_width,$d_height, 100); 
														 }
														 
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
														if($value1){
															imagedestroy($src); 
														}
										  
														 $data111 = array('thumb'   =>  $thume.$a);
														$dis_level2 = array_merge($data,$data111);
												
														pdo_insert('sz_yi_dis_blevel', $dis_level2);  
													
													}
												
													  
												}
											
										}
									
									
									}
								   message('新添加分红用户证件成功！', $this->createPluginWebUrl('dis/notice', array('op' => 'post' )), 'success'); 
							}	
					 
		
						}
			
				}
				
		
				}else{

					foreach($agentlevel as $ro){
						if($ro['bonuslevel'] ==$_POST['level_name']){
								$a=3;
							}
					}

					if($a==3){
						if (checksubmit('submit')) {
							load()->model('account');	
							$member = pdo_fetchall("SELECT id,realname,mobile,weixin,avatar,bonuslevel,diymemberdata FROM " . tablename('sz_yi_member') . " WHERE  uniacid=:uniacid and bonuslevel=:bonuslevel", array( ':uniacid' => $_W['uniacid'],':bonuslevel' => $_POST['level_name']));
							
							foreach($member as $row){
							
							$diymemberdata = iunserializer($row['diymemberdata']);
							
									 $data = array(
										'uniacid' => $_W['uniacid'],
										'uid' => $row['id'],
										'mobile' => $row['mobile'],
										'realname' => $row['realname'],
										'weixin' => $row['weixin'],
										'commission_level' => '-',
										'bonus_level'  => $_POST['level_name'],
										'bd_level'  => '-',
										'bg' => save_media($_GPC['bg']), 
										'data' => htmlspecialchars_decode($_GPC['data']),
										'createtime' => time(),
									 );	
								
										$data11 = json_decode(str_replace('&quot;', "'", $data['data']), true);

										$ychar="A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
										$list=explode(",",$ychar);
										$authnum='';
										for($i=0;$i<16;$i++){
										$randnum=rand(0,36); // 10+26;
										$authnum.=$list[$randnum];
										}
										
										foreach($data11 as $src_k =>$value){
											if($value['src']){
											$src1 = $src_k;
											   $value1 = $value['src'];
											   break;
											}
											
										}
										
										foreach($data11 as $realname_k =>$value){
											if($value['type']=="nickname"){
											$realname = $realname_k;
											   break;
											}
										}
										
										foreach($data11 as $mobile_k =>$value){
											if($value['type']=="title"){
												$mobile = $mobile_k;
											   break;
											}
										}
										
										foreach($data11 as $weixin_k =>$value){
											if($value['type']=="marketprice"){
												$weixin = $weixin_k;
											   break;
											}
										}
										
								
									if(!empty($_GPC['bg'])){
									
										  
										 $dst_path = ATTACHMENT_ROOT.$_GPC['bg'];//背景图
										 $dst = imagecreatefromstring(file_get_contents($dst_path));//创建图片的实例
										 $font = dirname(__FILE__).'/simsun.ttc';//字体
										 list($dst_w, $dst_h) = getimagesize($dst_path);
										//判断是不是添加图片水印
										if($value1){
										   $src_path = ATTACHMENT_ROOT . $value1;
										   $src = imagecreatefromstring(file_get_contents($src_path)); 
										   list($src_w, $src_h) = getimagesize($src_path); 
										  }
										$left1 = $dst_w *  $data11[$realname]['left']/300;
										$top1 = $dst_h * $data11[$realname]['top']/450;
										$left2 = $dst_w *  $data11[$mobile]['left']/300;
										$top2 = $dst_h *  $data11[$mobile]['top']/450;
										$left3 = $dst_w *  $data11[$weixin]['left']/300;
										$top3 = $dst_h *  $data11[$weixin]['top']/450;
									
										$r_color = $this -> model ->getcolor($data11[$realname]['color']); 
										$m_color = $this -> model ->getcolor($data11[$mobile]['color']); 
										$w_color = $this -> model ->getcolor($data11[$weixin]['color']); 
										$r_black = imagecolorallocate($dst, $r_color['red'],$r_color['green'],$r_color['blue']);//字体颜色
										$m_black = imagecolorallocate($dst, $m_color['red'],$m_color['green'],$m_color['blue']);//字体颜色
										$w_black = imagecolorallocate($dst, $w_color['red'],$w_color['green'],$w_color['blue']);//字体颜色
										
										imagefttext($dst, $data11[$realname]['size'], 0, $left1, $top1,$r_black, $font, $row['realname']);
										imagefttext($dst, $data11[$mobile]['size'], 0, $left2, $top2, $m_black, $font, $row['mobile']);
										imagefttext($dst, $data11[$weixin]['size'], 0, $left3, $top3, $w_black, $font, $row['weixin']);
										
										if($user_diyform_open==1){
											foreach($data11 as $val333 ){
												
												foreach($diymemberdata as $k1 => $valu){
												
													if($val333['type'] == $k1){
													
													$color = $this -> model ->getcolor($val333['color']); 
													$colour = imagecolorallocate($dst, $color['red'],$color['green'],$color['blue']);//字体颜色
													$left = $dst_w *  $val333['left']/300;
													$top = $dst_h *  $val333['top']/450;
													imagefttext($dst, $val333['size'], 0, $left, $top, $colour, $font, $valu);
													
													}
												
												}
											
											}
										}
										
										
										//设置图片水印边距
										if($value1){
											$s_left = $data11[$src1]['left'] * 2;
											$s_top = $data11[$src1]['top']  * 2.1;
											$d_width =	$data11[$src1]['width'] * 2;
											$d_height =	$data11[$src1]['height'] * 2;
											imagecopymerge($dst, $src, $s_left, $s_top,0, 0,$d_width,$d_height, 100); 
										 }
										 
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
										if($value1){
											imagedestroy($src); 
										}
										  
										$data111 = array('thumb'   =>  $thume.$a);
										$dis_level2 = array_merge($data,$data111);
								
										 pdo_insert('sz_yi_dis_blevel', $dis_level2);   
									
									}
								
									  
								}
							 
						}	
				     message('分红证件设计成功！', $this->createPluginWebUrl('dis/notice', array('op' => 'post' )), 'success');   
	
					}

				}

			}
			//报单开始
			/*



			 elseif($_POST['db_level'] !=0){

				//报单证件表
			 	if($bd_level1){
			   		foreach($bd_level1 as  $val1) { 
			 			foreach($val1 as $value1) { 
			 				$new_arr1[] = $value1; 
			 			} 
			 		}
				
			 	$result = array_diff($new_arrbd,$new_arr1);
				
			 		if(!empty($result)){

			 			$member_1 = pdo_fetchall("select m.id,bm.level as level1 from " . tablename('sz_yi_member') . " m" . " left join " . tablename('bd_member') . " bm on bm.openid = m.openid". " where    m.uniacid = " . $_W['uniacid'] . "  and  bm.uniacid = " . $_W['uniacid'] . "     ORDER BY m.id asc");	
				
							
						
			 			foreach($member_1 as $ro){
									
			 				if($ro['level1'] == $_POST['db_level']){
							
			 					$a=2;
			 				}
			 			}

			 				if($a==2){
			 					if (checksubmit('submit')) {
			 						load()->model('account');	
								
			 							foreach($result as $result_id){
										

			 							$member = pdo_fetchall("select m.id,m.realname,m.mobile,m.weixin,m.avatar,bm.level as level1,m.diymemberdata from " . tablename('sz_yi_member') . " m" . " left join " . tablename('bd_member') . " bm on bm.openid = m.openid"  . " where  m.uniacid=:uniacid and bm.uniacid=:uniacid and bm.level=:level1 and m.id=:id  ", array( ':uniacid' => $_W['uniacid'],':level1' => $_POST['db_level'],':id' => $result_id));

							
			 								if(!empty($member)){
			 										foreach($member as $row){
													$diymemberdata = iunserializer($row['diymemberdata']);
												
													
			 											 $data = array(
			 												'uniacid' => $_W['uniacid'],
			 												'uid' => $row['id'],
			 												'mobile' => $row['mobile'],
			 												'realname' => $row['realname'],
			 												'weixin' => $row['weixin'],
			 												'commission_level' => '-',
			 												'bonus_level'  => '-',
			 												'bd_level'  => $_POST['db_level'],
			 												'bg' => save_media($_GPC['bg']), 
			 												'data' => htmlspecialchars_decode($_GPC['data']),
			 												'createtime' => time(),
			 											 );	
			 												$data11 = json_decode(str_replace('&quot;', "'", $data['data']), true);

			 												$ychar="A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
			 												$list=explode(",",$ychar);
			 												$authnum='';
															
			 												for($i=0;$i<20;$i++){
			 												$randnum=rand(0,36);  10+26;
			 												$authnum.=$list[$randnum];
			 												}
															
			 												foreach($data11 as $src_k =>$value){
			 													if($value['src']){
			 														$src1 = $src_k;
			 													   $value1 = $value['src'];
			 													   break;
			 													}
			 												}
			 												foreach($data11 as $realname_k =>$value){
			 													if($value['type']=="nickname"){
			 													$realname = $realname_k;
			 													   break;
			 													}
			 												}
			 												foreach($data11 as $mobile_k =>$value){
			 													if($value['type']=="title"){
			 														$mobile = $mobile_k;
			 													  	 break;
			 													}
			 												}
			 												foreach($data11 as $weixin_k =>$value){
			 													if($value['type']=="marketprice"){
			 														$weixin = $weixin_k;
			 													    break;
			 													}
			 												}
													
			 											if(!empty($_GPC['bg'])){
														
			 												$dst_path = ATTACHMENT_ROOT.$_GPC['bg'];//背景图
			 												 $dst = imagecreatefromstring(file_get_contents($dst_path));//创建图片的实例
			 												 $font = dirname(__FILE__).'/simsun.ttc';//字体
			 												 list($dst_w, $dst_h) = getimagesize($dst_path);
			 												//判断是不是添加图片水印
			 												if($value1){
			 												   $src_path = ATTACHMENT_ROOT . $value1;
			 												   $src = imagecreatefromstring(file_get_contents($src_path)); 
			 												   list($src_w, $src_h) = getimagesize($src_path); 
			 												 }

			 												$left1 = $dst_w *  $data11[$realname]['left']/300;
			 												$top1 = $dst_h * $data11[$realname]['top']/450;
			 												$left2 = $dst_w *  $data11[$mobile]['left']/300;
			 												$top2 = $dst_h *  $data11[$mobile]['top']/450;
			 												$left3 = $dst_w *  $data11[$weixin]['left']/300;
			 												$top3 = $dst_h *  $data11[$weixin]['top']/450;
														
			 												$r_color = $this -> model ->getcolor($data11[$realname]['color']); 
			 												$m_color = $this -> model ->getcolor($data11[$mobile]['color']); 
			 												$w_color = $this -> model ->getcolor($data11[$weixin]['color']); 
			 												$r_black = imagecolorallocate($dst, $r_color['red'],$r_color['green'],$r_color['blue']);//字体颜色
			 												$m_black = imagecolorallocate($dst, $m_color['red'],$m_color['green'],$m_color['blue']);//字体颜色
			 												$w_black = imagecolorallocate($dst, $w_color['red'],$w_color['green'],$w_color['blue']);//字体颜色
															
			 												imagefttext($dst, $data11[$realname]['size'], 0, $left1, $top1,$r_black, $font, $row['realname']);
			 												imagefttext($dst, $data11[$mobile]['size'], 0, $left2, $top2, $m_black, $font, $row['mobile']);
			 												imagefttext($dst, $data11[$weixin]['size'], 0, $left3, $top3, $w_black, $font, $row['weixin']);
															
												
															if($user_diyform_open==1){
																foreach($data11 as $val333 ){
																	
																	foreach($diymemberdata as $k1 => $valu){
																	
																		if($val333['type'] == $k1){
																		
																		$color = $this -> model ->getcolor($val333['color']); 
																		$colour = imagecolorallocate($dst, $color['red'],$color['green'],$color['blue']);//字体颜色
																		$left = $dst_w *  $val333['left']/300;
																		$top = $dst_h *  $val333['top']/450;
																		imagefttext($dst, $val333['size'], 0, $left, $top, $colour, $font, $valu);
																		
																		}
																	
																	}
																
																}
															}
															
			 												//设置图片水印边距
			 												if($value1){
			 													$s_left = $data11[$src1]['left'] * 2.1;
			 													$s_top = $data11[$src1]['top']  * 2.3;
			 													$d_width =	$data11[$src1]['width'] * 1.8;
			 													$d_height =	$data11[$src1]['height'] * 1.8;
			 													imagecopymerge($dst, $src, $s_left, $s_top,0, 0,$d_width,$d_height, 100); 
			 												 }
															 
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
			 												if($value1){
			 													imagedestroy($src); 
			 												}
											  
			 												 $data111 = array('thumb'   =>  $thume.$a);
			 												$dis_level2 = array_merge($data,$data111);
													
			 												pdo_insert('sz_yi_dis_level', $dis_level2);  
														
			 											}
													
														  
			 										}
												
			 								}
										
										
			 							}
			 						   message('新添加报单用户证件成功！', $this->createPluginWebUrl('dis/notice', array('op' => 'post' )), 'success'); 
			 					}	
						 
			
			 				}
				
			 		}
				
		
			 	}else{

			 		$level_name3 = pdo_fetchall("select m.id,bl.levelname,bm.level as level1,m.level as level2 from " . tablename('sz_yi_member') . " m" . " left join " . tablename('bd_member') . " bm on bm.openid = m.openid" . " left join " . tablename('bd_level') . " bl on bl.id = bm.level"  . " where    m.uniacid = " . $_W['uniacid'] . "  and  bm.uniacid = " . $_W['uniacid'] . "  and  bl.uniacid = " . $_W['uniacid'] . "    ORDER BY m.id desc");	
					

			 	foreach($level_name3 as $ro){

			 		if($ro['level1'] ==$_POST['db_level']){
			 				$a=3;

			 			}
			 		}
					
			 		if($a==3){
						
			 			if (checksubmit('submit')) {
			 				load()->model('account');	

			 				$bd_member = pdo_fetchall("select m.id,m.realname,m.mobile,m.weixin,m.avatar,bm.level as level1,m.diymemberdata from " . tablename('sz_yi_member') . " m" . " left join " . tablename('bd_member') . " bm on bm.openid = m.openid"  . " WHERE  m.uniacid=:uniacid and bm.uniacid=:uniacid and bm.level=:level1", array( ':uniacid' => $_W['uniacid'],':level1' => $_POST['db_level']));
							
			 				foreach($bd_member as $row){
							
						$diymemberdata = iunserializer($row['diymemberdata']);
							
			 						 $data = array(
			 							'uniacid' => $_W['uniacid'],
			 							'uid' => $row['id'],
			 							'mobile' => $row['mobile'],
			 							'realname' => $row['realname'],
			 							'weixin' => $row['weixin'],
			 							'commission_level' => '-',
			 							'bonus_level'  => '-',
			 							'bd_level'  => $_POST['db_level'],
			 							'bg' => save_media($_GPC['bg']), 
			 							'data' => htmlspecialchars_decode($_GPC['data']),
			 							'createtime' => time(),
			 						 );	
								
			 							$data11 = json_decode(str_replace('&quot;', "'", $data['data']), true);
			 							
			 							$ychar="A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
			 							$list=explode(",",$ychar);
			 							$authnum='';
			 							for($i=0;$i<16;$i++){
			 							$randnum=rand(0,36);  //10+26;
			 							$authnum.=$list[$randnum];
			 							}
										
			 							foreach($data11 as $src_k =>$value){
			 								if($value['src']){
			 								$src1 = $src_k;
			 								   $value1 = $value['src'];
			 								   break;
			 								}
											
			 							}
										
			 							foreach($data11 as $realname_k =>$value){
			 								if($value['type']=="nickname"){
			 								$realname = $realname_k;
			 								   break;
			 								}
			 							}
										
			 							foreach($data11 as $mobile_k =>$value){
			 								if($value['type']=="title"){
			 									$mobile = $mobile_k;
			 								   break;
			 								}
			 							}
										
			 							foreach($data11 as $weixin_k =>$value){
			 								if($value['type']=="marketprice"){
			 									$weixin = $weixin_k;
			 								   break;
			 								}
			 							}
										
								
			 						if(!empty($_GPC['bg'])){
									  
			 							  $dst_path = ATTACHMENT_ROOT.$_GPC['bg'];//背景图
			 							 $dst = imagecreatefromstring(file_get_contents($dst_path));//创建图片的实例
			 							 $font = dirname(__FILE__).'/simsun.ttc';//字体
			 							 list($dst_w, $dst_h) = getimagesize($dst_path);
			 							//判断是不是添加图片水印
			 							if($value1){
			 							   $src_path = ATTACHMENT_ROOT . $value1;
			 							   $src = imagecreatefromstring(file_get_contents($src_path)); 
			 							   list($src_w, $src_h) = getimagesize($src_path); 
			 							  }
			 							$left1 = $dst_w *  $data11[$realname]['left']/300;
			 							$top1 = $dst_h * $data11[$realname]['top']/450;
			 							$left2 = $dst_w *  $data11[$mobile]['left']/300;
			 							$top2 = $dst_h *  $data11[$mobile]['top']/450;
			 							$left3 = $dst_w *  $data11[$weixin]['left']/300;
			 							$top3 = $dst_h *  $data11[$weixin]['top']/450;
									
			 							$r_color = $this -> model ->getcolor($data11[$realname]['color']); 
			 							$m_color = $this -> model ->getcolor($data11[$mobile]['color']); 
			 							$w_color = $this -> model ->getcolor($data11[$weixin]['color']); 
			 							$r_black = imagecolorallocate($dst, $r_color['red'],$r_color['green'],$r_color['blue']);//字体颜色
			 							$m_black = imagecolorallocate($dst, $m_color['red'],$m_color['green'],$m_color['blue']);//字体颜色
			 							$w_black = imagecolorallocate($dst, $w_color['red'],$w_color['green'],$w_color['blue']);//字体颜色
										//今年
										imagefttext($dst, 11, 0, 450, 266, $m_black, $font, $m);
			 							imagefttext($dst, 11, 0, 490, 266, $w_black, $font, $day);
										//去年
										imagefttext($dst, 11, 0, 450, 285, $m_black, $font, $m);
			 							imagefttext($dst, 11, 0, 490, 285, $w_black, $font, $day);
										
										 file_put_contents(dirname(__FILE__).'/dasdsadsa',json_encode($font)); 
			 							imagefttext($dst, $data11[$realname]['size'], 0, $left1, $top1,$r_black, $font, $row['realname']);
			 							imagefttext($dst, $data11[$mobile]['size'], 0, $left2, $top2, $m_black, $font, $row['mobile']);
			 							imagefttext($dst, $data11[$weixin]['size'], 0, $left3, $top3, $w_black, $font, $row['weixin']);
										
										
										if($user_diyform_open==1){
											foreach($data11 as $val333 ){
												
												foreach($diymemberdata as $k1 => $valu){
												
													if($val333['type'] == $k1){
													
													$color = $this -> model ->getcolor($val333['color']); 
													$colour = imagecolorallocate($dst, $color['red'],$color['green'],$color['blue']);//字体颜色
													$left = $dst_w *  $val333['left']/300;
													$top = $dst_h *  $val333['top']/450;
													imagefttext($dst, $val333['size'], 0, $left, $top, $colour, $font, $valu);
													
													}
												
												}
											
											}
										}
										
			 							//设置图片水印边距
			 							if($value1){
			 								$s_left = $data11[$src1]['left'] * 2;
			 								$s_top = $data11[$src1]['top']  * 2.1;
			 								$d_width =	$data11[$src1]['width'] * 2;
			 								$d_height =	$data11[$src1]['height'] * 2;
			 								imagecopymerge($dst, $src, $s_left, $s_top,0, 0,$d_width,$d_height, 100); 
			 							 }
										 
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
			 							if($value1){
			 								imagedestroy($src); 
			 							}
										
			 							$data111 = array('thumb'   =>  $thume.$a);
			 							$dis_level2 = array_merge($data,$data111);
								
			 						    pdo_insert('sz_yi_dis_level', $dis_level2);  
									
			 						}
								
									  
			 					}
							 
			 			}	
			 	  message('报单证件设计成功！', $this->createPluginWebUrl('dis/notice', array('op' => 'post' )), 'success'); 
	
			 		}

			 	}
			 }

		*/

		//报单结束
	
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
