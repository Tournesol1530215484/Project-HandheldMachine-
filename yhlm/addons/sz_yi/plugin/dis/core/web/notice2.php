<?php
global $_W, $_GPC; 
 
$operation = $_GET['op'];
$uniacid = $_W['uniacid']; 
ca('dis.notice'); 
$set = $this->getSet();

if ($operation == 'post'){
	$bonus_level = pdo_fetchall("SELECT * FROM " . tablename('sz_yi_dis_level') . " WHERE uniacid = '$uniacid'");//经销商
	$pindex    = max(1, intval($_GPC['page']));
    $psize     = 5;
    $params    = array();
	//分销	
	 $sql = "select dm.id,dm.realname,dm.commission_level,l.levelname  from " . tablename('sz_yi_dis_clevel') . " dm " . " left join " . tablename('sz_yi_commission_level') . " l on l.id = dm.commission_level" . " where   dm.uniacid = " . $_W['uniacid'] . "  and  l.uniacid = " . $_W['uniacid'] . "  ORDER BY dm.id desc";
     $sql .= " limit " . ($pindex - 1) * $psize . ',' . $psize;
	 $cm_level  = pdo_fetchall($sql, $params);
	 $total = pdo_fetchcolumn("select count(dm.id) from" . tablename('sz_yi_dis_clevel') . " dm  " . " left join " . tablename('sz_yi_commission_level') . "f on f.id=dm.commission_level" . " where dm.uniacid =" . $_W['uniacid'] . " ", $params);
	   $pager = pagination($total, $pindex, $psize);
	
	
	//分红
	$pindex1    = max(1, intval($_GPC['page']));
    $psize1     = 2;
    $params1    = array();
	$sql1 = "select dm.id,dm.realname,dm.bonus_level,l.levelname  from " . tablename('sz_yi_dis_blevel') . " dm " . " left join " . tablename('sz_yi_bonus_level') . " l on l.id = dm.bonus_level" . " where   dm.uniacid = " . $_W['uniacid'] . " ORDER BY dm.id desc";
	 $sql1 .= " limit " . ($pindex1 - 1) * $psize1 . ',' . $psize1;
	 $bo_level  = pdo_fetchall($sql1, $params1);
	 $total1 = pdo_fetchcolumn("select count(dm.id) from" . tablename('sz_yi_dis_blevel') . " dm  " . " left join " . tablename('sz_yi_bonus_level') . "f on f.id=dm.bonus_level" . " where dm.uniacid =" . $_W['uniacid'] . " ", $params1);
	 $pager11 = pagination($total1, $pindex1, $psize1);
	
	//报单
	/* $bd_level = pdo_fetchall("select dm.id,dm.realname,dm.bd_level,bm.levelname from " . tablename('sz_yi_dis_level') . " dm " . " left join " . tablename('bd_level') . " bm on bm.id = dm.bd_level"  . " where   dm.uniacid = " . $_W['uniacid'] . "  and bm.uniacid = " . $_W['uniacid'] . "   ORDER BY dm.id desc"); */
	
	
	//全面刷新数据
	if($_POST){
			
			
			$this -> model -> delFile("../attachment/dis_photo");
			
			//分销数据 
			$c_leveluid = pdo_fetchall("SELECT uid FROM " . tablename('sz_yi_dis_clevel') . " WHERE  uniacid=:uniacid  ", array( ':uniacid' => $_W['uniacid']));
			if(!empty($c_leveluid)){

					//总表数据
					$memberid = pdo_fetchall("SELECT id FROM " . tablename('sz_yi_member') . " WHERE  uniacid=:uniacid  ", array( ':uniacid' => $_W['uniacid']));
					foreach($memberid as  $val) { 
							foreach($val as $value) { 
								$new_arr[] = $value; 	
							} 
						}
					
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
					
						foreach($result as $result_id){
						$member = pdo_fetchall("SELECT id,realname,mobile,weixin,avatar,agentlevel FROM " . tablename('sz_yi_member') . " WHERE  uniacid=:uniacid   and id=:id", array( ':uniacid' => $_W['uniacid'],':id' => $result_id));
					
					
						if(!empty($member)){
						
								foreach($member as $row){
										 $data = array(
											'uniacid' => $_W['uniacid'],
											'uid' => $row['id'],
											'mobile' => $row['mobile'],
											'realname' => $row['realname'],
											'weixin' => $row['weixin'],
											'commission_level' => $row['agentlevel'],
											'bonus_level'  => '-',
											'bd_level'  => '-',
											'bg' => $clevel['bg'], 
											'data' => $clevel['data'],
											'createtime' => time(),
										 );	
										
											$data11 = json_decode(str_replace('&quot;', "'", $clevel['data']), true);
											
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
									
									 	if(!empty($clevel['bg'])){
											
												$dst_path = ATTACHMENT_ROOT.$clevel['bg'];//背景图
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
				
				}
				
			}


			//分红
			$b_leveluid = pdo_fetchall("SELECT uid FROM " . tablename('sz_yi_dis_blevel') . " WHERE  uniacid=:uniacid  ORDER BY id desc ", array( ':uniacid' => $_W['uniacid']));
			if(!empty($b_leveluid)){

					//总表数据
					$memberid1 = pdo_fetchall("SELECT id FROM " . tablename('sz_yi_member') . " WHERE  uniacid=:uniacid  ORDER BY id asc  ", array( ':uniacid' => $_W['uniacid']));
					foreach($memberid1 as  $val) { 
							foreach($val as $value) { 
								$new_arr2[] = $value; 	
							} 
						}
					
					foreach($b_leveluid as  $val1) { 
						foreach($val1 as $value1) { 
							$new_arr3[] = $value1; 
						} 
					}	
					
					$result2 = array_intersect($new_arr2,$new_arr3);
					
					
						$blevel = pdo_fetch("SELECT id,bg,data FROM " . tablename('sz_yi_dis_blevel') . " WHERE  uniacid=:uniacid  ", array( ':uniacid' => $_W['uniacid']));

					pdo_delete('sz_yi_dis_blevel', array('uniacid' => $_W['uniacid']));
					if(!empty($result2)){
						//取原本data字段的值 
					
						foreach($result2 as $result_id){
						$member = pdo_fetchall("SELECT id,realname,mobile,weixin,avatar,bonuslevel FROM " . tablename('sz_yi_member') . " WHERE  uniacid=:uniacid   and id=:id", array( ':uniacid' => $_W['uniacid'],':id' => $result_id));
					
					
						if(!empty($member)){
						
								foreach($member as $row){
										 $data = array(
											'uniacid' => $_W['uniacid'],
											'uid' => $row['id'],
											'mobile' => $row['mobile'],
											'realname' => $row['realname'],
											'weixin' => $row['weixin'],
											'commission_level' => '-',
											'bonus_level'  => $row['bonuslevel'],
											'bd_level'  => '-',
											'bg' => $blevel['bg'], 
											'data' => $blevel['data'],
											'createtime' => time(),
										 );	
										
											$data11 = json_decode(str_replace('&quot;', "'", $blevel['data']), true);
											
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
									
									 	if(!empty($blevel['bg'])){
											
												$dst_path = ATTACHMENT_ROOT.$blevel['bg'];//背景图
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
				
				}
				
			}
			
		

			//报单
			/* $bd_leveluid = pdo_fetchall("SELECT uid FROM " . tablename('sz_yi_dis_level') . " WHERE  uniacid=:uniacid  ORDER BY id desc ", array( ':uniacid' => $_W['uniacid']));
			if(!empty($bd_leveluid)){

					//总表数据
					$memberid2 = pdo_fetchall("select m.id from " . tablename('sz_yi_member') . " m" . " left join " . tablename('bd_member') . " bm on bm.openid = m.openid". " where    m.uniacid = " . $_W['uniacid'] . "  and  bm.uniacid = " . $_W['uniacid'] . "     ORDER BY m.id asc");	
					
					foreach($memberid2 as  $val) { 
							foreach($val as $value) { 
								$new_arr4[] = $value; 	
							} 
						}
					
					foreach($bd_leveluid as  $val1) { 
						foreach($val1 as $value1) { 
							$new_arr5[] = $value1; 
						} 
					}	
					
					$result2 = array_intersect($new_arr4,$new_arr5);
					
					
						$blevel = pdo_fetch("SELECT id,bg,data FROM " . tablename('sz_yi_dis_level') . " WHERE  uniacid=:uniacid  ", array( ':uniacid' => $_W['uniacid']));

					 pdo_delete('sz_yi_dis_level', array('uniacid' => $_W['uniacid']));
					if(!empty($result2)){
						//取原本data字段的值 
					
						foreach($result2 as $result_id){
						$member = pdo_fetchall("select m.id,m.realname,m.mobile,m.weixin,m.avatar,bm.level as level1 from " . tablename('sz_yi_member') . " m" . " left join " . tablename('bd_member') . " bm on bm.openid = m.openid"  . " where  m.uniacid=:uniacid and bm.uniacid=:uniacid  and m.id=:id  ", array( ':uniacid' => $_W['uniacid'],':level1' => $_POST['db_level'],':id' => $result_id));
						if(!empty($member)){
						
								foreach($member as $row){
										 $data = array(
											'uniacid' => $_W['uniacid'],
											'uid' => $row['id'],
											'mobile' => $row['mobile'],
											'realname' => $row['realname'],
											'weixin' => $row['weixin'],
											'commission_level' => '-',
											'bonus_level'  => '-',
											'bd_level'  => $row['level1'],
											'bg' => $blevel['bg'], 
											'data' => $blevel['data'],
											'createtime' => time(),
										 );	
										
											$data11 = json_decode(str_replace('&quot;', "'", $blevel['data']), true);
											
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
									
									 	if(!empty($blevel['bg'])){
											
												$dst_path = ATTACHMENT_ROOT.$blevel['bg'];//背景图
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
				
				}
				
			} */


		//结束

	
	}
	

			
}



//修改数据
if ($operation == 'update') {
			ca('dis.notice.update');
			$id1 = intval($_GPC['id']);
			$row11 = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_dis_level') . ' WHERE id = :id1', array(':id1' => $id1));
			
			/* if (empty($row11)) {
				message('抱歉，该数据不存在或是已经被删除！');
			} */
			
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
	$id1 = intval($_GPC['id1']);
	$id2 = intval($_GPC['id2']);
	$id3 = intval($_GPC['id3']);
	
	$c_level = pdo_fetch('SELECT id,commission_level, thumb FROM ' . tablename('sz_yi_dis_clevel') . ' WHERE id = :id', array(':id' => $id1));
	$b_level = pdo_fetch('SELECT id,commission_level, thumb FROM ' . tablename('sz_yi_dis_blevel') . ' WHERE id = :id', array(':id' => $id2));
	
	$bd_level = pdo_fetch('SELECT id,commission_level, thumb FROM ' . tablename('sz_yi_dis_level') . ' WHERE id = :id', array(':id' => $id3));
	
	pdo_delete('sz_yi_dis_clevel', array('id' => $id1));
	pdo_delete('sz_yi_dis_blevel', array('id' => $b_level['id']));
	pdo_delete('sz_yi_dis_level', array('id' => $id3));
	plog('dis.notice.delete', "删除商品 ID: {$id} ");
	message('删除成功！', referer(), 'success');
} 


load()->func('tpl');
include $this->template('notice');
