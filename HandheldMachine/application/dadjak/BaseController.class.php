<?php
namespace App\Controller;
use Think\Controller;
class BaseController extends Controller {
	protected $_resp = array('code'=>'0','result'=>'');
	public function _initialize(){
		header("Content-type: text/html; charset=utf-8");
		$this->_post = $_REQUEST;
		if ( in_array(CONTROLLER_NAME, array('Base','Index')) || in_array(ACTION_NAME, array('Callpolice','appUpdate')) ) goto IGNORE;
//		if (!$this->encrypt()) {
//			$this->_resp['code']   = '9999';
//			$this->_resp['result'] = 'token验证失败';
//			$this->ajaxReturn($this->_resp);
//		}
		IGNORE:
	}
	

	/**
	 * 微信支付
	 * 接收支付结果通知参数
	 * @return Object 返回结果对象；
	 */
	public function getNotifyData(){
		$this->log("this  is my"."dula120");
		$postXml = $GLOBALS["HTTP_RAW_POST_DATA"];    // 接受通知参数；
		if (empty($postXml)) {
			return false;
		}
		$postObj = $this->xmlToArray($postXml);      // 调用解析方法，将xml数据解析成对象
		$this->log('app_id:'.$postObj['appid']);

		if ($postObj === false) {
			return false;
		}
		if (!empty($postObj['result_code'])) {
			if ($postObj['return_code'] == 'FAIL') {
				return false;
			}
		}

		$reply = "<xml>
                    <return_code><![CDATA[SUCCESS]]></return_code>
                    <return_msg><![CDATA[OK]]></return_msg>
                    </xml>";
		echo  $reply;      // 向微信后台返回结果。
		$nonce_str=$postObj['nonce_str'];
		$out_trade_no=$postObj['out_trade_no'];
		$trade=M("weipay_records")->where("nonce_str='$nonce_str' and out_trade_no='$out_trade_no'")->find();
		if($trade){
			$ve['appid'] = $postObj['appid'];
			$ve['mch_id'] = $postObj['mch_id'];
			$ve['device_info'] = $postObj['device_info'];
			// $ve['singn'] = $postObj['singn'];
			$ve['result_code'] = $postObj['result_code'];
			$ve['error_code'] = $postObj['error_code'];
			$ve['openid'] = $postObj['openid'];
			$ve['is_subscribe'] = $postObj['is_subscribe'];
			$ve['trade_type'] = $postObj['trade_type'];
			$ve['bank_type'] = $postObj['bank_type'];
			$ve['total_fee'] = $postObj['total_fee'];
			$ve['fee_type'] = $postObj['fee_type'];
			$ve['cash_fee'] = $postObj['cash_fee'];
			$ve['cash_fee_type'] = $postObj['cash_fee_type'];
			$ve['coupon_fee'] = $postObj['coupon_fee'];
			$ve['coupon_count'] = $postObj['coupon_count'];
			// $ve['coupon_id_$n'] = $postObj['coupon_id_$n'];
			// $ve['coupon_fee_$'] = $postObj['coupon_fee_$'];
			$ve['transaction_id'] = $postObj['transaction_id'];
			$ve['time_end'] = $postObj['time_end'];
			$this->log('transaction_id'.$ve['transaction_id']);

			//生成保单
			//1-8位
			$policy_ymd =  date('Y-m-d',strtotime($ve['time_end']));      //截取时间年月日
			$policy_payment = str_replace('-','',$policy_ymd);                  //去掉横线和空
			//9-14位
			$trade_infos=M('weipay_records')->where("out_trade_no='$out_trade_no'")->find();

			$policy_rfid=$trade_infos['rfid_area'];
			$real_num = substr(strval($policy_rfid+1000000),1,6);
			//14-22位
			$policy_rfid=$trade_infos['rfid'];
			$real_nu = substr(strval($policy_rfid+100000000),1,8);
			//23-25
			$type=$trade_infos['type'];
			if($type =='电瓶车'){
				$type='001';
			}else if($type =='摩托车'){
				$type='002';
			}
			//26-27
			$ins_type=$trade_infos['ins_type'];
			if($ins_type=='一年'){
				$ins_type='01';
			}else if($ins_type=='二年'){
				$ins_type='02';
			}else if($ins_type=='三年'){
				$ins_type='03';
			}
			//28-29
			$app_type='01';
			//30-32
			$identification='001';
			$ve['reserve_1'] =$policy_payment.$real_num.$real_nu.$type.$ins_type.$app_type.$identification;
			M('weipay_records')->where("nonce_str='$nonce_str' and out_trade_no='$out_trade_no'")->save($ve);
		}

		$trade_info=M('weipay_records')->where("out_trade_no='$out_trade_no' and transaction_id='".$postObj['transaction_id']."'")->find();
		if($trade_info['type']=='摩托车'){
			$trade_info['type']=10;
		}else{
			$trade_info['type']=1;
		}
		$regist_attr=M('dpc_regist')->where('type="'.$trade_info['type'].'" and rfid="'.$trade_info['rfid'].'" and rfid_area="'.$trade_info['rfid_area'].'" and cur_state=1')->find();
		$date = date("Y-m-d", time()); //当前时间
		$regist_attr['attr12']=trim($regist_attr['attr12']);
		if(empty($regist_attr['attr12'])){
			//attr11并结合fk_date计算有效期
			$a= "+" . $regist_attr['attr11'];//使用月数
			$create_date = $regist_attr['fk_date'];//创建时间
			$fk_time = date("Y-m-d", strtotime("$a month", strtotime($create_date)));//到期时间
			if(strtotime($fk_time)<=strtotime($date)){
				if($trade_info['ins_type']=="一年"){
					$sve['attr12']=date("Y-m-d", strtotime("+12 month", strtotime('+7 day',strtotime($date))));//到期时间
					$sve['attr11']=12;
				}else if($trade_info['ins_type']=="二年"){
					$sve['attr12']=date("Y-m-d", strtotime("+24 month", strtotime('+7 day',strtotime($date))));//到期时间
					$sve['attr11']=24;
				}else if($trade_info['ins_type']=="三年"){
					$sve['attr12']=date("Y-m-d", strtotime("+36 month", strtotime('+7 day',strtotime($date))));//到期时间
					$sve['attr11']=36;
				}
			}
			else if(strtotime($fk_time)>strtotime($date)){
				//服务未到期，如果是正式用户，那么就是在到期时间加上年限*12
				if($a>=12||$a==0){	//代表的是线下购买的正式用户
					if($trade_info['ins_type']=="一年"){
						$attr11=$regist_attr['attr11']+12;
						$b="+" . $attr11;
						$sve['attr12']=date("Y-m-d", strtotime("$b month", strtotime('+7 day',strtotime($create_date))));//到期时间
						$sve['attr11']=12;//+$regist_attr['attr11'];
					}else if($trade_info['ins_type']=="二年"){
						$attr11=$regist_attr['attr11']+24;
						$b="+" . $attr11;
						$sve['attr12']=date("Y-m-d", strtotime("$b month", strtotime('+7 day',strtotime($create_date))));//到期时间
						$sve['attr11']=24;//+$regist_attr['attr11'];
					}else if($trade_info['ins_type']=="三年"){
						$attr11=$regist_attr['attr11']+36;
						$b="+" . $attr11;
						$sve['attr12']=date("Y-m-d", strtotime("$b month", strtotime('+7 day',strtotime($create_date))));//到期时间
						$sve['attr11']=36;//+$regist_attr['attr11'];
					}

				}else{	//体验用户
					if($trade_info['ins_type']=="一年"){
						$sve['attr12']=date("Y-m-d", strtotime("+12 month", strtotime('+7 day',strtotime($date))));//到期时间
						$sve['attr11']=12;
					}else if($trade_info['ins_type']=="二年"){
						$sve['attr12']=date("Y-m-d", strtotime("+24 month", strtotime('+7 day',strtotime($date))));//到期时间
						$sve['attr11']=24;
					}else if($trade_info['ins_type']=="三年"){
						$sve['attr12']=date("Y-m-d", strtotime("+36 month", strtotime('+7 day',strtotime($date))));//到期时间
						$sve['attr11']=36;
					}
				}
			}

		}else if(!empty($regist_attr['attr12'])){
			if(strtotime($regist_attr['attr12'])<=strtotime($date)){
				if($trade_info['ins_type']=="一年"){
					$sve['attr12']=date("Y-m-d", strtotime("+12 month", strtotime('+7 day',strtotime($date))));//到期时间
					$sve['attr11']=12;
				}else if($trade_info['ins_type']=="二年"){
					$sve['attr12']=date("Y-m-d", strtotime("+24 month", strtotime('+7 day',strtotime($date))));//到期时间
					$sve['attr11']=24;
				}else if($trade_info['ins_type']=="三年"){
					$sve['attr12']=date("Y-m-d", strtotime("+36 month", strtotime('+7 day',strtotime($date))));//到期时间
					$sve['attr11']=36;
				}
			}else if(strtotime($regist_attr['attr12'])>strtotime($date)){
				if($trade_info['ins_type']=="一年"){
					$sve['attr12']=date("Y-m-d", strtotime("+12 month", strtotime($regist_attr['attr12'])));//到期时间
					$sve['attr11']=12;//+$regist_attr['attr11'];
				}else if($trade_info['ins_type']=="二年"){
					$sve['attr12']=date("Y-m-d", strtotime("+24 month", strtotime($regist_attr['attr12'])));//到期时间
					$sve['attr11']=24;//+$regist_attr['attr11'];
				}else if($trade_info['ins_type']=="三年"){
					$sve['attr12']=date("Y-m-d", strtotime("+36 month", strtotime($regist_attr['attr12'])));//到期时间
					$sve['attr11']=36;//+$regist_attr['attr11'];
				}
			}
		}
		if(empty($regist_attr['attr12'])){//第一次充值的开始时间和结束时间
//			$trade_a=strtotime($trade_info['time_end']);
//			$term_b=date('Y-m-d',strtotime("+7 day",$trade_a));
//			$term= $term_b."--".$sve['attr12'];
			//根据attr11和fk_date计算有效期
			$a= "+" . $regist_attr['attr11'];//使用月数
			$create_date = $regist_attr['fk_date'];//创建时间
			$fk_time = date("Y-m-d", strtotime("$a month", strtotime($create_date)));//到期时间
			if(strtotime($fk_time)<=strtotime($date)){//表示已经到期
				$trade_a=strtotime($trade_info['time_end']);
				$term_b=date('Y-m-d',strtotime("+7 day",$trade_a));
				$term= $term_b."--".$sve['attr12'];

			}else{
				//服务未到期
				if($a>=12||$a==0){//正式用户
					$trade_a=strtotime($fk_time);
					$term_b=date('Y-m-d',strtotime("+7 day",$trade_a));
					$term= $term_b."--".$sve['attr12'];

				}else{		//体验用户
					$trade_a=strtotime($trade_info['time_end']);
					$term_b=date('Y-m-d',strtotime("+7 day",$trade_a));
					$term= $term_b."--".$sve['attr12'];
				}
			}
		}else{//非第一次充值的开始和结束时间
			if(strtotime($regist_attr['attr12'])>strtotime($date)){//如果是在保险期内
				$term=date('Y-m-d',strtotime($regist_attr['attr12']))."--". $sve['attr12'];
			}else{//如果不是在保险期内
				$trade=strtotime('+7 day',strtotime($date));
				$termd=date("Y-m-d",$trade);
				$term=$termd."--". $sve['attr12'];
			}
//			$term=$regist_attr['attr12']."--". $sve['attr12'];
//			$this->log("保险到期时间是：".$term);
		}
		//M('weipay_records')->where("out_trade_no='$out_trade_no'")->setField('term',$term);
		$sql="update weipay_records set term='$term' WHERE out_trade_no='$out_trade_no'";
		$res=M('weipay_records')->query($sql);
		// $res=D('alipay_records')->query($sql);
		if($res){
			$this->log('termsuccess'.$term);
		}else{
			$this->log('termerror'.$term);
		}
		M('dpc_regist')->where('type="'.$trade_info['type'].'" and rfid="'.$trade_info['rfid'].'" and rfid_area="'.$trade_info['rfid_area'].'" and cur_state=1')->save($sve);
	}

