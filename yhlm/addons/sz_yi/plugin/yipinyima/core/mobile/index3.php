<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
//接受传值过来的id
$yiid = $_GPC['yiid'];
$mid = $_GPC['mid'];
$userinfo = m('user')->oauth_info();
$openid = $userinfo['openid'];
//判断二维码传值过来的两个参数参数不为空
if (!empty($yiid) && !empty($mid)) {

    $item = pdo_fetch("SELECT * FROM " . tablename('yipinyima') . " d " .
        " left join " . tablename('yipinyimapeizhi') . ' m on m.id = d.yipinp_id ' .
        " left join " . tablename('yipinyimaerwei') . ' g on g.yipin_id = d.id ' .
        " where m.id=" . $yiid . " and g.id=" . $mid . " and d.uniacid=" . $_W['uniacid'] . "");
	$lise=pdo_fetch('select * from' .tablename('yipinyimaerwei') ." d " .
		" left join " .tablename('yipinyimajilu') . ' m on m.er_id =d.id ' .
		" where m.er_id=" . $mid . " and d.uniacid=" . $_W['uniacid'] ."");
	 $list = pdo_fetch('select * from' . tablename('yipinyimaerwei') . 'where id=:id and uniacid=:uniacid', array(
        ':uniacid' => $_W['uniacid'],
		':id'=>$mid
    ));
	$lskid=$item['id'];

	$shengyu=$item['yipin_shengyu'];
	$shiyongliang=$item['yi_shengyu'];
	//执行修改剩余量
	if($shiyongliang){
		$jianjian--;
		$syzt=array(
			'yi_shengyu'=>$jianjian
		);
	$result = pdo_update('yipinyima', $syzt,
           array(
                   'id' => $lskid
            ));
	if($result){
		$jia++;
		$mslg=array(
			'yipin_shengyu'=>$jia
		);
		pdo_update('yipinyima', $mslg,
           array(
                   'id' => $lskid
            ));
	}
	
	}


    $mer = $item['ms_status']; //二维码状态，0为生效 1为关闭
    $fxss = $item['ismnes']; //该字段支持只能扫描其他扫码失效 0为生效，1为关闭
    $yonj = $item['isbind']; //该变量用于判断绑定上下级关系 0为生效，1为关闭
    $zst = $item['jifenzt']; //查询表示不同身份扫描可以获得一次积分或现金或优惠券；off表示1个二维码只允许获得一次积分或现金或优惠券 0为no 1为off
 //   $smfh =iunserializer($item['roleid']); //拿出支持等级
	//拿出等级以及积分等
	//$mes = iunserializer($item['shiyongdengji']);
	
	//$fmsf = iunserializer($item['jifen']['shop']['itemid']);
	//print_r($fmsf);
	
   // $smfh = $item['groupid']; //拿出赠送等级
	//$yhjuan = $item['subcouponid']; //分销商id
	$zjxz = $item['paytype']; //拿出余额打入余额还是红包
    $erwzt = $lise['zt_status']; //扫描二维码状态，查询以及修改要用
    //查出联系电话
    $msgnn = pdo_fetch('select * from' . tablename('sz_yi_sysset') . 'where uniacid=:uniacid', array(
        ':uniacid' => $_W['uniacid']
    ));
    $set = iunserializer($msgnn["sets"]);
    $seten = $set['shop']['phone'];
    $time = "二维码扫码结果";
    $name = $userinfo['nickname'];
    $saomiao = pdo_fetch('select * from' . tablename('sz_yi_member') . 'where uniacid=:uniacid and  openid=:openid', array(
        ':uniacid' => $_W['uniacid'],
        ':openid' => $openid,
    ));
	//会员id
	$huiyuanid=$saomiao['id'];
	$snh = $saomiao['agentlevel'];
    $phost = $saomiao['mobile'];
    //查出分销商信息
    $huiyuan = pdo_fetch('select * from' . tablename('sz_yi_member') . 'where uniacid=:uniacid and id=:id', array(
        ':uniacid' => $_W['uniacid'],
        ':id' =>$item['commjssionid']
    ));
	 $sng = $saomiao['agentlevel'];
    //拿出分销商id
    $mys = $huiyuan['id'];
	foreach(iunserializer($item['jifen']) as $key => $value ){
						if($sng>0){
							if( $sng == $value['itemid']){
								$items = $value;
							}
						}else{
								if($huiyuanid  > $value['itemid']){
								$items = $value;
							}
						}
					}
					$id = $items['id'] ;
					$itemid = $items['itemid'];
					$jcs = $items['jifen'];
					$xjz = $items['xianjin'];
					$mks = $items['subcouponid'];
					$yhjms = $items['subcouponnum'];
    if ($mer == 0) {
		 //进行上下级判断
        if($yonj==0){
			
            //扫描人进行判断，如果扫描人的上级为空，就自动添加是扫描人的上级
            $msl=$saomiao['agentid'];
            $sma = $saomiao['id'];
            $namese=$saomiao['nickname'];
            $usename=$huiyuan['nickname'];
    
            if($msl==0){
                //拿出分销商的id
                 $fex=$huiyuan['id'];
                $user_data = array(
                    'agentid' => $fex
                );
                $result = pdo_update('sz_yi_member', $user_data,
                    array(
                        'id' => $sma
                    ));
                if($result){
                    //写入通知以及进行下一步
                    $msg = array(
                        'first' => array(
                            'title' => "亲爱的",
                            "value" => $namese
                        ),
                        'product' => array(
                            'title' => '邀请人是',
                            'value' => $usename,
                            "color" => "#4a5077"
                        ),
                        'remark' => array(
                            'value' => "\r\n谢谢您对我们的支持！",
                            "color" => "#4a5077"
                        )
                    );
                    $detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
                    m('message')->sendCustomNotice($openid, $msg, $detailurl);

                }
                if ($zst==0) {
                 //查询二维码字表,带入二维码ID进行查询，如果不存在，已openid进行查询，如果已经有了数据 就不在送，没有就进行送
				$erweimas=pdo_fetch('select * from' .tablename('yipinyimajilu') . 'where uniacid=:uniacid and openid=:openid and er_id=:er_id',array(
						        ':uniacid' => $_W['uniacid'],
								':openid' => $openid,
								':er_id'=>$mid
								));
					$ermwid=$erweimas['id'];
					$ermsf=$erweimas['zt_status'];
					if(!empty($erweimas)){
						//如果有数据的话，状态加1，不赠送
							if($ermsf){
						         $ermsf++;
									$dams = array(
										'zt_status' => $erwzt,
										'openid' => $openid,
										'zt_name' => $name,
										'zt_phosh' => $phost,
										'zt_shijian' => TIMESTAMP,
										'uniacid'=>$_W['uniacid'],
										'er_id'=>$mid
										);
                                    pdo_update('yipinyimajilu', $dams, array(
                                        'id' => $mid
                                    ));
							}
					}else{
						//如果没有数据的话，就开始进行赠送。
					foreach(iunserializer($item['jifen']) as $key => $value ){
						if($sng>0){
							if( $sng == $value['itemid']){
								$items = $value;
							}
						}else{
								if($huiyuanid  > $value['itemid']){
								$items = $value;
							}
						}
					}
					if( empty($items) || !array($items)){
						return;
					}
					$id = $items['id'] ;
					$itemid = $items['itemid'];
					$jcs = $items['jifen'];
					$xjz = $items['xianjin'];
					$mks = $items['subcouponid'];
					$yhjms = $items['subcouponnum'];
								if ($jcs > 0) {
                                    $this->model->jifengodsd($openid, $jcs, $mid, $name,$erwzt,$phost);
                                    $damss = array(
                                        'openid' => $openid,
                                        'zs_xhao' => $this->model->get_sn(),
                                        'zs_name' => $name,
                                        'zs_time' => TIMESTAMP,
                                        'zs_shuliang' => $jcs,
                                        'uniacid' => $_W['uniacid'],
                                        'zs_leixing' => '0'
                                    );
                                    pdo_insert('yipinyimarizhi', $damss, array(
                                        'id' => $msk
                                    ));
                                }

                                if ($xjz > 0) {
                                    $this->model->yipinyuemmm($openid,$zjxz,$xjz,$mid,$name,$erwzt,$phost);
                                    $damss = array(
                                        'openid' => $openid,
                                        'zs_xhao' => $this->model->get_sn(),
                                        'zs_name' => $name,
                                        'zs_time' => TIMESTAMP,
                                        'zs_shuliang'=>$xjz,
                                        'zs_xianjin' => $zjxz,
                                        'uniacid' => $_W['uniacid']
                                    );
                                    pdo_insert('yipinyimarizhi', $damss, array(
                                        'id' => $msk
                                    ));
                                }

                            if ($mks > 0) {
                                $this->model->yipinCouponm($openid, $mks, $yhjms, $mid, $name,$erwzt, $phost);
                                $damss = array(
                                    'openid' => $openid,
                                    'zs_xhao' => $this->model->get_sn(),
                                    'zs_name' => $name,
                                    'zs_time' => TIMESTAMP,
                                    'zs_shuliang' => $yhjms,
                                    'uniacid' => $_W['uniacid'],
                                    'zs_leixing' => '1',
                                    'yhijd'=>$mks
                                );
                                pdo_insert('yipinyimarizhi', $damss, array(
                                    'id' => $msk
                                ));
                            }
						
					}
					include $this->template('index');
                } else { //反之只能扫一次
						foreach(iunserializer($item['jifen']) as $key => $value ){
						if($sng>0){
							if( $sng == $value['itemid']){
								$items = $value;
							}
						}else{
								if($huiyuanid  > $value['itemid']){
								$items = $value;
							}
						}
					}
					if( empty($items) || !array($items)){
						return;
					}
					$id = $items['id'] ;
					$itemid = $items['itemid'];
					$jcs = $items['jifen'];
					$xjz = $items['xianjin'];
					$mks = $items['subcouponid'];
					$yhjms = $items['subcouponnum'];
						
                                if($erwzt>1){
                                    $erwzt++;
									$dams = array(
										'zt_status' => $erwzt,
										'openid' => $openid,
										'zt_name' => $name,
										'zt_phosh' => $phost,
										'zt_shijian' => TIMESTAMP,
										'uniacid'=>$_W['uniacid'],
										'er_id'=>$mid
										);
                                    pdo_update('yipinyimajilu', $dams, array(
                                        'id' => $mid
                                    ));
                                }else{
                                //查询积分如果有值就赠送积分，没有值就进入下一步
                                if ($jcs > 0) {
                                    $this->model->jifengods($openid, $jcs, $mid, $name, $erwzt,$phost);
                                    $damss = array(
                                        'openid' => $openid,
                                        'zs_xhao' => $this->model->get_sn(),
                                        'zs_name' => $name,
                                        'zs_time' => TIMESTAMP,
                                        'zs_shuliang' => $jcs,
                                        'uniacid' => $_W['uniacid'],
                                        'zs_leixing' => '0'
                                    );
                                    pdo_insert('yipinyimarizhi', $damss, array(
                                        'id' => $msk
                                    ));
                                }

                                if ($xjz > 0) {
                                    $this->model->yipinyuem($openid, $zjxz, $xjz, $mid, $name,$erwzt, $phost);
                                    $damss = array(
                                        'openid' => $openid,
                                        'zs_xhao' => $this->model->get_sn(),
                                        'zs_name' => $name,
                                        'zs_time' => TIMESTAMP,
                                        'zs_shuliang'=>$xjz,
                                        'zs_xianjin' => $zjxz,
                                        'uniacid' => $_W['uniacid']
                                    );
                                    pdo_insert('yipinyimarizhi', $damss, array(
                                        'id' => $msk
                                    ));
                                }

                            if ($mks > 0) {
                                $this->model->yipinCoupon($openid, $yhjuan, $yhjms, $mid, $name,$erwzt, $phost);
                                $damss = array(
                                    'openid' => $openid,
                                    'zs_xhao' => $this->model->get_sn(),
                                    'zs_name' => $name,
                                    'zs_time' => TIMESTAMP,
                                    'zs_shuliang' => $yhjms,
                                    'uniacid' => $_W['uniacid'],
                                    'zs_leixing' => '1',
                                    'yhijd'=>$mks
                                );
                                pdo_insert('yipinyimarizhi', $damss, array(
                                    'id' => $msk
                                ));
                            }
                               }
                                                
                }
				include $this->template('index');   
            }else{
			
                //如果扫描人已经有了上级，就自动进入下一步
                if($msl>0){
                    if ($zst == 0) {
						   //查询二维码字表,带入二维码ID进行查询，如果不存在，已openid进行查询，如果已经有了数据 就不在送，没有就进行送
				$erweimas=pdo_fetch('select * from' .tablename('yipinyimajilu') . 'where uniacid=:uniacid and openid=:openid and er_id=:er_id',array(
						        ':uniacid' => $_W['uniacid'],
								':openid' => $openid,
								':er_id'=>$mid
								));
					$ermwid=$erweimas['id'];
					$ermsf=$erweimas['zt_status'];
					if(!empty($erweimas)){
						//如果有数据的话，状态加1，不赠送
							if($ermsf){
						         $ermsf++;
									$dams = array(
										'zt_status' => $erwzt,
										'openid' => $openid,
										'zt_name' => $name,
										'zt_phosh' => $phost,
										'zt_shijian' => TIMESTAMP,
										'uniacid'=>$_W['uniacid'],
										'er_id'=>$mid
										);
                                    pdo_update('yipinyimajilu', $dams, array(
                                        'id' => $mid
                                    ));
							}
					}else{
						//如果没有数据的话，就开始进行赠送。
					foreach(iunserializer($item['jifen']) as $key => $value ){
						if($sng>0){
							if( $sng == $value['itemid']){
								$items = $value;
							}
						}else{
								if($huiyuanid  > $value['itemid']){
								$items = $value;
							}
						}
					}
					if( empty($items) || !array($items)){
						return;
					}
					$id = $items['id'] ;
					$itemid = $items['itemid'];
					$jcs = $items['jifen'];
					$xjz = $items['xianjin'];
					$mks = $items['subcouponid'];
					$yhjms = $items['subcouponnum'];
								if ($jcs > 0) {
                                    $this->model->jifengodsd($openid, $jcs, $mid, $name,$erwzt,$phost);
                                    $damss = array(
                                        'openid' => $openid,
                                        'zs_xhao' => $this->model->get_sn(),
                                        'zs_name' => $name,
                                        'zs_time' => TIMESTAMP,
                                        'zs_shuliang' => $jcs,
                                        'uniacid' => $_W['uniacid'],
                                        'zs_leixing' => '0'
                                    );
                                    pdo_insert('yipinyimarizhi', $damss, array(
                                        'id' => $msk
                                    ));
                                }

                                if ($xjz > 0) {
                                    $this->model->yipinyuemmm($openid,$zjxz,$xjz,$mid,$name,$erwzt,$phost);
                                    $damss = array(
                                        'openid' => $openid,
                                        'zs_xhao' => $this->model->get_sn(),
                                        'zs_name' => $name,
                                        'zs_time' => TIMESTAMP,
                                        'zs_shuliang'=>$xjz,
                                        'zs_xianjin' => $zjxz,
                                        'uniacid' => $_W['uniacid']
                                    );
                                    pdo_insert('yipinyimarizhi', $damss, array(
                                        'id' => $msk
                                    ));
                                }

                            if ($mks > 0) {
                                $this->model->yipinCouponm($openid, $mks, $yhjms, $mid, $name,$erwzt, $phost);
                                $damss = array(
                                    'openid' => $openid,
                                    'zs_xhao' => $this->model->get_sn(),
                                    'zs_name' => $name,
                                    'zs_time' => TIMESTAMP,
                                    'zs_shuliang' => $yhjms,
                                    'uniacid' => $_W['uniacid'],
                                    'zs_leixing' => '1',
                                    'yhijd'=>$mks
                                );
                                pdo_insert('yipinyimarizhi', $damss, array(
                                    'id' => $msk
                                ));
                            }
						
						}
					include $this->template('index');
                    } else { //反之只能扫一次
				foreach(iunserializer($item['jifen']) as $key => $value ){
						if($sng>0){
							if( $sng == $value['itemid']){
								$items = $value;
							}
						}else{
								if($huiyuanid  > $value['itemid']){
								$items = $value;
							}
						}
					}
					if( empty($items) || !array($items)){
						return;
					}
					$id = $items['id'] ;
					$itemid = $items['itemid'];
					$jcs = $items['jifen'];
					$xjz = $items['xianjin'];
					$mks = $items['subcouponid'];
					$yhjms = $items['subcouponnum'];	
                                //查询赠送状态，如果已经扫描了，将不会在扫描
                                if($erwzt>1){
                                    $erwzt++;
									$dams = array(
										'zt_status' => $erwzt,
										'openid' => $openid,
										'zt_name' => $name,
										'zt_phosh' => $phost,
										'zt_shijian' => TIMESTAMP,
										'uniacid'=>$_W['uniacid'],
										'er_id'=>$mid
										);
                                    pdo_update('yipinyimajilu', $dams, array(
                                        'id' => $mid
                                    ));
                                }else{
                                //查询积分如果有值就赠送积分，没有值就进入下一步
                                if ($jcs > 0) {
                                    $this->model->jifengods($openid, $jcs, $mid, $name, $erwzt,$phost);
                                    $damss = array(
                                        'openid' => $openid,
                                        'zs_xhao' => $this->model->get_sn(),
                                        'zs_name' => $name,
                                        'zs_time' => TIMESTAMP,
                                        'zs_shuliang' => $jcs,
                                        'uniacid' => $_W['uniacid'],
                                        'zs_leixing' => '0'
                                    );
                                    pdo_insert('yipinyimarizhi', $damss, array(
                                        'id' => $msk
                                    ));
                                }

                                if ($xjz > 0) {
                                    $this->model->yipinyuem($openid, $zjxz, $xjz, $mid, $name,$erwzt, $phost);
                                    $damss = array(
                                        'openid' => $openid,
                                        'zs_xhao' => $this->model->get_sn(),
                                        'zs_name' => $name,
                                        'zs_time' => TIMESTAMP,
                                        'zs_shuliang'=>$xjz,
                                        'zs_xianjin' => $zjxz,
                                        'uniacid' => $_W['uniacid']
                                    );
                                    pdo_insert('yipinyimarizhi', $damss, array(
                                        'id' => $msk
                                    ));
                                }

                            if ($mks > 0) {
                                $this->model->yipinCoupon($openid, $yhjuan, $yhjms, $mid, $name,$erwzt, $phost);
                                $damss = array(
                                    'openid' => $openid,
                                    'zs_xhao' => $this->model->get_sn(),
                                    'zs_name' => $name,
                                    'zs_time' => TIMESTAMP,
                                    'zs_shuliang' => $yhjms,
                                    'uniacid' => $_W['uniacid'],
                                    'zs_leixing' => '1',
                                    'yhijd'=>$mks
                                );
                                pdo_insert('yipinyimarizhi', $damss, array(
                                    'id' => $msk
                                ));
                            }
                               }
                                include $this->template('index');

                        
                    }
                }
            }
        }
		//二维码支持下级扫描
		if($fxss==0){
		$saomiaod = pdo_fetch('select * from' . tablename('sz_yi_member') . 'where uniacid=:uniacid and agentid=:agentid and openid=:openid', array(
                ':uniacid' => $_W['uniacid'],
                'openid' => $openid,
                'agentid' => $mys
            ));
			if($saomiaod){
				 $sng = $saomiaod['agentlevel'];
				 if ($zst==0) {
                   //查询二维码字表,带入二维码ID进行查询，如果不存在，已openid进行查询，如果已经有了数据 就不在送，没有就进行送
				$erweimas=pdo_fetch('select * from' .tablename('yipinyimajilu') . 'where uniacid=:uniacid and openid=:openid and er_id=:er_id',array(
						        ':uniacid' => $_W['uniacid'],
								':openid' => $openid,
								':er_id'=>$mid
								));
					$ermwid=$erweimas['id'];
					$ermsf=$erweimas['zt_status'];
					if(!empty($erweimas)){
						//如果有数据的话，状态加1，不赠送
							if($ermsf){
						         $ermsf++;
									$dams = array(
										'zt_status' => $erwzt,
										'openid' => $openid,
										'zt_name' => $name,
										'zt_phosh' => $phost,
										'zt_shijian' => TIMESTAMP,
										'uniacid'=>$_W['uniacid'],
										'er_id'=>$mid
										);
                                    pdo_update('yipinyimajilu', $dams, array(
                                        'id' => $mid
                                    ));
							}
					}else{
						//如果没有数据的话，就开始进行赠送。
					foreach(iunserializer($item['jifen']) as $key => $value ){
						if($sng>0){
							if( $sng == $value['itemid']){
								$items = $value;
							}
						}else{
								if($huiyuanid  > $value['itemid']){
								$items = $value;
							}
						}
					}
					if( empty($items) || !array($items)){
						return;
					}
					$id = $items['id'] ;
					$itemid = $items['itemid'];
					$jcs = $items['jifen'];
					$xjz = $items['xianjin'];
					$mks = $items['subcouponid'];
					$yhjms = $items['subcouponnum'];
								if ($jcs > 0) {
                                    $this->model->jifengodsd($openid, $jcs, $mid, $name,$erwzt,$phost);
                                    $damss = array(
                                        'openid' => $openid,
                                        'zs_xhao' => $this->model->get_sn(),
                                        'zs_name' => $name,
                                        'zs_time' => TIMESTAMP,
                                        'zs_shuliang' => $jcs,
                                        'uniacid' => $_W['uniacid'],
                                        'zs_leixing' => '0'
                                    );
                                    pdo_insert('yipinyimarizhi', $damss, array(
                                        'id' => $msk
                                    ));
                                }

                                if ($xjz > 0) {
                                    $this->model->yipinyuemmm($openid,$zjxz,$xjz,$mid,$name,$erwzt,$phost);
                                    $damss = array(
                                        'openid' => $openid,
                                        'zs_xhao' => $this->model->get_sn(),
                                        'zs_name' => $name,
                                        'zs_time' => TIMESTAMP,
                                        'zs_shuliang'=>$xjz,
                                        'zs_xianjin' => $zjxz,
                                        'uniacid' => $_W['uniacid']
                                    );
                                    pdo_insert('yipinyimarizhi', $damss, array(
                                        'id' => $msk
                                    ));
                                }

                            if ($mks > 0) {
                                $this->model->yipinCouponm($openid, $mks, $yhjms, $mid, $name,$erwzt, $phost);
                                $damss = array(
                                    'openid' => $openid,
                                    'zs_xhao' => $this->model->get_sn(),
                                    'zs_name' => $name,
                                    'zs_time' => TIMESTAMP,
                                    'zs_shuliang' => $yhjms,
                                    'uniacid' => $_W['uniacid'],
                                    'zs_leixing' => '1',
                                    'yhijd'=>$mks
                                );
                                pdo_insert('yipinyimarizhi', $damss, array(
                                    'id' => $msk
                                ));
                            }
						
					}
					include $this->template('index');
                } else { //反之只能扫一次
                   				foreach(iunserializer($item['jifen']) as $key => $value ){
						if($sng>0){
							if( $sng == $value['itemid']){
								$items = $value;
							}
						}else{
								if($huiyuanid  > $value['itemid']){
								$items = $value;
							}
						}
					}
					if( empty($items) || !array($items)){
						return;
					}
					$id = $items['id'] ;
					$itemid = $items['itemid'];
					$jcs = $items['jifen'];
					$xjz = $items['xianjin'];
					$mks = $items['subcouponid'];
					$yhjms = $items['subcouponnum'];
                                if($erwzt>1){
                                    $erwzt++;
									$dams = array(
										'zt_status' => $erwzt,
										'openid' => $openid,
										'zt_name' => $name,
										'zt_phosh' => $phost,
										'zt_shijian' => TIMESTAMP,
										'uniacid'=>$_W['uniacid'],
										'er_id'=>$mid
										);
                                    pdo_update('yipinyimajilu', $dams, array(
                                        'id' => $mid
                                    ));
                                }else{
                                //查询积分如果有值就赠送积分，没有值就进入下一步
                                if ($jcs > 0) {
                                    $this->model->jifengods($openid, $jcs, $mid, $name,$erwzt,$phost);
                                    $damss = array(
                                        'openid' => $openid,
                                        'zs_xhao' => $this->model->get_sn(),
                                        'zs_name' => $name,
                                        'zs_time' => TIMESTAMP,
                                        'zs_shuliang' => $jcs,
                                        'uniacid' => $_W['uniacid'],
                                        'zs_leixing' => '0'
                                    );
                                    pdo_insert('yipinyimarizhi', $damss, array(
                                        'id' => $msk
                                    ));
                                }

                                if ($xjz > 0) {
                                    $this->model->yipinyuem($openid, $zjxz, $xjz, $mid, $name,$erwzt,$phost);
                                    $damss = array(
                                        'openid' => $openid,
                                        'zs_xhao' => $this->model->get_sn(),
                                        'zs_name' => $name,
                                        'zs_time' => TIMESTAMP,
                                        'zs_shuliang'=>$xjz,
                                        'zs_xianjin' => $zjxz,
                                        'uniacid' => $_W['uniacid']
                                    );
                                    pdo_insert('yipinyimarizhi', $damss, array(
                                        'id' => $msk
                                    ));
                                }

                            if ($mks > 0) {
                                $this->model->yipinCoupon($openid, $yhjuan, $yhjms, $mid, $name,$erwzt, $phost);
                                $damss = array(
                                    'openid' => $openid,
                                    'zs_xhao' => $this->model->get_sn(),
                                    'zs_name' => $name,
                                    'zs_time' => TIMESTAMP,
                                    'zs_shuliang' => $yhjms,
                                    'uniacid' => $_W['uniacid'],
                                    'zs_leixing' => '1',
                                    'yhijd'=>$mks
                                );
                                pdo_insert('yipinyimarizhi', $damss, array(
                                    'id' => $msk
                                ));
                            }
                               }
                                include $this->template('index');                   
                }
			}else{
				print_r("输出一个错误");
			}
		}
	
	//http://test.61why.com/app/app/index.php?i=5&c=entry&m=sz_yi&do=plugin&p=yipinyima&yiid=3&mid=2
	
	//两个同时关闭的话，就拿出扫描者的等级进行赠送
	if($fxss==1 && $yonj==1){
			
			 $hyg = $saomiao['level'];
			
				 if ($zst==0) {
				
						//查询二维码字表,带入二维码ID进行查询，如果不存在，已openid进行查询，如果已经有了数据 就不在送，没有就进行送
				$erweimas=pdo_fetch('select * from' .tablename('yipinyimajilu') . 'where uniacid=:uniacid and openid=:openid and er_id=:er_id',array(
						        ':uniacid' => $_W['uniacid'],
								':openid' => $openid,
								':er_id'=>$mid
								));
					
					$ermsf=$erweimas['zt_status'];
					if(!empty($erweimas)){
						//如果有数据的话，状态加1，不赠送
							if($ermsf){
						         $ermsf++;
									$dams = array(
										'zt_status' => $erwzt,
										'openid' => $openid,
										'zt_name' => $name,
										'zt_phosh' => $phost,
										'zt_shijian' => TIMESTAMP,
										'uniacid'=>$_W['uniacid'],
										'er_id'=>$mid
										);
                                    pdo_update('yipinyimajilu', $dams, array(
                                        'id' => $mid
                                    ));

							}
							
					}else{
					
						//如果没有数据的话，就开始进行赠送。
					foreach(iunserializer($item['jifen']) as $key => $value ){
						if($sng>0){
							if( $sng == $value['itemid']){
								$items = $value;
							}
						}else{
								if($huiyuanid  > $value['itemid']){
								$items = $value;
							}
						}
					}
					if( empty($items) || !array($items)){
						return;
					}
					$id = $items['id'] ;
					$itemid = $items['itemid'];
					$jcs = $items['jifen'];
					$xjz = $items['xianjin'];
					$mks = $items['subcouponid'];
					$yhjms = $items['subcouponnum'];
								if ($jcs > 0) {
                                    $this->model->jifengodsd($openid, $jcs, $mid, $name,$erwzt,$phost);
                                    $damss = array(
                                        'openid' => $openid,
                                        'zs_xhao' => $this->model->get_sn(),
                                        'zs_name' => $name,
                                        'zs_time' => TIMESTAMP,
                                        'zs_shuliang' => $jcs,
                                        'uniacid' => $_W['uniacid'],
                                        'zs_leixing' => '0'
                                    );
                                    pdo_insert('yipinyimarizhi', $damss, array(
                                        'id' => $msk
                                    ));
                                }

                                if ($xjz > 0) {
                                    $this->model->yipinyuemmm($openid,$zjxz,$xjz,$mid,$name,$erwzt,$phost);
                                    $damss = array(
                                        'openid' => $openid,
                                        'zs_xhao' => $this->model->get_sn(),
                                        'zs_name' => $name,
                                        'zs_time' => TIMESTAMP,
                                        'zs_shuliang'=>$xjz,
                                        'zs_xianjin' => $zjxz,
                                        'uniacid' => $_W['uniacid']
                                    );
                                    pdo_insert('yipinyimarizhi', $damss, array(
                                        'id' => $msk
                                    ));
                                }

                            if ($mks > 0) {
                                $this->model->yipinCouponm($openid, $mks, $yhjms, $mid, $name,$erwzt, $phost);
                                $damss = array(
                                    'openid' => $openid,
                                    'zs_xhao' => $this->model->get_sn(),
                                    'zs_name' => $name,
                                    'zs_time' => TIMESTAMP,
                                    'zs_shuliang' => $yhjms,
                                    'uniacid' => $_W['uniacid'],
                                    'zs_leixing' => '1',
                                    'yhijd'=>$mks
                                );
                                pdo_insert('yipinyimarizhi', $damss, array(
                                    'id' => $msk
                                ));
                            }
						
					}
					include $this->template('index');
                   
                } else { //反之只能扫一次	

					foreach(iunserializer($item['jifen']) as $key => $value ){

						if($sng>0){
							if( $sng == $value['itemid']){
								$items = $value;
							}
						}else{
								if($huiyuanid  > $value['itemid']){
								$items = $value;
							}
						}
					}
					if( empty($items) || !array($items)){
						return;
					}
					$id = $items['id'] ;
					$itemid = $items['itemid'];
					$jcs = $items['jifen'];
					$xjz = $items['xianjin'];
					$mks = $items['subcouponid'];
					$yhjms = $items['subcouponnum'];	
                            //查询赠送状态，如果已经扫描了，将不会在扫描
							
                                if($erwzt>1){
                                    $erwzt++;
									$dams = array(
										'zt_status' => $erwzt,
										'openid' => $openid,
										'zt_name' => $name,
										'zt_phosh' => $phost,
										'zt_shijian' => TIMESTAMP,
										'uniacid'=>$_W['uniacid'],
										'er_id'=>$mid
										);
                                    pdo_update('yipinyimajilu', $dams, array(
                                        'id' => $mid
                                    ));
                                }else{
                                //查询积分如果有值就赠送积分，没有值就进入下一步
                                if ($jcs > 0) {
                                    $this->model->jifengods($openid, $jcs, $mid, $name,$erwzt,$phost);
                                    $damss = array(
                                        'openid' => $openid,
                                        'zs_xhao' => $this->model->get_sn(),
                                        'zs_name' => $name,
                                        'zs_time' => TIMESTAMP,
                                        'zs_shuliang' => $jcs,
                                        'uniacid' => $_W['uniacid'],
                                        'zs_leixing' => '0'
                                    );
                                    pdo_insert('yipinyimarizhi', $damss, array(
                                        'id' => $msk
                                    ));
                                }

                                if ($xjz > 0) {
                                    $this->model->yipinyuem($openid,$zjxz,$xjz,$mid,$name,$erwzt,$phost);
                                    $damss = array(
                                        'openid' => $openid,
                                        'zs_xhao' => $this->model->get_sn(),
                                        'zs_name' => $name,
                                        'zs_time' => TIMESTAMP,
                                        'zs_shuliang'=>$xjz,
                                        'zs_xianjin' => $zjxz,
                                        'uniacid' => $_W['uniacid']
                                    );
                                    pdo_insert('yipinyimarizhi', $damss, array(
                                        'id' => $msk
                                    ));
                                }

                            if ($mks > 0) {
                                $this->model->yipinCoupon($openid, $mks, $yhjms, $mid, $name,$erwzt, $phost);
                                $damss = array(
                                    'openid' => $openid,
                                    'zs_xhao' => $this->model->get_sn(),
                                    'zs_name' => $name,
                                    'zs_time' => TIMESTAMP,
                                    'zs_shuliang' => $yhjms,
                                    'uniacid' => $_W['uniacid'],
                                    'zs_leixing' => '1',
                                    'yhijd'=>$mks
                                );
                                pdo_insert('yipinyimarizhi', $damss, array(
                                    'id' => $msk
                                ));
                            }
                               }
                                include $this->template('index');
                    
                }
	}
	
    } else {
        $emsz = "您好，你的二维码已经失效，请换取一张";
        include $this->template('erweima');
    }
} else { //如果没有值，返回错误
    echo "您好,请您重新扫码，谢谢";
}



