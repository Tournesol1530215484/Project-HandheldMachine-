<?php
/**
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */

if (!defined('IN_IA')) {
    exit('Access Denied');
};
if (!class_exists('YipinyimaModel')) {
    class YipinyimaModel extends PluginModel
    {
        /**
         *  定时任务
         */
        function chasx(){
                //查询字段

            $yiid=pdo_fetchall('select id,pihao,uniacid,start,end from'.tablename('yipinyima'));
            return $yiid;
        }

        /**
             * 得到新的二维码号
             * @return  string
             */
            function get_sn()
            {
                /* 选择一个随机的方案 */
                mt_srand((double)microtime() * 1000000);

                return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            }

        /**
             * 生成二维码
             * @param type $data 访问地址
             * @param type $filename 图片文件地址
             * @param type $logo 图片中logo地址
             * @return string
             */


            function urlcode($data, $filename, $logo)
            {
                $dir = IA_ROOT;

                //$dir=str_replace('\\','/',realpath(dirname(dirname(dirname(__FILE__))).'/'));

                require_once(IA_ROOT . '/framework/library/qrcode/phpqrcode.php');
                if (empty($data) || empty($filename)) {
                    return $filename;
                }
                $filed = $dir . $filename;

                $size = '400x400';
                $errorCorrectionLevel = 'M'; // 纠错级别：L、M、Q、H
                $matrixPointSize = 10; // 点的大小：1到10
                if (!empty($filed)) {

                }
                if (@fopen($filed, 'r')) {
                    unlink($filed);
                }
                QRcode::png($data, $filed, $errorCorrectionLevel, $matrixPointSize);


                $QR = imagecreatefrompng($filed); //Warning: imagecreatefrompng() [function.imagecreatefrompng]: Unable to find the wrapper "https" - did you forget to enable it when you configured PHP? =
                //$QR = imagecreatefrompng('./chart.png');//外面那QR图
                if ($logo !== FALSE) {
                    $logo = imagecreatefromstring(file_get_contents($dir . $logo));
                    $QR_width = imagesx($QR);
                    $QR_height = imagesy($QR);
                    $logo_width = imagesx($logo);
                    $logo_height = imagesy($logo);
                    // Scale logo to fit in the QR Code
                    $logo_qr_width = $QR_width / 5;
                    $scale = $logo_width / $logo_qr_width;
                    $logo_qr_height = $logo_height / $scale;
                    $from_width = ($QR_width - $logo_qr_width) / 2;
                    imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);

                }
                return $filename;
            }
            /**
             * 多赠送余额
             *
             * @param
             * @return array 数组格式的返回结果
             */
            function yipinyuemmm($openid,$zjxz =0,$xjz =0,$msk =0,$name=0,$erwzt=0,$phost)
            {
                global $_W;

                $erwzt++;
                if ($zjxz == 0) {
                    //0为赠送到余额，反之赠送到微信钱包
                    m('member')->setCredit($openid, 'credit2', $xjz); //余额充值
                    $logno = m('common')->createNO('member_log', 'logno', 'RC');
                    $data = array(
                        'openid' => $openid,
                        'logno' => $logno,
                        'uniacid' => $_W['uniacid'],
                        'type' => '0',
                        'createtime' => TIMESTAMP,
                        'status' => '1',
                        'title' => '系统赠送',
                        'money' => $xjz,
                        'rechargetype' => 'system',
                    );
                    pdo_insert('sz_yi_member_log', $data);
                    $logid = pdo_insertid();
                    m('member')->setRechargeCredit($openid);
                    //赠送成功更新二维码状态
					$dams = array(
                    'zt_status' => $erwzt,
                    'openid' => $openid,
                    'zt_name' => $name,
                    'zt_phosh' => $phost,
                    'zt_shijian' => TIMESTAMP,
					'uniacid'=>$_W['uniacid'],
					'er_id'=>$msk,
					'yi_biaoshi'=>"2",
					);
					pdo_insert('yipinyimajilu', $dams);
				    $msg = array(
                    'first' => array(
                        'value' => "余额已经打入到您的账号",
                        "color" => "#4a5077"
                    ),
                    'keyword1' => array(
                        'title' => '赠送金额',
                        'value' => $xjz,
                        "color" => "#4a5077"
                    ),
                    'remark' => array(
                        'value' => "\r\n谢谢您对我们的支持！",
                        "color" => "#4a5077"
                    )
                );
                $detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
                m('message')->sendCustomNotice($openid, $msg, $detailurl);
                       
                } else {

                    $result = m('finance')->pay($openid, $zjxz, $xjz);
                    if (is_error($result)) {
                        if (strexists($result['message'], '系统繁忙')) {
                            $result = m('finance')->pay($openid, $zjxz, $xjz);
                            if (is_error($result)) {
                                message($result['message'], '', 'error');
                            }
                        }
                    }
					$dams = array(
                    'zt_status' => $erwzt,
                    'openid' => $openid,
                    'zt_name' => $name,
                    'zt_phosh' => $phost,
                    'zt_shijian' => TIMESTAMP,
					'uniacid'=>$_W['uniacid'],
					'er_id'=>$msk,
					'yi_biaoshi'=>"2",
					);
									    $msg = array(
                    'first' => array(
                        'value' => "现金已经打入你的微信钱包",
                        "color" => "#4a5077"
                    ),
                    'keyword1' => array(
                        'title' => '赠送金额',
                        'value' => $xjz,
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
            }
            /**
             * 赠送余额
             *
             * @param
             * @return array 数组格式的返回结果
             */
            function yipinyuem($openid,$zjxz =0,$xjz =0,$msk =0,$name=0,$erwzt=0,$phost)
            {
                global $_W;
				
                $erwzt++;
                if ($zjxz == 0) {
                    //0为赠送到余额，反之赠送到微信钱包
                    m('member')->setCredit($openid, 'credit2', $xjz); //余额充值
                    $logno = m('common')->createNO('member_log', 'logno', 'RC');
                    $data = array(
                        'openid' => $openid,
                        'logno' => $logno,
                        'uniacid' => $_W['uniacid'],
                        'type' => '0',
                        'createtime' => TIMESTAMP,
                        'status' => '1',
                        'title' => '系统赠送',
                        'money' => $xjz,
                        'rechargetype' => 'system',
                    );
                    pdo_insert('sz_yi_member_log', $data);
                    $logid = pdo_insertid();
                    m('member')->setRechargeCredit($openid);
                    //赠送成功更新二维码状态
					$dams = array(
                    'zt_status' => $erwzt,
                    'openid' => $openid,
                    'zt_name' => $name,
                    'zt_phosh' => $phost,
                    'zt_shijian' => TIMESTAMP,
					'uniacid'=>$_W['uniacid'],
					'er_id'=>$msk,
					'yi_biaoshi'=>"1",
					);
					pdo_insert('yipinyimajilu', $dams);
				    $msg = array(
                    'first' => array(
                        'value' => "余额已经打入到您的账号",
                        "color" => "#4a5077"
                    ),
                    'keyword1' => array(
                        'title' => '充值金额',
                        'value' => $xjz,
                        "color" => "#4a5077"
                    ),
                    'remark' => array(
                        'value' => "\r\n谢谢您对我们的支持！",
                        "color" => "#4a5077"
                    )
                );
                $detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
                m('message')->sendCustomNotice($openid, $msg, $detailurl);
                    
                } else {

                    $result = m('finance')->pay($openid, $zjxz, $xjz);
                    if (is_error($result)) {
                        if (strexists($result['message'], '系统繁忙')) {
                            $result = m('finance')->pay($openid, $zjxz, $xjz);
                            if (is_error($result)) {
                                message($result['message'], '', 'error');
                            }
                        }
                    }
					$dams = array(
                    'zt_status' => $erwzt,
                    'openid' => $openid,
                    'zt_name' => $name,
                    'zt_phosh' => $phost,
                    'zt_shijian' => TIMESTAMP,
					'uniacid'=>$_W['uniacid'],
					'er_id'=>$msk,
					'yi_biaoshi'=>"1",
					);
					pdo_insert('yipinyimajilu', $dams);
                }
            }

            /**
             * 赠送积分
             *
             * @param
             * @return array 数组格式的返回结果
             */
            function  jifengods($openid = '', $jcs = 0, $msk = 0, $name = 0,$erwzt=0, $phost = 0)
            {
				
                $erwzt++;
                global $_W;
					$dams = array(
                    'zt_status' => $erwzt,
                    'openid' => $openid,
                    'zt_name' => $name,
                    'zt_phosh' => $phost,
                    'zt_shijian' => TIMESTAMP,
					'uniacid'=>$_W['uniacid'],
					'er_id'=>$msk,
					'yi_biaoshi'=>"1",
					);
					pdo_insert('yipinyimajilu', $dams);
                m('member')->setCredit($openid, 'credit1', $jcs); //余额积分
                $msg = array(
                    'first' => array(
                        'value' => "赠送积分！",
                        "color" => "#4a5077"
                    ),
                    'keyword1' => array(
                        'title' => '积分赠送通知',
                        'value' => "积分赠送通知积分:" . $jcs . "积分!",
                        "color" => "#4a5077"
                    ),
                    'remark' => array(
                        'value' => "\r\n我们已为您赠送积分，请您登录个人中心查看。",
                        "color" => "#4a5077"
                    )
                );
                $detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
                m('message')->sendCustomNotice($openid, $msg, $detailurl);
            }
			  /**
             * 多次赠送积分
             *
             * @param
             * @return array 数组格式的返回结果
             */
            function  jifengodsd($openid = '', $jcs = 0, $msk = 0, $name = 0,$erwzt=0, $phost=0)
            {
                $erwzt++;
                global $_W;
					$dams = array(
                    'zt_status' => $erwzt,
                    'openid' => $openid,
                    'zt_name' => $name,
                    'zt_phosh' => $phost,
                    'zt_shijian' => TIMESTAMP,
					'uniacid'=>$_W['uniacid'],
					'er_id'=>$msk,
					'yi_biaoshi'=>"2",
					);
                pdo_insert('yipinyimajilu', $dams);
                m('member')->setCredit($openid, 'credit1', $jcs); //余额积分
                $msg = array(
                    'first' => array(
                        'value' => "赠送积分！",
                        "color" => "#4a5077"
                    ),
                    'keyword1' => array(
                        'title' => '积分赠送通知',
                        'value' => "积分赠送通知积分:" . $jcs . "积分!",
                        "color" => "#4a5077"
                    ),
                    'remark' => array(
                        'value' => "\r\n我们已为您赠送积分，请您登录个人中心查看。",
                        "color" => "#4a5077"
                    )
                );
                $detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
                m('message')->sendCustomNotice($openid, $msg, $detailurl);
            }
			  /**
             * 多赠送优惠券
             *
             * @param
             * @return array 数组格式的返回结果
             */
            function yipinCouponm($openid = '', $couponid = 0, $couponnum = 0,$msk = 0,$name=0,$erwzt=0,$phost)
            {
				
                global $_W;
                $erwzt++;
                $time = time();
                for ($i = 0; $i <= $couponnum; $i++) {
                    //赠送优惠券数量
                    $log = array(
                        'uniacid' => $_W['uniacid'],
                        'openid' => $openid,
                        'logno' => m('common')->createNO('coupon_log', 'logno', 'CC'),
                        'couponid' => $couponid,
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
                        'couponid' => $couponid,
                        'gettype' => 3,
                        'gettime' => $time,
                        'senduid' => $goodsid
                    );
				
                    pdo_insert('sz_yi_coupon_data', $data);

                }
					$dams = array(
                    'zt_status' => $erwzt,
                    'openid' => $openid,
                    'zt_name' => $name,
                    'zt_phosh' => $phost,
                    'zt_shijian' => TIMESTAMP,
					'uniacid'=>$_W['uniacid'],
					'er_id'=>$msk,
					'yi_biaoshi'=>"2",
					);
			 pdo_insert('yipinyimajilu', $dams);
                $msg = array(
                    'first' => array(
                        'value' => "赠送优惠券！",
                        "color" => "#4a5077"
                    ),
                    'keyword1' => array(
                        'title' => '优惠券赠送通知',
                        'value' => "优惠券赠送通知:" . $couponnum . "张!",
                        "color" => "#4a5077"
                    ),
                    'remark' => array(
                        'value' => "\r\n我们已为您赠送优惠券，请您登录个人中心查看。",
                        "color" => "#4a5077"
                    )
                );
                $detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
                m('message')->sendCustomNotice($openid, $msg, $detailurl);
                return $openid;
            }
            /**
             * 赠送优惠券
             *
             * @param
             * @return array 数组格式的返回结果
             */
            function yipinCoupon($openid = '', $couponid = 0, $couponnum = 0,$msk = 0,$name=0,$erwzt=0,$phost)
            {

                global $_W;
                $erwzt++;
                $time = time();
                for ($i = 0; $i <= $couponnum; $i++) {
                    //赠送优惠券数量
                    $log = array(
                        'uniacid' => $_W['uniacid'],
                        'openid' => $openid,
                        'logno' => m('common')->createNO('coupon_log', 'logno', 'CC'),
                        'couponid' => $couponid,
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
                        'couponid' => $couponid,
                        'gettype' => 3,
                        'gettime' => $time,
                        'senduid' => $goodsid
                    );
				
                    pdo_insert('sz_yi_coupon_data', $data);

                }
					$dams = array(
                    'zt_status' => $erwzt,
                    'openid' => $openid,
                    'zt_name' => $name,
                    'zt_phosh' => $phost,
                    'zt_shijian' => TIMESTAMP,
					'uniacid'=>$_W['uniacid'],
					'er_id'=>$msk,
					'yi_biaoshi'=>"1",
					);
					pdo_insert('yipinyimajilu', $dams);
                $msg = array(
                    'first' => array(
                        'value' => "赠送优惠券！",
                        "color" => "#4a5077"
                    ),
                    'keyword1' => array(
                        'title' => '优惠券赠送通知',
                        'value' => "优惠券赠送通知:" . $couponnum . "张!",
                        "color" => "#4a5077"
                    ),
                    'remark' => array(
                        'value' => "\r\n我们已为您赠送优惠券，请您登录个人中心查看。",
                        "color" => "#4a5077"
                    )
                );
                $detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
                m('message')->sendCustomNotice($openid, $msg, $detailurl);
                return $openid;
            }
        }

    }