	/**
	 * 微信支付
	 *  作用：将xml转为array
	 */
	public function xmlToArray($xml){
		//将XML转为array
		$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		return $array_data;
	}


	//支付宝回调方法及处理逻辑
	public  function  notify(){
		include('/data/www/anju/Web/alipay/aop/AopClient.php');
		$alipayconf = C('ALIPAY_CONF');
		$aop = new \AopClient;
		$aop->alipayrsaPublicKey = $alipayconf['public_key'];
		//此处验签方式必须与下单时的签名方式一致
		$flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
		$this->log("非黔西南支付宝返回的订单信息".$flag);
		//验签通过后再实现业务逻辑，比如修改订单表中的支付状态。
		/**
		 *  ①验签通过后核实如下参数out_trade_no、total_amount、seller_id
		 *  ②修改订单表,trade_no(支付宝交易号)，seller_id(商户账号)，charset,app_id,total_amount,timestamp(交易时间)
		 **/
		if($flag){
			$app_id=$_POST["app_id"];
			$out_trade_no=$_POST["out_trade_no"];
			$trade=M("alipay_records")->where("app_id='$app_id' and out_trade_no='$out_trade_no'")->find();
			if($trade){
				$ve['trade_no']=$_POST['trade_no'];      //支付宝交易号
				$ve['seller_id']   =$_POST['seller_id'];    //商户账号
				$ve['charset']     =$_POST['charset'];
				$ve['gmt_payment']   =$_POST['gmt_payment'];  //交易付款时间
				$ve['trade_status']=$_POST['trade_status']; //交易状态
				$ve['gmt_create']  =$_POST['gmt_create'];   //创建交易时间

				//1-8位
				$policy_ymd =  date('Y-m-d',strtotime($ve['gmt_payment']));      //截取时间年月日
				$policy_payment = str_replace('-','',$policy_ymd);               //去掉横线和空
				//9-14位
				$trade_info=M('alipay_records')->where("out_trade_no='$out_trade_no'")->find();
				$policy_rfid=$trade_info['rfid_area'];
				$real_num = substr(strval($policy_rfid+1000000),1,6);  //六位为 物品所属标签区域码，不足补零
				//14-22位
				$policy_rfid=$trade_info['rfid'];                      //八位为物品所属标签号，不足补零
				$real_nu = substr(strval($policy_rfid+100000000),1,8);
				//23-25
				$type=$trade_info['type'];    //三位保险类型：
				if($type =='电瓶车'){
					$type='001';
				}else if($type =='摩托车'){
					$type='002';
				}
				//26-27
				$ins_type=$trade_info['ins_type'];  //二位保险期限：
				if($ins_type=='一年'){
					$ins_type='01';
				}else if($ins_type=='二年'){
					$ins_type='02';
				}else if($ins_type=='三年'){
					$ins_type='03';
				}
				//28-29
				$app_type='01';       //01，代表手机APP购买；其它购买途径另行商定；
				//30-32
				$identification='001';//三位业务流水号：默认001，以后可以考虑做其他标识；
				$ve['reserve_1'] = $policy_payment.$real_num.$real_nu.$type.$ins_type.$app_type.$identification;
				M('alipay_records')->where("app_id='$app_id' and out_trade_no='$out_trade_no'")->save($ve);

				echo 'success';

				$trade_info=M('alipay_records')->where("out_trade_no='$out_trade_no'")->find();
				if($trade_info['type']=='摩托车'){
					$trade_info['type']=10;
				}else{
					$trade_info['type']=1;
				}
				$regist_attr=M('dpc_regist')->where('type="'.$trade_info['type'].'" and rfid="'.$trade_info['rfid'].'" and rfid_area="'.$trade_info['rfid_area'].'" and cur_state=1')->find();
				$date = date("Y-m-d", time()); //当前时间
				if(empty($regist_attr['attr12'])){
					//attr11并结合fk_date计算有效期
					$a= "+" . $regist_attr['attr11'];//使用月数
					$create_date = $regist_attr['fk_date'];//创建时间
					//$create_date =date("Y-m-d H:i:s",strtotime('+7 day',$regist_attr['fk_date']));
					$this->log('黔西南创建时间：'.$create_date);
					$fk_time = date("Y-m-d", strtotime("$a month", strtotime($create_date)));//到期时间

					if(strtotime($fk_time)<=strtotime($date)){
						if($trade_info['ins_type']=="一年"){
							$sve['attr12']=date("Y-m-d", strtotime("+12 month +7 day", time()));//到期时间
							$sve['attr11']=12;
						}else if($trade_info['ins_type']=="二年"){
							$sve['attr12']=date("Y-m-d", strtotime("+24 month +7 day", time()));//到期时间
							$sve['attr11']=24;
						}else if($trade_info['ins_type']=="三年"){
							$sve['attr12']=date("Y-m-d", strtotime("+36 month +7 day", time()));//到期时间
							$sve['attr11']=36;
						}
					}
					else if(strtotime($fk_time)>strtotime($date)){
						//服务未到期，如果是正式用户，那么就是在到期时间加上年限*12
						if($a>=12||$a==0){	//代表的是线下购买的正式用户
							if($trade_info['ins_type']=="一年"){
								$attr11=$regist_attr['attr11']+12;
								$b="+" . $attr11;
								$sve['attr12']=date("Y-m-d", strtotime("$b month", strtotime('+7 day',strtotime($create_date))));//到期时间
								$sve['attr11']=12;//+$regist_attr['attr11'];
							}else if($trade_info['ins_type']=="二年"){
								$attr11=$regist_attr['attr11']+24;
								$b="+" . $attr11;
								$sve['attr12']=date("Y-m-d", strtotime("$b month", strtotime('+7 day',strtotime($create_date))));//到期时间
								$sve['attr11']=24;//+$regist_attr['attr11'];
							}else if($trade_info['ins_type']=="三年"){
								$attr11=$regist_attr['attr11']+36;
								$b="+" . $attr11;
								$sve['attr12']=date("Y-m-d", strtotime("$b month", strtotime('+7 day',strtotime($create_date))));//到期时间
								$sve['attr11']=36;//+$regist_attr['attr11'];
							}

						}else{	//体验用户
							if($trade_info['ins_type']=="一年"){
								$sve['attr12']=date("Y-m-d", strtotime("+12 month", strtotime('+7 day',strtotime($date))));//到期时间
								$sve['attr11']=12;
							}else if($trade_info['ins_type']=="二年"){
								$sve['attr12']=date("Y-m-d", strtotime("+24 month", strtotime('+7 day',strtotime($date))));//到期时间
								$sve['attr11']=24;
							}else if($trade_info['ins_type']=="三年"){
								$sve['attr12']=date("Y-m-d", strtotime("+36 month", strtotime('+7 day',strtotime($date))));//到期时间
								$sve['attr11']=36;
							}
						}
					}

				}else if(!empty($regist_attr['attr12'])){
					if(strtotime($regist_attr['attr12'])<=strtotime($date)){
						if($trade_info['ins_type']=="一年"){
							$sve['attr12']=date("Y-m-d", strtotime("+12 month +7 day", time()));//到期时间
							$this->log("测试attr12：".$sve['attr12']);
							$sve['attr11']=12;
						}else if($trade_info['ins_type']=="二年"){
							$sve['attr12']=date("Y-m-d", strtotime("+24 month +7 day", time()));//到期时间
							$sve['attr11']=24;
						}else if($trade_info['ins_type']=="三年"){
							$sve['attr12']=date("Y-m-d", strtotime("+36 month +7 day", time()));//到期时间
							$sve['attr11']=36;
						}
					}else if(strtotime($regist_attr['attr12'])>strtotime($date)){
						if($trade_info['ins_type']=="一年"){
							$sve['attr12']=date("Y-m-d", strtotime("+12 month", strtotime($regist_attr['attr12'])));//到期时间
							$sve['attr11']=12;//+$regist_attr['attr11'];
						}else if($trade_info['ins_type']=="二年"){
							$sve['attr12']=date("Y-m-d", strtotime("+24 month", strtotime($regist_attr['attr12'])));//到期时间
							$sve['attr11']=24;//+$regist_attr['attr11'];
						}else if($trade_info['ins_type']=="三年"){
							$sve['attr12']=date("Y-m-d", strtotime("+36 month", strtotime($regist_attr['attr12'])));//到期时间
							$sve['attr11']=36;//+$regist_attr['attr11'];
						}
					}
				}
				if(empty($regist_attr['attr12'])){
//					$trade_a=strtotime($trade_info['gmt_payment']);
//					$term_b=date('Y-m-d',strtotime("+7 day",$trade_a));
//					$term= $term_b."--".$sve['attr12'];
					$a= "+" . $regist_attr['attr11'];//使用月数
					$create_date = $regist_attr['fk_date'];//创建时间
					$fk_time = date("Y-m-d", strtotime("$a month", strtotime($create_date)));//到期时间
					if(strtotime($fk_time)<=strtotime($date)){//表示已经到期
						$trade_a=strtotime($trade_info['gmt_payment']);
						$term_b=date('Y-m-d',strtotime("+7 day",$trade_a));
						$term= $term_b."--".$sve['attr12'];

					}else{
						//服务未到期
						if($a>=12||$a==0){//正式用户
							$trade_a=strtotime($fk_time);
							$term_b=date('Y-m-d',strtotime("+7 day",$trade_a));
							$term= $term_b."--".$sve['attr12'];

						}else{		//体验用户
							$trade_a=strtotime($trade_info['gmt_payment']);
							$term_b=date('Y-m-d',strtotime("+7 day",$trade_a));
							$term= $term_b."--".$sve['attr12'];
						}
					}
				}else{
					//判断当前时间是否大于过期时间
					if(strtotime($regist_attr['attr12'])>strtotime($date)){
						$term=date('Y-m-d',strtotime($regist_attr['attr12']))."--". $sve['attr12'];
					}else{
						$trade=strtotime('+7 day',time());
						$termd=date("Y-m-d",$trade);
						$term=$termd."--". $sve['attr12'];
					}
					//$term=$regist_attr['attr12']."--". $sve['attr12'];
				}
				//M('alipay_records')->where("out_trade_no='$out_trade_no'")->setField('term',$term);
				$sql="update alipay_records set term='$term' WHERE out_trade_no='$out_trade_no'";
				M('alipay_records')->query($sql);
				M('dpc_regist')->where('type="'.$trade_info['type'].'" and rfid="'.$trade_info['rfid'].'" and rfid_area="'.$trade_info['rfid_area'].'" and cur_state=1')->save($sve);

			}
		}else{
			echo 'failure';
		}
	}
	//黔西南支付宝回调方法及处理逻辑
	public  function  notify2(){
		include('/data/www/anju/Web/alipay/aop/AopClient.php');
		$alipayconf = C('ALIPAY_CONF_QIAN');
		$aop = new \AopClient;
		$aop->alipayrsaPublicKey = $alipayconf['public_key'];
		//此处验签方式必须与下单时的签名方式一致
		$flag = $aop->rsaCheckV1($_POST,NULL, "RSA2");
		$this->log("支付宝返回的订单信息".$flag);
		//验签通过后再实现业务逻辑，比如修改订单表中的支付状态。
		/**
		 *  ①验签通过后核实如下参数out_trade_no、total_amount、seller_id
		 *  ②修改订单表,trade_no(支付宝交易号)，seller_id(商户账号)，charset,app_id,total_amount,timestamp(交易时间)
		 **/
		if($flag){
			$app_id=$_POST["app_id"];
			$out_trade_no=$_POST["out_trade_no"];
			$trade=M("alipay_records")->where("app_id='$app_id' and out_trade_no='$out_trade_no'")->find();
			if($trade){
				$ve['trade_no']=$_POST['trade_no'];      //支付宝交易号
				$ve['seller_id']   =$_POST['seller_id'];    //商户账号
				$ve['charset']     =$_POST['charset'];
				$ve['gmt_payment']   =$_POST['gmt_payment'];  //交易付款时间
				$ve['trade_status']=$_POST['trade_status']; //交易状态
				$ve['gmt_create']  =$_POST['gmt_create'];   //创建交易时间

				//1-8位
				$policy_ymd =  date('Y-m-d',strtotime($ve['gmt_payment']));      //截取时间年月日
				$policy_payment = str_replace('-','',$policy_ymd);               //去掉横线和空
				//9-14位
				$trade_info=M('alipay_records')->where("out_trade_no='$out_trade_no'")->find();
				$policy_rfid=$trade_info['rfid_area'];
				$real_num = substr(strval($policy_rfid+1000000),1,6);  //六位为 物品所属标签区域码，不足补零
				//14-22位
				$policy_rfid=$trade_info['rfid'];                      //八位为物品所属标签号，不足补零
				$real_nu = substr(strval($policy_rfid+100000000),1,8);
				//23-25
				$type=$trade_info['type'];    //三位保险类型：
				if($type =='电瓶车'){
					$type='001';
				}else if($type =='摩托车'){
					$type='002';
				}
				//26-27
				$ins_type=$trade_info['ins_type'];  //二位保险期限：
				if($ins_type=='一年'){
					$ins_type='01';
				}else if($ins_type=='二年'){
					$ins_type='02';
				}else if($ins_type=='三年'){
					$ins_type='03';
				}
				//28-29
				$app_type='01';       //01，代表手机APP购买；其它购买途径另行商定；
				//30-32
				$identification='001';//三位业务流水号：默认001，以后可以考虑做其他标识；
				$ve['reserve_1'] = $policy_payment.$real_num.$real_nu.$type.$ins_type.$app_type.$identification;
				M('alipay_records')->where("app_id='$app_id' and out_trade_no='$out_trade_no'")->save($ve);

				echo 'success';

				$trade_info=M('alipay_records')->where("out_trade_no='$out_trade_no'")->find();
				if($trade_info['type']=='摩托车'){
					$trade_info['type']=10;
				}else{
					$trade_info['type']=1;
				}

				$regist_attr=M('dpc_regist')->where('type="'.$trade_info['type'].'" and rfid="'.$trade_info['rfid'].'" and rfid_area="'.$trade_info['rfid_area'].'" and cur_state=1')->find();
				$date = date("Y-m-d", time()); //当前时间
				$Arr_Qian=array(CITY_AREA_QXN01,CITY_AREA_QXN22,CITY_AREA_QXN23,CITY_AREA_QXN24,CITY_AREA_QXN25,CITY_AREA_QXN26,CITY_AREA_QXN27,CITY_AREA_QXN28,CITY_AREA_QXN29);
				$is_Qian=in_array($regist_attr['rfid_area'],$Arr_Qian);
				$this->log('是否是黔西南地区'.$is_Qian);
				if(empty($regist_attr['attr12'])){
					//attr11并结合fk_date计算有效期
					$a= "+" . $regist_attr['attr11'];//使用月数
					$create_date = $regist_attr['fk_date'];//创建时间
					$fk_time = date("Y-m-d", strtotime("$a month", strtotime($create_date)));//到期时间
					if(strtotime($fk_time)<=strtotime($date)){
						if($is_Qian){	//如果是黔西南，两天后生效
							if($trade_info['ins_type']=="一年"){
								$sve['attr12']=date("Y-m-d", strtotime("+12 month +2 day", time()));//到期时间
								$sve['attr11']=12;
							}else if($trade_info['ins_type']=="二年"){
								$sve['attr12']=date("Y-m-d", strtotime("+24 month +2 day", time()));//到期时间
								$sve['attr11']=24;
							}else if($trade_info['ins_type']=="三年"){
								$sve['attr12']=date("Y-m-d", strtotime("+36 month +2 day", time()));//到期时间
								$sve['attr11']=36;
							}
						}else{		//其他地区七天都生效
							if($trade_info['ins_type']=="一年"){
								$sve['attr12']=date("Y-m-d", strtotime("+12 month +7 day", time()));//到期时间
								$sve['attr11']=12;
							}else if($trade_info['ins_type']=="二年"){
								$sve['attr12']=date("Y-m-d", strtotime("+24 month +7 day", time()));//到期时间
								$sve['attr11']=24;
							}else if($trade_info['ins_type']=="三年"){
								$sve['attr12']=date("Y-m-d", strtotime("+36 month +7 day", time()));//到期时间
								$sve['attr11']=36;
							}
						}

					}
					else if(strtotime($fk_time)>strtotime($date)){
						//服务未到期，如果是正式用户，那么就是在到期时间加上年限*12

						if($is_Qian){	//黔西南地区，到期时间加天
							if($a>=12||$a==0){	//代表的是线下购买的正式用户
								if($trade_info['ins_type']=="一年"){
									$attr11=$regist_attr['attr11']+12;
									$b="+" . $attr11;
									$sve['attr12']=date("Y-m-d", strtotime("$b month", strtotime('+2 day',strtotime($create_date))));//到期时间
									$sve['attr11']=12;//+$regist_attr['attr11'];
								}else if($trade_info['ins_type']=="二年"){
									$attr11=$regist_attr['attr11']+24;
									$b="+" . $attr11;
									$sve['attr12']=date("Y-m-d", strtotime("$b month", strtotime('+2 day',strtotime($create_date))));//到期时间
									$sve['attr11']=24;//+$regist_attr['attr11'];
								}else if($trade_info['ins_type']=="三年"){
									$attr11=$regist_attr['attr11']+36;
									$b="+" . $attr11;
									$sve['attr12']=date("Y-m-d", strtotime("$b month", strtotime('+2 day',strtotime($create_date))));//到期时间
									$sve['attr11']=36;//+$regist_attr['attr11'];
								}

							}else{	//体验用户(实际没有)
								if($trade_info['ins_type']=="一年"){
									$sve['attr12']=date("Y-m-d", strtotime("+12 month", strtotime('+2 day',strtotime($date))));//到期时间
									$sve['attr11']=12;
								}else if($trade_info['ins_type']=="二年"){
									$sve['attr12']=date("Y-m-d", strtotime("+24 month", strtotime('+2 day',strtotime($date))));//到期时间
									$sve['attr11']=24;
								}else if($trade_info['ins_type']=="三年"){
									$sve['attr12']=date("Y-m-d", strtotime("+36 month", strtotime('+2 day',strtotime($date))));//到期时间
									$sve['attr11']=36;
								}
							}
						}else{		//其他地区时间加7天
							if($a>=12||$a==0){	//代表的是线下购买的正式用户
								if($trade_info['ins_type']=="一年"){
									$attr11=$regist_attr['attr11']+12;
									$b="+" . $attr11;
									$sve['attr12']=date("Y-m-d", strtotime("$b month", strtotime('+7 day',strtotime($create_date))));//到期时间
									$sve['attr11']=12;//+$regist_attr['attr11'];
								}else if($trade_info['ins_type']=="二年"){
									$attr11=$regist_attr['attr11']+24;
									$b="+" . $attr11;
									$sve['attr12']=date("Y-m-d", strtotime("$b month", strtotime('+7 day',strtotime($create_date))));//到期时间
									$sve['attr11']=24;//+$regist_attr['attr11'];
								}else if($trade_info['ins_type']=="三年"){
									$attr11=$regist_attr['attr11']+36;
									$b="+" . $attr11;
									$sve['attr12']=date("Y-m-d", strtotime("$b month", strtotime('+7 day',strtotime($create_date))));//到期时间
									$sve['attr11']=36;//+$regist_attr['attr11'];
								}

							}else{	//体验用户
								if($trade_info['ins_type']=="一年"){
									$sve['attr12']=date("Y-m-d", strtotime("+12 month", strtotime('+7 day',strtotime($date))));//到期时间
									$sve['attr11']=12;
								}else if($trade_info['ins_type']=="二年"){
									$sve['attr12']=date("Y-m-d", strtotime("+24 month", strtotime('+7 day',strtotime($date))));//到期时间
									$sve['attr11']=24;
								}else if($trade_info['ins_type']=="三年"){
									$sve['attr12']=date("Y-m-d", strtotime("+36 month", strtotime('+7 day',strtotime($date))));//到期时间
									$sve['attr11']=36;
								}
							}
						}

					}

				}else if(!empty($regist_attr['attr12'])){
					if($is_Qian){		//线上充值，黔西南地区加两天
						if(strtotime($regist_attr['attr12'])<=strtotime($date)){
							if($trade_info['ins_type']=="一年"){
								$sve['attr12']=date("Y-m-d", strtotime("+12 month +2 day", time()));//到期时间
								$sve['attr11']=12;
							}else if($trade_info['ins_type']=="二年"){
								$sve['attr12']=date("Y-m-d", strtotime("+24 month +2 day", time()));//到期时间
								$sve['attr11']=24;
							}else if($trade_info['ins_type']=="三年"){
								$sve['attr12']=date("Y-m-d", strtotime("+36 month +2 day", time()));//到期时间
								$sve['attr11']=36;
							}
						}else if(strtotime($regist_attr['attr12'])>strtotime($date)){
							if($trade_info['ins_type']=="一年"){
								$sve['attr12']=date("Y-m-d", strtotime("+12 month", strtotime($regist_attr['attr12'])));//到期时间
								$sve['attr11']=12;//+$regist_attr['attr11'];
							}else if($trade_info['ins_type']=="二年"){
								$sve['attr12']=date("Y-m-d", strtotime("+24 month", strtotime($regist_attr['attr12'])));//到期时间
								$sve['attr11']=24;//+$regist_attr['attr11'];
							}else if($trade_info['ins_type']=="三年"){
								$sve['attr12']=date("Y-m-d", strtotime("+36 month", strtotime($regist_attr['attr12'])));//到期时间
								$sve['attr11']=36;//+$regist_attr['attr11'];
							}
						}
					}else{		//线上充值，其他地区加7天
						if(strtotime($regist_attr['attr12'])<=strtotime($date)){
							if($trade_info['ins_type']=="一年"){
								$sve['attr12']=date("Y-m-d", strtotime("+12 month +7 day", time()));//到期时间
								$this->log("测试attr12：".$sve['attr12']);
								$sve['attr11']=12;
							}else if($trade_info['ins_type']=="二年"){
								$sve['attr12']=date("Y-m-d", strtotime("+24 month +7 day", time()));//到期时间
								$sve['attr11']=24;
							}else if($trade_info['ins_type']=="三年"){
								$sve['attr12']=date("Y-m-d", strtotime("+36 month +7 day", time()));//到期时间
								$sve['attr11']=36;
							}
						}else if(strtotime($regist_attr['attr12'])>strtotime($date)){
							if($trade_info['ins_type']=="一年"){
								$sve['attr12']=date("Y-m-d", strtotime("+12 month", strtotime($regist_attr['attr12'])));//到期时间
								$sve['attr11']=12;//+$regist_attr['attr11'];
							}else if($trade_info['ins_type']=="二年"){
								$sve['attr12']=date("Y-m-d", strtotime("+24 month", strtotime($regist_attr['attr12'])));//到期时间
								$sve['attr11']=24;//+$regist_attr['attr11'];
							}else if($trade_info['ins_type']=="三年"){
								$sve['attr12']=date("Y-m-d", strtotime("+36 month", strtotime($regist_attr['attr12'])));//到期时间
								$sve['attr11']=36;//+$regist_attr['attr11'];
							}
						}
					}

				}
				if(empty($regist_attr['attr12'])){
//					$trade_a=strtotime($trade_info['gmt_payment']);
//					$term_b=date('Y-m-d',strtotime("+7 day",$trade_a));
//					$term= $term_b."--".$sve['attr12'];
					$a= "+" . $regist_attr['attr11'];//使用月数
					$create_date = $regist_attr['fk_date'];//创建时间
					$fk_time = date("Y-m-d", strtotime("$a month", strtotime($create_date)));//到期时间
					if(strtotime($fk_time)<=strtotime($date)){//表示已经到期
						if($is_Qian){	//黔西南地区，加2天
							$trade_a=strtotime($trade_info['gmt_payment']);
							$term_b=date('Y-m-d',strtotime("+2 day",$trade_a));
							$term= $term_b."--".$sve['attr12'];
						}else{		//其他地区，加7天
							$trade_a=strtotime($trade_info['gmt_payment']);
							$term_b=date('Y-m-d',strtotime("+7 day",$trade_a));
							$term= $term_b."--".$sve['attr12'];
						}
					}else{
						if($is_Qian){	//黔西南地区指甲两天
							//服务未到期
							if($a>=12||$a==0){//正式用户
								$trade_a=strtotime($fk_time);
								$term_b=date('Y-m-d',strtotime("+2 day",$trade_a));
								$term= $term_b."--".$sve['attr12'];

							}else{		//体验用户（实际情况没有这个）
								$trade_a=strtotime($trade_info['gmt_payment']);
								$term_b=date('Y-m-d',strtotime("+2 day",$trade_a));
								$term= $term_b."--".$sve['attr12'];
							}
						}else{	//其他地区加7天
							//服务未到期
							if($a>=12||$a==0){//正式用户
								$trade_a=strtotime($fk_time);
								$term_b=date('Y-m-d',strtotime("+7 day",$trade_a));
								$term= $term_b."--".$sve['attr12'];

							}else{		//体验用户
								$trade_a=strtotime($trade_info['gmt_payment']);
								$term_b=date('Y-m-d',strtotime("+7 day",$trade_a));
								$term= $term_b."--".$sve['attr12'];
							}
						}
					}
				}else{
					if(strtotime($regist_attr['attr12'])>strtotime($date)){
						$term=date('Y-m-d',strtotime($regist_attr['attr12']))."--". $sve['attr12'];
					}else{
						if($is_Qian){	//黔西南地区指甲两天
							$trade=strtotime('+2 day',time());
							$termd=date("Y-m-d",$trade);
							$term=$termd."--". $sve['attr12'];
						}else{		//其他地区加7天
							$trade=strtotime('+7 day',time());
							$termd=date("Y-m-d",$trade);
							$term=$termd."--". $sve['attr12'];
						}
					}
					//$term=$regist_attr['attr12']."--". $sve['attr12'];
				}

				//M('alipay_records')->where("out_trade_no='$out_trade_no'")->setField('term',$term);
				$sql="update alipay_records set term='$term' WHERE out_trade_no='$out_trade_no'";
				//M('alipay_records')->query($sql);
				M('alipay_records')->query($sql);
				M('dpc_regist')->where('type="'.$trade_info['type'].'" and rfid="'.$trade_info['rfid'].'" and rfid_area="'.$trade_info['rfid_area'].'" and cur_state=1')->save($sve);

			}
		}else{
			echo 'failure';
		}
	}



