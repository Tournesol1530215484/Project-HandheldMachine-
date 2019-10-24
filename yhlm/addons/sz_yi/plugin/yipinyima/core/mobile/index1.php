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
    $mer = $item['ms_status']; //二维码状态，0为生效 1为关闭
    $fxss = $item['ismnes']; //该字段支持只能扫描其他扫码失效 0为生效，1为关闭
    $yonj = $item['isbind']; //该变量用于判断绑定上下级关系 0为生效，1为关闭
    $zst = $item['jifenzt']; //查询表示不同身份扫描可以获得一次积分或现金或优惠券；off表示1个二维码只允许获得一次积分或现金或优惠券 0为no 1为off
    $fxs = iunserializer($item['roleid']); //拿出支持等级
    $smfh = $item['groupid']; //拿出赠送等级
    $jcs = $item['jifen']; //积分
    $yhjuan = $item['subcouponid']; //分销商id
    $yhjms = $item['subcouponnum']; //分销商张数
    $xjz = $item['xianjin']; //现金
    $zjxz = $item['paytype']; //拿出余额打入余额还是红包
    $mks = $item['subcouponid']; //优惠券id
    $erwzt = $item['yipin_status']; //扫描二维码状态，查询以及修改要用
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
    $phost = $saomiao['mobile'];
    //查出分销商信息
    $huiyuan = pdo_fetch('select * from' . tablename('sz_yi_member') . 'where uniacid=:uniacid and id=:id', array(
        ':uniacid' => $_W['uniacid'],
        ':id' =>$item['commjssionid']
    ));
    //拿出分销商id
    $mys = $huiyuan['id'];
    if ($mer == 0) {
        //两个同时开启就进入下一个，不是的话，就进入下一个
		if($fxss==0 && $yonj==0){
			print_r("你已经进来两个同时开启");exit;
		}else{
		 //进行上下级判断
        if($yonj==0){
            //扫描人进行判断，如果扫描人的上级为空，就自动添加是扫描人的上级
            $msl=$saomiao['agentid'];
            $sma = $saomiao['id'];
            $namese=$saomiao['nickname'];
            $usename=$huiyuan['nickname'];
            $sng = $saomiao['agentlevel'];
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
                if(!empty($result)){
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
                    print_r('111');
                } else { //反之只能扫一次
                        if (in_array($smfh,$fxs)) {
                            if ($smfh==$sng) {
                            //查询赠送状态，如果已经扫描了，将不会在扫描
                                if($erwzt>1){
                                    $erwzt++;
                                    $dams = array(
                                        'yipin_status'=>$erwzt,
                                        'openid' => $openid,
                                        'yipin_name' => $name,
                                        'yipin_phosh' => $phost,
                                        'yipin_shijian' => TIMESTAMP
                                    );
                                    pdo_update('yipinyimaerwei', $dams, array(
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
                        } else { //否则输出模板
                            //二维码状态如果是1就输出到模块
                          
                        }
                    }
                }
            }else{
				 $sng = $saomiao['agentlevel'];
                //如果扫描人已经有了上级，就自动进入下一步
                if($msl>0){
                    if ($zst == 0) {
                        print_r('111');
                    } else { //反之只能扫一次
                        if (in_array($smfh,$fxs)) {
                            if($smfh==$sng){
                                //查询赠送状态，如果已经扫描了，将不会在扫描
                                if($erwzt>1){
                                    $erwzt++;
                                    $dams = array(
                                        'yipin_status'=>$erwzt,
                                        'openid' => $openid,
                                        'yipin_name' => $name,
                                        'yipin_phosh' => $phost,
                                        'yipin_shijian' => TIMESTAMP
                                    );
                                    pdo_update('yipinyimaerwei', $dams, array(
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
                                } else { //否则输出模板
                                //二维码状态如果是1就输出到模块
                                include $this->template('index');

                                }

                        }
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
                    print_r('111');
                } else { //反之只能扫一次
                    if (in_array($smfh,$fxs)) {
                            if ($smfh==$sng) {
                            //查询赠送状态，如果已经扫描了，将不会在扫描
                                if($erwzt>1){
                                    $erwzt++;
                                    $dams = array(
                                        'yipin_status'=>$erwzt,
                                        'openid' => $openid,
                                        'yipin_name' => $name,
                                        'yipin_phosh' => $phost,
                                        'yipin_shijian' => TIMESTAMP
                                    );
                                    pdo_update('yipinyimaerwei', $dams, array(
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
                        } else { //否则输出模板
                            //二维码状态如果是1就输出到模块
                          
                        }
                    }
                }
			}else{
				print_r("输出一个错误");
			}
		}
	}
	//两个同时关闭的话，就拿出扫描者的等级进行赠送
	if($fxss==1 && $yonj==1){
			 $sng = $saomiao['agentlevel'];
				 if ($zst==0) {
                    print_r('111');
                } else { //反之只能扫一次
                    if (in_array($smfh,$fxs)) {
                            if ($smfh==$sng) {
                            //查询赠送状态，如果已经扫描了，将不会在扫描
                                if($erwzt>1){
                                    $erwzt++;
                                    $dams = array(
                                        'yipin_status'=>$erwzt,
                                        'openid' => $openid,
                                        'yipin_name' => $name,
                                        'yipin_phosh' => $phost,
                                        'yipin_shijian' => TIMESTAMP
                                    );
                                    pdo_update('yipinyimaerwei', $dams, array(
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
                        } else { //否则输出模板
                            //二维码状态如果是1就输出到模块
                          
                        }
                    }
                }
	}
	
    } else {
        $emsz = "您好，你的二维码已经失效，请换取一张";
        include $this->template('erweima');
    }
} else { //如果没有值，返回错误
    echo "您好,请您重新扫码，谢谢";
}



