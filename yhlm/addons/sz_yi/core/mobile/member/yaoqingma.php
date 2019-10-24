<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
global $_W, $_GPC;
$openid = m('user') -> getOpenid();
$godg=$_GPC['godg'];
$member    = m("member")->getMember($openid);

$memberid=$member['id'];
if($godg=='fenxiang'){
	$shop_set = m('common')->getSysset(array('shop'));
	$pics=$shop_set['shop']['logo'];
	$guanggao=$shop_set['shop']['guanggao'];
	$fenxiangbiaoti=$shop_set['shop']['guanggao'].$member['id'].$member['mobile'];
	$_W['shopshare'] = array('title' => $fenxiangbiaoti, 'imgUrl' =>$img, 'desc' => $fenxiangbiaoti, 'link' => $this->createMobileUrl('member/yaoqingma',array(
	'msg'=>$guanggao,'mdk'=>$member['id'],'mobile'=>$member['mobile']
	)));
}
$shop_set = m('common')->getSysset(array('shop'));
$member    = m("member")->getMember($openid);
$memberid=$member['id'];
$mdk=$_GPC['mdk'];
$goods=pdo_fetch('select * from ' .tablename('sz_yi_member') . ' where 	agentid=:agentid and uniacid=:uniacid',array(
':uniacid'=>$_W['uniacid'],
':agentid'=>$mdk
));
if($goods){
	//直接跳回首页
	header('location: ' . $this->createMobileUrl('shop'));
	exit;
}
session_start();
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
//print_r($member);exit;
if ($_W['isajax']){
    if ($_W['ispost']){
		if($operation == "zhiyou"){
		$mobile = !empty($_GPC['yaoqingma']) ? $_GPC['yaoqingma'] : show_json(0, '邀请码不能为空不能为空！');
		//查询上级ID
		$ifon=pdo_fetch('select * from ' .tablename('sz_yi_member') . ' where openid=:openid and uniacid=:uniacid',array(
		':openid'=>$openid,
		':uniacid'=>$_W['uniacid']
		));
		if($ifon['agentid']==0){
			//如果为0的话，就可以更改
			//修改个人上级，其他的不可更改
		$member_data=array(
		'agentid'=>$mobile
		);
		
		//判断赠送，如果开就送，没有的话，就不送
		if($shop_set['shop']['jiangli']==0){
				exit;
		}else{
		//得到推荐人的openid
		$tuijianid=pdo_fetch('select * from ' .tablename('sz_yi_member') . ' where id=:id and uniacid=:uniacid',array(
		':id'=>$mobile,
		':uniacid'=>$_W['uniacid']
		));
		if(empty($tuijianid)){
			show_json(0,'此邀请码不存在，请重新获取');
		}
		//赠送开始
		//从推荐人开始
	
		if(!empty($shop_set['shop']['reccredit'])){
					//如果积分不为空的话，就送
					//转换值
					$integral=$shop_set['shop']['reccredit'];
					m('member')->setCredit($tuijianid['openid'], 'credit1', $integral); //余额积分
					//通知
				 $msg = array(
                    'first' => array(
                        'value' => "赠送积分！",
                        "color" => "#4a5077"
                    ),
                    'keyword1' => array(
                        'title' => '积分赠送通知',
                        'value' => "积分赠送通知积分:" . $integral . "积分!",
                        "color" => "#4a5077"
                    ),
                    'remark' => array(
                        'value' => "\r\n我们已为您赠送积分，请您登录个人中心查看。",
                        "color" => "#4a5077"
                    )
                );
                $detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
                m('message')->sendCustomNotice($tuijianid['openid'], $msg, $detailurl);
				}//积分结束
				
				if(!empty($shop_set['shop']['recmoney'])){
					//RMB
					$rmb=$shop_set['shop']['recmoney'];
					if($shop_set['shop']['paytype']==0){
						//为零的话，打入余额
						m('member')->setCredit($tuijianid['openid'], 'credit2', $rmb); //余额充值
						$logno = m('common')->createNO('member_log', 'logno', 'RC');
						$data = array(
                        'openid' => $tuijianid['openid'],
                        'logno' => $logno,
                        'uniacid' => $_W['uniacid'],
                        'type' => '0',
                        'createtime' => TIMESTAMP,
                        'status' => '1',
                        'title' => '系统赠送',
                        'money' => $rmb,
                        'rechargetype' => 'system',
						);
                    pdo_insert('sz_yi_member_log', $data);
					//写入通知
					$msg = array(
                            'first' => array(
                                'value' => "余额已经打入到您的账号!",
                                "color" => "#4a5077"
                            ),
                            'money' => array(
                                'title' => '充值金额',
                                'value' => $rmb,
                                "color" => "#4a5077"
                            ),
                            'product' => array(
                                'title' => '充值方式',
                                'value' => '系统赠送',
                                "color" => "#4a5077"
                            ),
                            'remark' => array(
                                'value' => "\r\n谢谢您对我们的支持！",
                                "color" => "#4a5077"
                            )
                        );
                        $detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
                        m('message')->sendCustomNotice($tuijianid['openid'], $msg, $detailurl);
					}else{
						$result = m('finance')->pay($tuijianid['openid'], $shop_set['shop']['paytype'], $rmb);
						if (is_error($result)) {
							if (strexists($result['message'], '系统繁忙')) {
								$result = m('finance')->pay($tuijianid['openid'], $shop_set['shop']['paytype'], $rmb);
								if (is_error($result)) {
                                message($result['message'], '', 'error');
								}
							}
						}
					}
				}//现金结束	
				if(!empty($shop_set['shop']['reccouponid'])){
					if(!empty($shop_set['shop']['reccouponnum'])){
						$zhangshu=$shop_set['shop']['reccouponnum'];
						$time = time();
						for ($i = 0; $i <= $zhangshu; $i++) {
							//赠送优惠券数量
							$log = array(
								'uniacid' => $_W['uniacid'],
								'openid' => $tuijianid['openid'],
								'logno' => m('common')->createNO('coupon_log', 'logno', 'CC'),
								'couponid' => $shop_set['shop']['reccouponid'],
								'status' => 1,
								'paystatus' => -1,
								'creditstatus' => -1,
								'createtime' => $time,
								'getfrom' => 0
							);
							pdo_insert('sz_yi_coupon_log', $log);
							$data = array(
								'uniacid' => $_W['uniacid'],
								'openid' => $tuijianid['openid'],
								'couponid' => $shop_set['shop']['reccouponid'],
								'gettype' => 3,
								'gettime' => $time,
								'senduid' => $goodsid
							);
							pdo_insert('sz_yi_coupon_data', $data);
							$msg = array(
								'first' => array(
									'value' => "赠送优惠券！",
									"color" => "#4a5077"
								),
								'keyword1' => array(
									'title' => '优惠券赠送通知',
									'value' => "优惠券赠送通知:" . $zhangshu . "张!",
									"color" => "#4a5077"
								),
								'remark' => array(
									'value' => "\r\n我们已为您赠送优惠券，请您登录个人中心查看。",
									"color" => "#4a5077"
								)
							);
							$detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
							m('message')->sendCustomNotice($tuijianid['openid'],$msg, $detailurl);
						}
					}
				}//优惠券结束
			//赠送被邀请者
					if(!empty($shop_set['shop']['subcredit'])){
					//如果积分不为空的话，就送
					//转换值
					$integrald=$shop_set['shop']['subcredit'];
					m('member')->setCredit($openid, 'credit1', $integrald); //余额积分
					//通知
				 $msg = array(
                    'first' => array(
                        'value' => "赠送积分！",
                        "color" => "#4a5077"
                    ),
                    'keyword1' => array(
                        'title' => '积分赠送通知',
                        'value' => "积分赠送通知积分:" . $integrald . "积分!",
                        "color" => "#4a5077"
                    ),
                    'remark' => array(
                        'value' => "\r\n我们已为您赠送积分，请您登录个人中心查看。",
                        "color" => "#4a5077"
                    )
                );
                $detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
                m('message')->sendCustomNotice($openid, $msg, $detailurl);
			  }//积分结束
			  if(!empty($shop_set['shop']['submoney'])){
						//RMB
						$rmbd=$shop_set['shop']['submoney'];
						if($shop_set['shop']['paytype']==0){
							//为零的话，打入余额
							m('member')->setCredit($openid, 'credit2', $rmbd); //余额充值
							$logno = m('common')->createNO('member_log', 'logno', 'RC');
							$data = array(
							'openid' => $openid,
							'logno' => $logno,
							'uniacid' => $_W['uniacid'],
							'type' => '0',
							'createtime' => TIMESTAMP,
							'status' => '1',
							'title' => '系统赠送',
							'money' => $rmbd,
							'rechargetype' => 'system',
							);
						pdo_insert('sz_yi_member_log', $data);
						//写入通知
						$msg = array(
								'first' => array(
									'value' => "余额已经打入到您的账号!",
									"color" => "#4a5077"
								),
								'money' => array(
									'title' => '充值金额',
									'value' => $rmbd,
									"color" => "#4a5077"
								),
								'product' => array(
									'title' => '充值方式',
									'value' => '系统赠送',
									"color" => "#4a5077"
								),
								'remark' => array(
									'value' => "\r\n谢谢您对我们的支持！",
									"color" => "#4a5077"
								)
							);
							$detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
							m('message')->sendCustomNotice($openid, $msg, $detailurl);
				}else{
							$result = m('finance')->pay($openid, $shop_set['shop']['paytype'], $rmbd);
							if (is_error($result)) {
								if (strexists($result['message'], '系统繁忙')) {
									$result = m('finance')->pay($openid, $shop_set['shop']['paytype'], $rmbd);
									if (is_error($result)) {
									message($result['message'], '', 'error');
									}
								}
							}
						}
			  }	//现金结束
				if(!empty($shop_set['shop']['subcouponid'])){
					if(!empty($shop_set['shop']['subcouponnum'])){
						$zhangshud=$shop_set['shop']['subcouponnum'];
						$time = time();
						for ($i = 0; $i <= $zhangshud; $i++) {
							//赠送优惠券数量
							$log = array(
								'uniacid' => $_W['uniacid'],
								'openid' => $openid,
								'logno' => m('common')->createNO('coupon_log', 'logno', 'CC'),
								'couponid' => $shop_set['shop']['subcouponid'],
								'status' => 1,
								'paystatus' => -1,
								'creditstatus' => -1,
								'createtime' => $time,
								'getfrom' => 0
							);
							pdo_insert('sz_yi_coupon_log', $log);
							$data = array(
								'uniacid' => $_W['uniacid'],
								'openid' => $openid,
								'couponid' => $shop_set['shop']['subcouponid'],
								'gettype' => 3,
								'gettime' => $time,
								'senduid' => $goodsid
							);
							pdo_insert('sz_yi_coupon_data', $data);
							$msg = array(
								'first' => array(
									'value' => "赠送优惠券！",
									"color" => "#4a5077"
								),
								'keyword1' => array(
									'title' => '优惠券赠送通知',
									'value' => "优惠券赠送通知:" . $zhangshud . "张!",
									"color" => "#4a5077"
								),
								'remark' => array(
									'value' => "\r\n我们已为您赠送优惠券，请您登录个人中心查看。",
									"color" => "#4a5077"
								)
							);
							$detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
							m('message')->sendCustomNotice($openid, $msg, $detailurl);
						}
					}
				}//优惠卷结束
		  }//判断是否开启
		$retu=pdo_update('sz_yi_member',$member_data, array('id' => $memberid, 'uniacid' => $_W['uniacid']));
		//修改成功后跳转
		//拼接链接，跳转页面
		$fenxianglianjie=$this->createMobileUrl('shop/index');
		show_json(1, $fenxianglianjie);
		}else{
			show_json(0,'抱歉，你已经有了上级，不可更改');
		}
		}else if($operation == 'dome'){
		
		$mobile = !empty($_GPC['mobile']) ? $_GPC['mobile'] : show_json(0, '手机号不能为空！');
		$mobiled = !empty($_GPC['yaoqingma']) ? $_GPC['yaoqingma'] : show_json(0, '邀请码不能为空！');
        $password = !empty($_GPC['password']) ? $_GPC['password'] : show_json(0, '密码不能为空！');
        $code = !empty($_GPC['code']) ? $_GPC['code'] : show_json(0, '验证码不能为空！');
         if(($_SESSION['codetime'] + 60 * 5) < time()){
            show_json(0, '验证码已过期,请重新获取');
        }
        if($_SESSION['code'] != $code){
            show_json(0, '验证码错误,请重新获取');
        }
        if($_SESSION['code_mobile'] != $mobile){
            show_json(0, '注册手机号与验证码不匹配！');
        }
        $member = pdo_fetch('select * from ' . tablename('sz_yi_member') . ' where mobile=:mobile and pwd!="" and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':mobile' => $mobile));
        if(!empty($member)){
            show_json(0, '该手机号已被注册！');
        }
		$sid = !empty( $_SESSION['sid'] )?$_SESSION['sid']:0;
				$ifon=pdo_fetch('select * from ' .tablename('sz_yi_member') . ' where openid=:openid and uniacid=:uniacid',array(
		':openid'=>$openid,
		':uniacid'=>$_W['uniacid']
		));
		if($ifon['agentid']==0){
			//如果为0的话，就可以更改
			//修改个人上级，其他的不可更改
           $member_data =array(
		   'pwd'=>md5($password),
		   'regtype'=>1,
		   'isbindmobile'=>1,
		   'agentid'=>$mobiled,
		   'mobile'=>$mobile
		   );
		
		//判断赠送，如果开就送，没有的话，就不送
		if($shop_set['shop']['jiangli']==0){
				exit;
		}else{
		//得到推荐人的openid
		$tuijianid=pdo_fetch('select * from ' .tablename('sz_yi_member') . ' where id=:id and uniacid=:uniacid',array(
		':id'=>$mobiled,
		':uniacid'=>$_W['uniacid']
		));
		if(empty($tuijianid)){
			show_json(0,'此邀请码不存在，请重新获取');
		}
		//赠送开始
		//从推荐人开始
	
		if(!empty($shop_set['shop']['reccredit'])){
					//如果积分不为空的话，就送
					//转换值
					$integral=$shop_set['shop']['reccredit'];
					m('member')->setCredit($tuijianid['openid'], 'credit1', $integral); //余额积分
					//通知
				 $msg = array(
                    'first' => array(
                        'value' => "赠送积分！",
                        "color" => "#4a5077"
                    ),
                    'keyword1' => array(
                        'title' => '积分赠送通知',
                        'value' => "积分赠送通知积分:" . $integral . "积分!",
                        "color" => "#4a5077"
                    ),
                    'remark' => array(
                        'value' => "\r\n我们已为您赠送积分，请您登录个人中心查看。",
                        "color" => "#4a5077"
                    )
                );
                $detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
                m('message')->sendCustomNotice($tuijianid['openid'], $msg, $detailurl);
				}//积分结束
				
				if(!empty($shop_set['shop']['recmoney'])){
					//RMB
					$rmb=$shop_set['shop']['recmoney'];
					if($shop_set['shop']['paytype']==0){
						//为零的话，打入余额
						m('member')->setCredit($tuijianid['openid'], 'credit2', $rmb); //余额充值
						$logno = m('common')->createNO('member_log', 'logno', 'RC');
						$data = array(
                        'openid' => $tuijianid['openid'],
                        'logno' => $logno,
                        'uniacid' => $_W['uniacid'],
                        'type' => '0',
                        'createtime' => TIMESTAMP,
                        'status' => '1',
                        'title' => '系统赠送',
                        'money' => $rmb,
                        'rechargetype' => 'system',
						);
                    pdo_insert('sz_yi_member_log', $data);
					//写入通知
					$msg = array(
                            'first' => array(
                                'value' => "余额已经打入到您的账号!",
                                "color" => "#4a5077"
                            ),
                            'money' => array(
                                'title' => '充值金额',
                                'value' => $rmb,
                                "color" => "#4a5077"
                            ),
                            'product' => array(
                                'title' => '充值方式',
                                'value' => '系统赠送',
                                "color" => "#4a5077"
                            ),
                            'remark' => array(
                                'value' => "\r\n谢谢您对我们的支持！",
                                "color" => "#4a5077"
                            )
                        );
                        $detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
                        m('message')->sendCustomNotice($tuijianid['openid'], $msg, $detailurl);
					}else{
						$result = m('finance')->pay($tuijianid['openid'], $shop_set['shop']['paytype'], $rmb);
						if (is_error($result)) {
							if (strexists($result['message'], '系统繁忙')) {
								$result = m('finance')->pay($tuijianid['openid'], $shop_set['shop']['paytype'], $rmb);
								if (is_error($result)) {
                                message($result['message'], '', 'error');
								}
							}
						}
					}
				}//现金结束	
				if(!empty($shop_set['shop']['reccouponid'])){
					if(!empty($shop_set['shop']['reccouponnum'])){
						$zhangshu=$shop_set['shop']['reccouponnum'];
						$time = time();
						for ($i = 0; $i <= $zhangshu; $i++) {
							//赠送优惠券数量
							$log = array(
								'uniacid' => $_W['uniacid'],
								'openid' => $tuijianid['openid'],
								'logno' => m('common')->createNO('coupon_log', 'logno', 'CC'),
								'couponid' => $shop_set['shop']['reccouponid'],
								'status' => 1,
								'paystatus' => -1,
								'creditstatus' => -1,
								'createtime' => $time,
								'getfrom' => 0
							);
							pdo_insert('sz_yi_coupon_log', $log);
							$data = array(
								'uniacid' => $_W['uniacid'],
								'openid' => $tuijianid['openid'],
								'couponid' => $shop_set['shop']['reccouponid'],
								'gettype' => 3,
								'gettime' => $time,
								'senduid' => $goodsid
							);
							pdo_insert('sz_yi_coupon_data', $data);
							$msg = array(
								'first' => array(
									'value' => "赠送优惠券！",
									"color" => "#4a5077"
								),
								'keyword1' => array(
									'title' => '优惠券赠送通知',
									'value' => "优惠券赠送通知:" . $zhangshu . "张!",
									"color" => "#4a5077"
								),
								'remark' => array(
									'value' => "\r\n我们已为您赠送优惠券，请您登录个人中心查看。",
									"color" => "#4a5077"
								)
							);
							$detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
							m('message')->sendCustomNotice($tuijianid['openid'],$msg, $detailurl);
						}
					}
				}//优惠券结束
			//赠送被邀请者
					if(!empty($shop_set['shop']['subcredit'])){
					//如果积分不为空的话，就送
					//转换值
					$integrald=$shop_set['shop']['subcredit'];
					m('member')->setCredit($openid, 'credit1', $integrald); //余额积分
					//通知
				 $msg = array(
                    'first' => array(
                        'value' => "赠送积分！",
                        "color" => "#4a5077"
                    ),
                    'keyword1' => array(
                        'title' => '积分赠送通知',
                        'value' => "积分赠送通知积分:" . $integrald . "积分!",
                        "color" => "#4a5077"
                    ),
                    'remark' => array(
                        'value' => "\r\n我们已为您赠送积分，请您登录个人中心查看。",
                        "color" => "#4a5077"
                    )
                );
                $detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
                m('message')->sendCustomNotice($openid, $msg, $detailurl);
			  }//积分结束
			  if(!empty($shop_set['shop']['submoney'])){
						//RMB
						$rmbd=$shop_set['shop']['submoney'];
						if($shop_set['shop']['paytype']==0){
							//为零的话，打入余额
							m('member')->setCredit($openid, 'credit2', $rmbd); //余额充值
							$logno = m('common')->createNO('member_log', 'logno', 'RC');
							$data = array(
							'openid' => $openid,
							'logno' => $logno,
							'uniacid' => $_W['uniacid'],
							'type' => '0',
							'createtime' => TIMESTAMP,
							'status' => '1',
							'title' => '系统赠送',
							'money' => $rmbd,
							'rechargetype' => 'system',
							);
						pdo_insert('sz_yi_member_log', $data);
						//写入通知
						$msg = array(
								'first' => array(
									'value' => "余额已经打入到您的账号!",
									"color" => "#4a5077"
								),
								'money' => array(
									'title' => '充值金额',
									'value' => $rmbd,
									"color" => "#4a5077"
								),
								'product' => array(
									'title' => '充值方式',
									'value' => '系统赠送',
									"color" => "#4a5077"
								),
								'remark' => array(
									'value' => "\r\n谢谢您对我们的支持！",
									"color" => "#4a5077"
								)
							);
							$detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
							m('message')->sendCustomNotice($openid, $msg, $detailurl);
				}else{
							$result = m('finance')->pay($openid, $shop_set['shop']['paytype'], $rmbd);
							if (is_error($result)) {
								if (strexists($result['message'], '系统繁忙')) {
									$result = m('finance')->pay($openid, $shop_set['shop']['paytype'], $rmbd);
									if (is_error($result)) {
									message($result['message'], '', 'error');
									}
								}
							}
						}
			  }	//现金结束
				if(!empty($shop_set['shop']['subcouponid'])){
					if(!empty($shop_set['shop']['subcouponnum'])){
						$zhangshud=$shop_set['shop']['subcouponnum'];
						$time = time();
						for ($i = 0; $i <= $zhangshud; $i++) {
							//赠送优惠券数量
							$log = array(
								'uniacid' => $_W['uniacid'],
								'openid' => $openid,
								'logno' => m('common')->createNO('coupon_log', 'logno', 'CC'),
								'couponid' => $shop_set['shop']['subcouponid'],
								'status' => 1,
								'paystatus' => -1,
								'creditstatus' => -1,
								'createtime' => $time,
								'getfrom' => 0
							);
							pdo_insert('sz_yi_coupon_log', $log);
							$data = array(
								'uniacid' => $_W['uniacid'],
								'openid' => $openid,
								'couponid' => $shop_set['shop']['subcouponid'],
								'gettype' => 3,
								'gettime' => $time,
								'senduid' => $goodsid
							);
							pdo_insert('sz_yi_coupon_data', $data);
							$msg = array(
								'first' => array(
									'value' => "赠送优惠券！",
									"color" => "#4a5077"
								),
								'keyword1' => array(
									'title' => '优惠券赠送通知',
									'value' => "优惠券赠送通知:" . $zhangshud . "张!",
									"color" => "#4a5077"
								),
								'remark' => array(
									'value' => "\r\n我们已为您赠送优惠券，请您登录个人中心查看。",
									"color" => "#4a5077"
								)
							);
							$detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
							m('message')->sendCustomNotice($openid, $msg, $detailurl);
						}
					}
				}//优惠卷结束
		  }//判断是否开启
		pdo_update('sz_yi_member',$member_data, array('id' => $memberid, 'uniacid' => $_W['uniacid']));	
		}else{
			show_json(0,'抱歉，你已经有了上级，不可更改');
		}
        $lifeTime = 24 * 3600 * 3;
        session_set_cookie_params($lifeTime);
        @session_start();
        $cookieid = "__cookie_sz_yi_userid_{$_W['uniacid']}";
        setcookie('member_mobile', $mobile);
        setcookie($cookieid, base64_encode($openid));
		//修改成功后跳转
		//拼接链接，跳转页面
		$fenxianglianjied=$this->createMobileUrl('shop/index');
		show_json(1,$fenxianglianjied);
		}
    }
}
include $this->template('member/yaoqingma');