	/*
	 * 验证token
	 */
	protected function encrypt(){
		$token = $this->_post['token'];
		if (empty($token)) {
			return false;
		}
		$array = array();
		foreach ($this->_post as $key => $val) {
			if ($key != 'token' && $key != 'PHPSESSID') {
				$array[$key] = $val;
			}
		}
		if ($array) {
			ksort($array);
			$str = '';
			foreach ($array as $key => $val) {
				$str .= $key.'='.$val;
				$str .= '&';
			}
			$str .= 'key='.ENCRYPT_KEY;
			$key = sha1($str);
			if ($key == $token) {
				return true;
			}
		}
		return false;
	}


	/*
	 * 发送短信
	 */
//	protected function toMesage($mobile,$content){
//		$account   = MSG_ACCOUNT;
//		$password  = MSG_PASSWORD;
//		$target    = "http://121.199.16.178/webservice/sms.php?method=Submit";
//	    $post_data = "account=$account&password=$password&mobile=$mobile&content=".rawurlencode($content);
//	    $gets      = xml_to_array(Post($post_data, $target));
//	    $message   = $gets['SubmitResult']['msg'];
//	    return $message;
//	}




	/**
	 * 云通讯短信验证码发送
	 * @param $to   短信接收者，做多200条
	 * @param array $datas  参数信息
	 * @param string $tempId        模板id 358203
	 */


	public function sendSMS($to,$datas=array(),$tempId=''){
		$accountSid= '8aaf070866235bc501668a50cffb3d8e';
		$accountToken= 'a4d8e112625847c4b19b1358e98fdad7';
		$appId='8aaf070866235bc501668a50d05c3d94';
		$serverIP='app.cloopen.com';
		$serverPort='8883';
		$softVersion='2013-12-26';
		$tempId='468443';       //模板id
		//$tempId='459090';       //模板id
		$datas=array($datas);
		include("/data/www/anju/Web/yuntongxun/CCPRestSmsSDK.php");
		$rest = new \REST($serverIP,$serverPort,$softVersion);
		$rest->setAccount($accountSid,$accountToken);
		$rest->setAppId($appId);
		$result = $rest->sendSMS($to,$datas,$tempId);
		if($result == NULL ) {
			$this->_resp['code'] = '1';
			$this->_resp['result'] = 'error';
			return json_encode( $this->_resp);
		}
		if($result->statusCode!=0) {
			$this->_resp['code'] = '1';
			$this->_resp['result'] ='error';
			return json_encode($this->_resp);
		}else{
			$this->_resp['code'] = '0';
			$this->_resp['result'] ='success';
			// 获取返回信息
			return json_encode($this->_resp);
		}
	}


	/**
	 * 校验必要参数
	 * @author: Simon
	 * @name  : checkNecessaryParams
	 * @access: protected
	 * @param : array||string $paramName
	 * @param : string $type='JSON'
	 * @see	  : index(PreAuthNewAction)
	 * @return: string
	 */
	protected function checkNecessaryParams($paramName, $type='JSON'){
		if (!is_array($paramName)){
			$paramName=explode(",", $paramName);
		}
		foreach ($paramName as $key=>$val){
			if (is_null($_REQUEST[$val]) || $_REQUEST[$val] == '' ){
				if ($type == 'JSON'){
					$this->_resp['code'] = '0002';
					$this->_resp['result'] = "缺少参数:".$val;
					$this->ajaxReturn($this->_resp);
				}else{
					die("缺少参数:".$val);
				}
			}
		}
	}


	//jsonp格式
	protected function outputs(){
		global $API_APP_LOGS_EXCLUDE;
		$REQUEST_METHOD = CONTROLLER_NAME.'/'.ACTION_NAME;
		if (!in_array($REQUEST_METHOD, $API_APP_LOGS_EXCLUDE)){
			if ($_SERVER["PATH_INFO"] != '/Base/viewLogs'){
				$strLog = date("Y-m-d H:i:s");
				$strLog .= " ".$_SERVER['REQUEST_METHOD'];
				$strLog .= " ".$_SERVER['PHP_SELF']."\r\n";
				$strLog .= "--".http_build_query($this->_post);
				$this->log($strLog, true);
			}
			$this->log(json_encode($this->_resp), true);


		}
		die(callback.'('.json_encode($this->_resp).')');
	}


	protected function output(){
		global $API_APP_LOGS_EXCLUDE;
		$REQUEST_METHOD = CONTROLLER_NAME.'/'.ACTION_NAME;
		if (!in_array($REQUEST_METHOD, $API_APP_LOGS_EXCLUDE)){
			if ($_SERVER["PATH_INFO"] != '/Base/viewLogs'){
				$strLog = date("Y-m-d H:i:s");
				$strLog .= " ".$_SERVER['REQUEST_METHOD'];
				$strLog .= " ".$_SERVER['PHP_SELF']."\r\n";
				$strLog .= "--".http_build_query($this->_post);
				$this->log($strLog, true);
			}
			$this->log(json_encode($this->_resp), true);
		}
		die(json_encode($this->_resp));
	}


	/**
	 * 记录日志
	 * @author: Simon
	 * @name  : log
	 * @access: protected
	 * @param : array||string $content
	 * @param : boolean $force
	 * @return: void
	 */
	protected function log($content, $force=false){
		if (is_array($content)) $content = json_encode($content);
		if (CUSTOM_APP_LOGS || $force){
			$handle = fopen("./logs/".date("Y-m-d").".txt","a+");
			if ($handle){
				fwrite($handle,$content."\r\n\r\n");
				fclose($handle);
			}
		}
	}


	public function viewLogs(){
		echo "<a href='clearLogs.html' />清除当天日志</a><br />";
		$content = file_get_contents("./logs/".date("Y-m-d").".txt");
// 		$content = file_get_contents("./logs/2016-09-06.txt");
		echo nl2br($content);
	}

	public function clearLogs(){
		header("Content-type: text/html; charset=utf-8");
		echo "<a href='viewLogs.html' />查看当天日志</a><br />";
		unlink("./logs/".date("Y-m-d").".txt");
		$this->redirect('/App/Base/viewLogs');
	}


	protected function uploadPhotos($id, $type,$newname = ""){
		$upload = new \Think\UploadFile();		// 实例化上传类
		$upload->maxSize   		= 20971520 ;		// 设置附件上传大小
		$upload->allowExts 		= array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath  		= getPicPath($id,$type,0); // 设置附件上传目录
// 		$upload->savePath 		= "http://anju.zwtapp.win:83/anju/Web/Uploads/car";
		$upload->thumb          = true; 		//是不是对上传文件进行缩略图处置
		$upload->thumbMaxWidth  = '50,200'; 	//缩略图处置宽度
		$upload->thumbMaxHeight = '60,240'; 	//缩略图处置高度
		$upload->thumbPrefix    = 'm_,s_';  	//2张缩略图
		$upload->thumbPath      = getPicPath($id,$type,0); //缩略图保留路径
// 		$upload->thumbPath 		= "http://test.zwtweb.win:82/uploadfile/photo/";
		$upload->thumbType = 0;  // 缩略图生成方式 1 按设置大小截取 0 按原图等比例缩略
		$upload->thumbRemoveOrigin = false; 	//上传图片后删除原图片
		if(!empty($newname)){
			$upload->saveRule = $newname;
		}
		// 上传文件
		if(!$upload->upload()) {
			$this->log("图片上传信息:".$upload->getErrorMsg());
			return false;

		}
		return $upload->getUploadFileInfo();
	}

	protected function addRecord($mobile, $rfid,$rfid_area,$type){
		$rid = M("AppRegist")->where("mobile = '$mobile'")->getField("id");

		$data['app_rid'] = $rid;
		$data['rfid'] = $rfid;
		$data['rfid_area'] = $rfid_area;
		$data['createtime'] = date("Y-m-d H:i:s",time());
		$data['type'] = $type;
		$id = M("AppRecord")->data($data)->add();
		if(empty($id)){
			$this->_resp['code']   = '-999';
			$this->_resp['result'] = '操作记录失败';
			$this->output();
		}
	}

	//把时间戳转换成多少天多少小时
	protected	function run_time($consume){

		$str = "";
		if($consume >= 86400){
			$str = floor($consume / 86400) . "";
			$consume = $consume % 86400;
		}
		if($consume >= 3600){
			$str .= floor($consume / 3600) . ":";
			$consume = $consume % 3600;
		}else{
			unset($zero);
			//$str .= "0:";
		}
		if($consume >= 60){
			$str .= floor($consume / 60) . ":";
			$consume = $consume % 60;
		}else{
			unset($zero);
			//$str .= "0:";
		}
		if($consume > 0){
			$str .= $consume;
		}elseif($str == ""){
			//$str = "0";
		}
		return $str;
	}



	/**
	 ** app操作记录
	 ** @param $mobile
	 **/
	protected function app_getRecord($mobile){
		$rid = M("AppRegist")->where("mobile = '$mobile'")->find();
		$add['name']=$rid['name'];
		$add['mobile']=$rid['mobile'];
		$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING'];
		$add['api']= $url;
		$add['time']=date('Y-m-d H:i:s');
		$sql="insert into app_getRecord VALUES (null,'".$add['name']."','".$add['mobile']."',0,0,'".$add['api']."','".$add['time']."')";
		mysql_query($sql);
	}


	/**
	 *
	 * 丢失报警记录
	 * */
	protected function add_Lostbike($mobile,$rfid,$rfid_area,$cur_state){
		$reporter_info=M('AppRegist')->where('mobile="'.$mobile.'"')->find();
		$regist_info = M('dpc_regist')->where('rfid="'.$rfid.'" and rfid_area="'.$rfid_area.'" and cur_state=1')->find();
		$data['reporter_name']=$reporter_info['name'];//报案人姓名
		$data['reporter_id']=$reporter_info['id_code'];//报案人身份证
		$data['lost_date']=date("Y-m-d H:i:s",time());//丢失日期
		$data['report_date']=date("Y-m-d H:i:s",time());//报案日期
		$data['owner_name']=$regist_info['owner_name'];//车主人姓名
		$data['owner_id']=$regist_info['owner_id'];//车主人身份证
		$data['bike_type']=$regist_info['attr4'];//电瓶车类型、型号、品牌等
		$data['bike_color']=$regist_info['attr3'];//电瓶车颜色
		$data['bike_code'] = $regist_info['attr1'];//电瓶车牌号
		$data['rfid']=$regist_info['rfid'];
		$data['rfid_area']=$regist_info['rfid_area'];
		$data['phone']=$regist_info['owner_phone1'];//联络电话
		$data['cur_state']=$cur_state;
		$data['is_regist']=1;
		$id=M("dpc_lostbike")->data($data)->add();
		if(empty($id)){
			$this->_resp['code']   = '-999';
			$this->_resp['result'] = '操作失败';
			$this->output();
		}
	}


	/*丢失车辆
    ram $mobile
    * @param $rfid
    * @param $rfid_area
    * */
	protected function Missing_vehicle($mobile,$rfid,$rfid_area){
		$reporter_info=M('AppRegist')->where('mobile="'.$mobile.'"')->find();
		$regist_info = M('dpc_regist')->where('rfid="'.$rfid.'" and rfid_area="'.$rfid_area.'"')->find();
		$data['reporter_name']=$reporter_info['name'];//报案人姓名
		$data['reporter_id']=$reporter_info['id_code'];//报案人身份证
		$data['lost_date']=date("Y-m-d H:i:s",time());//丢失日期
		$data['report_date']=date("Y-m-d H:i:s",time());//报案日期
		$data['owner_name']=$regist_info['owner_name'];//车主人姓名
		$data['owner_id']=$regist_info['owner_id'];//车主人身份证
		$data['bike_type']=$regist_info['attr4'];//电瓶车类型、型号、品牌等
		$data['bike_color']=$regist_info['attr3'];//电瓶车颜色
		$data['rfid']=$regist_info['rfid'];
		$data['rfid_area']=$regist_info['rfid_area'];
		$data['phone']=$regist_info['owner_phone1'];//联络电话
		$data['is_regist']=1;
		$id=M("dpc_addlostbike")->data($data)->add();
		if(empty($id)){
			$this->_resp['code']   = '-999';
			$this->_resp['result'] = '操作失败';
			$this->output();
		}
	}


//老版-推送接口
	protected function addpush($RegistrationId,$Message,$Android_title,$Android_value,$ios_alert,$ios_value,$ios_sound){
		require_once ('JPush/JPush.php');

//		$app_key = 'f390392e2ce2b82bcfc76bbd';
//		$master_secret = '70c7623fc8dc065773901851';

		$app_key = '9ff272c7211e5ac241462bbd';
		$master_secret = '4ff72db35922b881cf69381c';


		// 初始化
		$client = new JPush($app_key, $master_secret);

		// 完整的推送示例,包含指定Platform,指定Alias,Tag,指定iOS,Android notification,指定Message等
		try{
			$result = $client->push()
				->setPlatform(array('ios', 'android'))
//      	->addAlias($RegistrationId)
				->addRegistrationId($RegistrationId)
// 		->setNotificationAlert('Hi, JPush')
// 		->addAndroidNotification("$Android_value", "$Android_title", 1, array("key1"=>"value1", "key2"=>"value2"))
				->addIosNotification($ios_alert, $ios_sound,$badge,true, 'iOS category', array("key1"=>"$ios_value"))
				->setMessage($Message)

				->setOptions(100000, 3600, null,true)
				->send();
			// print_r($result);
		}catch(APIConnectionException $e){
			print($e);
		}catch(APIRequestException $e){
			print($e);
		}
	}


//新版-推送接口
	protected function addPush_tag($tag,$Android_info,$Android_title,$Android_value,$ios_alert,$ios_value,$ios_sound){
		require_once ('JPush/JPush.php');
		$app_key = '9ff272c7211e5ac241462bbd';
		$master_secret = '4ff72db35922b881cf69381c';
		// 初始化
		$client = new JPush($app_key, $master_secret);
		// 完整的推送示例,包含指定Platform,指定Alias,Tag,指定iOS,Android notification,指定Message等
		$result = $client->push()
			->setPlatform(array('ios', 'android'))
			->addTag($tag)
			->addAndroidNotification("$Android_value", "$Android_title", 1, array("key1"=>"$Android_info"))
			->addIosNotification($ios_alert, $ios_sound,$badge,true, 'iOS category', array("key1"=>"$ios_value"))
			//->setMessage($Message)
			->setOptions(100000, 3600, null,true)
			->send();
	}


	// 二维数组按某个key排序(0.1.2)把1变成4，倒叙排列，再把4变成1
	function array_sort($arr,$keys,$type='asc')
	{
		$keysvalue = $new_array = array();
		foreach ($arr as $k=>$v){
			if($v['can_lostbike'] == 1){
				$v['can_lostbike'] = 4;
			}
			$keysvalue[$k] = $v[$keys];
		}



		if($type == 'asc'){
			asort($keysvalue);
		}else{
			arsort($keysvalue);
		}
		reset($keysvalue);
		foreach ($keysvalue as $k=>$v){
			$new_array[$k] = $arr[$k];
		}
		return $new_array;
	}


	 /**
	*	code 状态码
	*	msg  提示信息
	*   data 要返回的数据
	*	返回ajax格式
	*/
	public function return_msg($code='',$msg='',$data=''){
		header("Access-Control-Allow-Origin:*");
		header('Access-Control-Allow-Methods:POST');
		header('Access-Control-Allow-Headers:x-requested-with, content-type');
		  $return_msg['code']=$code;
          $return_msg['msg']=$msg;
          $return_msg['data']=$data;
          echo json_encode($return_msg);die;


	}






}
