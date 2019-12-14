<?php
namespace App\Controller;
use Think\Controller;
class UserController extends BaseController {
    
	public function _initialize(){
		parent::_initialize();
	}


    /*
     * 获取验证码
     */
    public function getVerifyCode(){

    	$mobile  = $this->_post['mobile'];
    	$type    = $this->_post['type'];
    	if (empty($mobile)) {
    		$this->_resp['code']   = '0010';
			$this->_resp['result'] = '手机号不能为空';
			$this->output();
    	}
        if (!is_mobile($mobile)) {
            $this->_resp['code']   = '0011';
            $this->_resp['result'] = '手机号错误,请检查后确认';
            $this->output();
        }
    	if (empty($type)) {
    		$this->_resp['code']   = '0011';
			$this->_resp['result'] = '类型不能为空';
			$this->output();
    	}


        if ( in_array($mobile, array('15201916355','17091913312','13774375336','15618391762')) ) {
            $code    = '888888';
        }else{
            $code    = getCheckCode();
        }

    	$AppRegist  = M('AppRegist');
    	$AppRegist->startTrans();
    	//查询用户
    	$where = array();
		$where['mobile'] = $mobile;
		$id = $AppRegist->where($where)->getField('id');

		//注册
    	if ($type == 1) {
    		if ($id) {
    			$this->_resp['code']   = '0012';
				$this->_resp['result'] = '该手机号已注册，请直接登录';
				$this->output();
    		}

    	//登陆
    	}else{
    		if (!$id) {
    			$this->_resp['code']   = '0012';
				$this->_resp['result'] = '无此用户，请先注册';
				$this->output();
    		}
    	}

    	
        if ( !in_array($mobile, array('15201916355','17091913312','13774375336','15618391762')) ) {
          //   include('/data/www/anju/Web/Phpsms/ChuanglanSmsHelper/ChuanglanSmsApi.php');
          // $clapi  = new \ChuanglanSmsApi();
           $quency=M('app_sms_code')->where("mobile=$mobile")->field('id,update_date,result')->find();
			$date=date('Y-m-d',time());
			$bb_2=strtok($quency['update_date'],' ');//截取空格前的字符串
			if($date == $bb_2 ) {
			       if( $quency['result'] == '0') {
					$this->saveMesResult($quency['id'], '1');//更新result内容
				}
				if( $quency['result'] == '1'){
					$this->saveMesResult($quency['id'], '2');//更新result内容
				}
                               if($quency['result'] == '2'){
					$this->saveMesResult($quency['id'], '3');//更新result内容
				}
				if($quency['result'] == '3'){
					$this->saveMesResult($quency['id'], '4');//更新result内容
				}
				if($quency['result'] == '4'){
					$this->saveMesResult($quency['id'], '5');//更新result内容
				}
				if($quency['result'] == '5'){
					$this->_resp['code'] = '0001';
					$this->_resp['result'] = '今日发送验证码次数达5次上限';
					$this->output();
				}
 
                                 
                                        //写入短信验证码
					$AppSmsCode = M('AppSmsCode');
					$where = array();
					$where['mobile'] = $mobile;
					$id = $AppSmsCode->where($where)->getField('id');
					if ($id) {
						$where = array();
						$where['id'] = $id;
						$data = array();
						$data['code'] = $code;
						$data['update_date'] = date('Y-m-d H:i:s');
						if (false == $AppSmsCode->where($where)->save($data)) {
							$this->_resp['code']   = '0011';
							$this->_resp['result'] = '修改验证码失败';
							$this->output();
						}
					}else{
						$data = array();
						$data['mobile'] = $mobile;
						$data['code'] = $code;
						$data['update_date'] = date('Y-m-d H:i:s');
						if (!$AppSmsCode->add($data)) {
							$this->_resp['code']   = '0011';
							$this->_resp['result'] = '添加验证码失败';
							$this->output();
						}
					}


	//			$result = $clapi->sendSMS($mobile, "您的验证码是:【" . $code . "】。使用后验证码失效，请尽快使用！如非本人操作,可不用理会！（安驹app）");
				$result=$this->sendSMS($mobile,$code);
				if (!is_null(json_decode($result))) {
					$output = json_decode($result, true);
					if (isset($output['code']) && $output['code'] == '0') {
						$AppRegist->commit();
				        	$this->_resp['result'] = '验证码已发送,请注意查收';
						$this->output();
					} else {
						$this->log($result);
						$this->_resp['code'] = '0001';
				  	//	$this->_resp['result'] = $output['errorMsg'];
						$this->_resp['result'] = '验证码发送失败';
						$this->output();
					}
				} else {
					//echo $result;
					$this->_resp['result'] = $result;
					$this->output();
				}
			}else{
				$sve['result']='';
				M('app_sms_code')->where('id="'.$quency['id'].'"')->save($sve);
				$this->saveMesResult($quency['id'], '1');//更新result内容
                                  

                                        //写入短信验证码
					$AppSmsCode = M('AppSmsCode');
					$where = array();
					$where['mobile'] = $mobile;
					$id = $AppSmsCode->where($where)->getField('id');
					if ($id) {
						$where = array();
						$where['id'] = $id;
						$data = array();
						$data['code'] = $code;
						$data['update_date'] = date('Y-m-d H:i:s');
						if (false == $AppSmsCode->where($where)->save($data)) {
							$this->_resp['code']   = '0011';
							$this->_resp['result'] = '修改验证码失败';
							$this->output();
						}
					}else{
						$data = array();
						$data['mobile'] = $mobile;
						$data['code'] = $code;
						$data['update_date'] = date('Y-m-d H:i:s');
						if (!$AppSmsCode->add($data)) {
							$this->_resp['code']   = '0011';
							$this->_resp['result'] = '添加验证码失败';
							$this->output();
						}
					}

      			//		$result = $clapi->sendSMS($mobile, "您的验证码是:【" . $code . "】。使用后验证码失效，请尽快使用！如非本人操作,可不用理会！（安驹app）");
					$result=$this->sendSMS($mobile,$code);
				if (!is_null(json_decode($result))) {
					$output = json_decode($result, true);
					if (isset($output['code']) && $output['code'] == '0') {
						$AppRegist->commit();
						$this->_resp['result'] = '验证码已发送,请注意查收';
						$this->output();
					} else {
						$this->log($result);
						$this->_resp['code'] = '0001';
					//	$this->_resp['result'] = $output['errorMsg'];
					//	$this->_resp['code'] = '0001';
						$this->_resp['result'] = '验证码获取失败!';
						$this->output();
					}
				} else {
					//echo $result;
					$this->_resp['result'] = $result;
					$this->output();
				}
			}
    
        }
    	
    }


    /*
     * 注册
     */
    public function registered(){
    	//简单验证
    	$this->checkNecessaryParams('name,mobile,id_code,ver_code,ji_id');

    	//接收数据
    	$name     = $this->_post['name'];
    	$mobile   = $this->_post['mobile'];
    	$id_code  = $this->_post['id_code'];
    	$ver_code = $this->_post['ver_code'];
    	$reserve_1 = $this->_post['ji_id'];

		$str_code=strlen($id_code);//获取字符串长度
		$str_reserve_1=strlen($reserve_1);//获取字符串长度

        if (!is_mobile($mobile)) {
            $this->_resp['code']   = '0011';
            $this->_resp['result'] = '手机号错误,请检查后确认';
            $this->output();
        }
		if(empty($id_code)){
			$this->_resp['code']   = '0012';
			$this->_resp['result'] = '身份证号不允许为空，请重新填写';
			$this->output();
		};
		if($str_code<5){
			$this->_resp['code']   = '0010';
			$this->_resp['result'] = '身份证号不得少于5个字符';
			$this->output();
		}
		if(empty($reserve_1)){
			$this->_resp['code']   = '0013';
			$this->_resp['result'] = '极光注册码不允许为空，请重新填写';
			$this->output();
		}
		if($str_reserve_1<5){
			$this->_resp['code']   = '0014';
			$this->_resp['result'] = '极光注册码不得少于5个字符';
			$this->output();
		}



        $where = array();
        $where['mobile'] = $mobile;
        $userList = M('AppRegist')->where($where)->find();
//         if (empty($userList)) {
//             $this->_resp['code']   = '0020';
//             $this->_resp['result'] = '请先获取验证码';
//             $this->output();
//         }
        if ($userList['id_code']) {
            $this->_resp['code']   = '0020';
            $this->_resp['result'] = '该身份证已注册';
            $this->output();
        }


    	//验证短信
    	$where = array();
    	$where['mobile'] = $mobile;
    	$list = M('AppSmsCode')->field('id,code,update_date')->where($where)->find();
    	if ($list['code'] != $ver_code) {
    	//	$this->saveMesResult($list['id'],'验证码错误');
    		$this->_resp['code']   = '0020';
			$this->_resp['result'] = '验证码错误';
			$this->output();
    	}

    	$update_date = strtotime($list['update_date']);
    	$update_date = $update_date + VERIFY_CODE_VALID_TIME;
    	if ($update_date < time()) {
    	//	$this->saveMesResult($list['id'],'验证码失效');
    		$this->_resp['code']   = '0021';
			$this->_resp['result'] = '验证码失效';
			$this->output();
    	}

    	//更新用户数据
    	if(empty($userList['mobile'])){
    		$data = array();
    		$data['name']    = $name;
    		$data['mobile']    = $mobile;
    		$data['id_code'] = $id_code;
    		$data['reserve_1'] = $reserve_1;
    		$data['create_date'] = date('Y-m-d H:i:s');
    		$ar_id = M('AppRegist')->data($data)->add();
    		if (empty($ar_id)) {
    			$this->_resp['code']   = '0022';
    			$this->_resp['result'] = '写入用户信息失败';
    		}else{
    			session('appUserId',$ar_id);
    			session('appUser',$mobile);
    		}
    	}else {
    		$old_mobile = $userList['mobile'];
    		$data = array();
    		$data['name']    = $name;
    		$data['mobile']    = $mobile;
    		$data['id_code'] = $id_code;
    		$data['reserve_1'] = $reserve_1;
    		$data['create_date'] = date('Y-m-d H:i:s');
    		$ar_id = M('AppRegist')->where("mobile = '$old_mobile'")->save($data);
    		if (empty($ar_id)) {
    			$this->_resp['code']   = '0023';
    			$this->_resp['result'] = '更新用户信息失败';
    		}else{
    			session('appUserId',$userList['id']);
    			session('appUser',$mobile);
    		}
    	}
    	

    	$this->output();
    }

    protected function saveMesResult($id,$result){
    	M('AppSmsCode')->where(array('id'=>$id))->setField('result',$result);
    }

//测试账号
         public function logininfo(){
          $mobile   = $this->_post['mobile'];
           $ver_code = $this->_post['ver_code'];
             if ($mobile==17091913312 && $ver_code == 888888 ) {
	             $this->logins();
              }else{
		    $this->login();
                                     }
                         }




    /*
     * 登陆
     */
    public function login(){
	$mobile=session('appUser');//记录束

	$this->app_getRecord($mobile);
    	//简单验证
    	$this->checkNecessaryParams('mobile,ver_code,ji_id');
    	//接收数据
    	$mobile   = $this->_post['mobile'];
    	$ver_code = $this->_post['ver_code'];
	$reserve_1 = $this->_post['ji_id'];


        if (!is_mobile($mobile)) {
            $this->_resp['code']   = '0011';
            $this->_resp['result'] = '手机号错误,请检查后确认';
            $this->output();
        }
		
        //验证用户
        $where = array();
        $where['mobile'] = $mobile;
        $userList = M('AppRegist')->field('id,id_code')->where($where)->find();
        if (empty($userList['id_code'])) {
            $this->_resp['code']   = '0020';
            $this->_resp['result'] = '请先注册或完善身份信息';
            $this->output();
		}
    	//验证短信
    	$list = M('AppSmsCode')->field('id,code,update_date')->where($where)->find();
  	if ($list['code'] != $ver_code) {
    //		$this->saveMesResult($list['id'],'验证码错误');
    		$this->_resp['code']   = '0020';
			$this->_resp['result'] = '验证码错误';
			$this->output();
    	}


    	$update_date = strtotime($list['update_date']);
    	$update_date = $update_date + VERIFY_CODE_VALID_TIME;
    	if ($update_date < time()) {
    //		$this->saveMesResult($list['id'],'验证码失效');
    		$this->_resp['code']   = '0021';
			$this->_resp['result'] = '验证码失效';
    	}else{
    		
    		//更新用户数据
    		$where = array();
    		$where['id'] = $userList['id'];
    		$data = array();
    		$data['reserve_1'] = $reserve_1;
    		M('AppRegist')->where($where)->save($data);    		
    //		$this->saveMesResult($list['id'],'成功');
                session('appUserId',$userList['id']);
    		session('appUser',$mobile);
		}
	$this->_resp['result'] = $userList['id_code'];
    	$this->output();
    }

    /*
     * 添加用户反馈
     */
    public function addFeedBack(){
    	$this->checkNecessaryParams('content');
    	$mobile = session('appUser');
		$this->app_getRecord($mobile);
    	if (empty($mobile)) {
    		$this->_resp['code']   = '-1';
			$this->_resp['result'] = '请登陆';
			$this->output();
    	}
    	$content    = $this->_post['content'];

        if (!is_mobile($mobile)) {
            $this->_resp['code']   = '0011';
            $this->_resp['result'] = '手机号错误,请检查后确认';
            $this->output();
        }

    	$data = array();
    	$data['mobile']  = $mobile;
    	$data['content'] = $content;
    	$data['createtime'] = date("Y-m-d H:i:s",time());
    	if (!M('AppFeedback')->add($data)) {
    		$this->_resp['code']   = '0021';
			$this->_resp['result'] = '写入失败';
    	}
    	$this->output();
    }

//新版-注册接口
public function new_Registered(){
         $mobile=session('appUser');//记录操作用
	 $this->app_getRecord($mobile);
       	$this->checkNecessaryParams('name,mobile,id_code,ver_code,ji_id,device,mac,imei');

	//接收数据
	$name     = $this->_post['name'];
	$mobile   = $this->_post['mobile'];
	$id_code  = $this->_post['id_code'];
	$ver_code = $this->_post['ver_code'];
	$reserve_1= $this->_post['ji_id'];
	$device   = $this->_post['device'];//0安卓，1是ios
	$mac      = $this->_post['mac'];
	$imei     = $this->_post['imei'];

	$str_code=strlen($id_code);//获取字符串长度

	if (!is_mobile($mobile)) {
		$this->_resp['code']   = '0011';
		$this->_resp['result'] = '手机号错误,请检查后确认';
		$this->output();
	}
	if(empty($id_code)){
		$this->_resp['code']   = '0012';
		$this->_resp['result'] = '身份证号不允许为空，请重新填写';
		$this->output();
	};
	if($str_code<5){
		$this->_resp['code']   = '0010';
		$this->_resp['result'] = '身份证号不得少于5个字符';
		$this->output();
	}

	$userList = M('AppRegist')->where("id_code='$id_code'")->find();
	if ($userList['id_code'] ) {
		$this->_resp['code']   = '0020';
		$this->_resp['result'] = '该身份证已注册';
		$this->output();
	}


	//验证短信
	$where = array();
	$where['mobile'] = $mobile;
	$list = M('AppSmsCode')->field('id,code,update_date')->where($where)->find();
	if ($list['code'] != $ver_code) {
	//	$this->saveMesResult($list['id'],'验证码错误');
		$this->_resp['code']   = '0020';
		$this->_resp['result'] = '验证码错误';
		$this->output();
	}

	$update_date = strtotime($list['update_date']);
	$update_date = $update_date + VERIFY_CODE_VALID_TIME;
	if ($update_date < time()) {
	//	$this->saveMesResult($list['id'],'验证码失效');
		$this->_resp['code']   = '0021';
		$this->_resp['result'] = '验证码失效';
		$this->output();
	}

	//更新用户数据
	if(empty($userList['mobile'])){
		$data = array();
		$data['name']      = $name;
		$data['mobile']    = $mobile;
		$data['id_code']   = $id_code;
		$data['reserve_1'] = $reserve_1;
		$data['device']    = $device;
		$data['mac']       = $mac;
		$data['imei']      = $imei;
		$data['create_date'] = date('Y-m-d H:i:s',time());
		$ar_id = M('AppRegist')->data($data)->add();
		if (empty($ar_id)) {
			$this->_resp['code']   = '0022';
			$this->_resp['result'] = '写入用户信息失败';
		}else{
			session('appUserId',$ar_id);
			session('appUser',$mobile);
		}
	}else {
		$old_mobile = $userList['mobile'];
		$data = array();
		$data['name']      = $name;
		$data['mobile']    = $mobile;
		$data['id_code']   = $id_code;
		$data['reserve_1'] = $reserve_1;
		$data['device']    = $device;
		$data['mac']       = $mac;
		$data['imei']      = $imei;
		$data['create_date'] = date('Y-m-d H:i:s',time());
		$ar_id = M('AppRegist')->where("mobile = '$old_mobile'")->save($data);
		if (empty($ar_id)) {
			$this->_resp['code']   = '0023';
			$this->_resp['result'] = '更新用户信息失败';
		}else{
			session('appUserId',$userList['id']);
			session('appUser',$mobile);
		}
	}
	$this->output();
}

//新版-登陆接口
	public function new_Login(){
		$mobile=session('appUser');//记录操作用
		$this->app_getRecord($mobile);
		//简单验证
		$this->checkNecessaryParams('mobile,ver_code,ji_id,device,mac,imei');
		//接收数据
		$mobile   = $this->_post['mobile'];
		$ver_code = $this->_post['ver_code'];
		$reserve_1= $this->_post['ji_id'];
		$device   = $this->_post['device'];//0安卓，1是ios
		$mac      = $this->_post['mac'];
		$imei     = $this->_post['imei'];

		if (!is_mobile($mobile)) {
			$this->_resp['code']   = '0011';
			$this->_resp['result'] = '手机号错误,请检查后确认';
			$this->output();
		}

		//验证用户
		$where = array();
		$where['mobile'] = $mobile;
		$userList = M('AppRegist')->field('id,id_code')->where($where)->find();
		if (empty($userList['id_code'])) {
			$this->_resp['code']   = '0020';
			$this->_resp['result'] = '请先注册或完善身份信息';
			$this->output();
		}
		//验证短信
		$list = M('AppSmsCode')->field('id,code,update_date')->where($where)->find();
		if ($list['code'] != $ver_code) {
		//	$this->saveMesResult($list['id'],'验证码错误');
			$this->_resp['code']   = '0020';
			$this->_resp['result'] = '验证码错误';
			$this->output();
		}
		$update_date = strtotime($list['update_date']);
		$update_date = $update_date + VERIFY_CODE_VALID_TIME;
		if ($update_date < time()) {
		//	$this->saveMesResult($list['id'],'验证码失效');
			$this->_resp['code']   = '0021';
			$this->_resp['result'] = '验证码失效';
		}else{

			//更新用户数据
			$where = array();
			$where['id'] = $userList['id'];
			$data = array();
			$data['reserve_1'] = $reserve_1;
			$data['device']    =$device;
			$data['mac']       =$mac;
			$data['imei']      =$imei;
			M('AppRegist')->where($where)->save($data);
		//	$this->saveMesResult($list['id'],'成功');
			session('appUserId',$userList['id']);
			session('appUser',$mobile);
		}
                $this->_resp['result'] = $userList['id_code'];
		$this->output();
	}


	//更改身份证信息时发送验证码
	public function getCode(){
		$mobile  =$this->_post['mobile'];
		$id_code =$this->_post['id_code'];//新的身份证
		if (empty($mobile)) {
			$this->_resp['code']   = '0010';
			$this->_resp['result'] = '手机号不能为空';
			$this->output();
		}
		if (!is_mobile($mobile)) {
			$this->_resp['code']   = '0011';
			$this->_resp['result'] = '手机号错误,请检查后确认';
			$this->output();
		}

		$AppRegist  = M('AppRegist');
		$AppRegist->startTrans();
		//查询用户
		$where = array();
		//$where['mobile'] = $mobile;
		$where['id_code'] =$id_code;
		$id = $AppRegist->where($where)->getField('id');
                if($id){
			$this->_resp['code']   = '0001';
			$this->_resp['result'] = '该身份证号已经被他人注册使用，请先对使用该身份证信息的账号进行变更，谢谢！';
			$this->output();
		}

		$code    = getCheckCode();  //生成的验证码

		if ( !in_array($mobile, array('15201916355','17091913312','13774375336','15618391762','13597599187')) ) {
//		 include ('/data/www/anju/Web/Phpsms/ChuanglanSmsHelper/ChuanglanSmsApi.php');
//			$clapi  = new \ChuanglanSmsApi();
			$quency=M('app_sms_code')->where("mobile=$mobile")->field('id,result,update_date')->find();
			
			$date=date('Y-m-d',time());

			$bb_2=strtok($quency['update_date'],' ');//截取空格前的字符串
			
			if($date == $bb_2 ) {
				
					if( $quency['result'] == '0') {
						$this->saveMesResult($quency['id'], '1');//更新result内容
					}	
				     	if( $quency['result'] == '1') {
						$this->saveMesResult($quency['id'], '2');//更新result内容
				    }
					if ($quency['result'] == '2') {
						$this->saveMesResult($quency['id'], '3');//更新result内容
					}
					if ($quency['result'] == '3') {
						$this->saveMesResult($quency['id'], '4');//更新result内容
					}
					if ($quency['result'] == '4') {
						$this->saveMesResult($quency['id'], '5');//更新result内容
					}
					if ($quency['result'] == '5') {
						$this->_resp['code'] = '0001';
						$this->_resp['result'] = '今日发送验证码次数达5次上限';
						$this->output();
					}

					//写入短信验证码
					$AppSmsCode = M('AppSmsCode');
					$where = array();
					$where['mobile'] = $mobile;
					$id = $AppSmsCode->where($where)->getField('id');
					if ($id) {
						$where = array();
						$where['id'] = $id;
						$data = array();
						$data['code'] = $code;
						$data['update_date'] = date('Y-m-d H:i:s');
						if (false == $AppSmsCode->where($where)->save($data)) {
							$this->_resp['code']   = '0011';
							$this->_resp['result'] = '修改验证码失败';
							$this->output();
						}
					}else{
						$data = array();
						$data['mobile'] = $mobile;
						$data['code'] = $code;
						$data['update_date'] = date('Y-m-d H:i:s');
						if (!$AppSmsCode->add($data)) {
							$this->_resp['code']   = '0011';
							$this->_resp['result'] = '添加验证码失败';
							$this->output();
						}
					}

	//				$result = $clapi->sendSMS($mobile, "您的验证码是:【" . $code . "】。使用后验证码失效，请尽快使用！如非本人操作,可不用理会！（安驹app）");
					$result=$this->sendSMS($mobile,$code);	
				if (!is_null(json_decode($result))) {
						$output = json_decode($result, true);
						if (isset($output['code']) && $output['code'] == '0') {
							$AppRegist->commit();
							$this->_resp['result'] = '验证码已发送,请注意查收';
							$this->output();
						} else {
							$this->log($result);
							$this->_resp['code'] = '0001';
							$this->_resp['result'] = '验证码获取失败!';
						//	$this->_resp['result'] = $output['result'];
							$this->output();
						}
					} else {
						//echo $result;
						$this->_resp['result'] = $result;
						$this->output();
					}

			}else{
                 
				$sve['result']='';
				M('app_sms_code')->where('id="'.$quency['id'].'"')->save($sve);
				$this->saveMesResult($quency['id'], '1');//更新result内容

				//写入短信验证码
				$AppSmsCode = M('AppSmsCode');
				$where = array();
				$where['mobile'] = $mobile;
				$id = $AppSmsCode->where($where)->getField('id');
				if ($id) {
					$where = array();
					$where['id'] = $id;
					$data = array();
					$data['code'] = $code;
					$data['update_date'] = date('Y-m-d H:i:s');
					if (false == $AppSmsCode->where($where)->save($data)) {
						$this->_resp['code']   = '0011';
						$this->_resp['result'] = '修改验证码失败';
						$this->output();
					}
				}else{
					$data = array();
					$data['mobile'] = $mobile;
					$data['code'] = $code;
					$data['update_date'] = date('Y-m-d H:i:s');
					if (!$AppSmsCode->add($data)) {
						$this->_resp['code']   = '0011';
						$this->_resp['result'] = '添加验证码失败';
						$this->output();
					}
				}

		//		$result = $clapi->sendSMS($mobile, "您的验证码是:【" . $code . "】。使用后验证码失效，请尽快使用！如非本人操作,可不用理会！（安驹app）");
				$result=$this->sendSMS($monile,$code);
				if (!is_null(json_decode($result))) {
					$output = json_decode($result, true);
					if (isset($output['code']) && $output['code'] == '0') {
						$AppRegist->commit();
						$this->_resp['result'] = '验证码已发送,请注意查收';
						$this->output();
					} else {
						$this->log($result);
						$this->_resp['code'] = '0001';
						$this->_resp['result'] = '获取验证码失败';
		//				$this->_resp['result'] = $output['result'];
						$this->output();
					}
				} else {
					//echo $result;
					$this->_resp['result'] = $result;
					$this->output();
				}
			}
		}
	}


  	//修改身份证信息
        public function changeID(){
 //             $mobiles = $_SESSION('appUser');
 //             if(empty($mobiles)){
 //                     $this->_resp['code'] = '-1';
 //                    $this->_resp['result'] = "请登录!";
 //                $this->output();
 //            }
 //            $this->checkNecessaryParams("ver_code,id_code,mobile");
                 $ver_code=$this->_post['ver_code'];
               $this->log('$ver_code'.$ver_code);
                $id_code =$this->_post['id_code'];
               $this->log('$id_code'.$id_code);
                $mobile  =$this->_post['mobile'];
                $this->log('$mobile'.$mobile);
                $where = array();
                $where['mobile'] = $mobile;
                $userList = M('AppRegist')->field('id')->where($where)->find();
                //验证短信
                $list = M('AppSmsCode')->field('id,code,update_date')->where($where)->find();
                 if ($list['code'] != $ver_code) {
	                        $this->saveMesResult($list['id'],'验证码错误');
                        $this->_resp['code']   = '0020';
                        $this->_resp['result'] = '验证码错误';
                      $this->output();
              }
               $update_date = strtotime($list['update_date']);
              $update_date = $update_date + VERIFY_CODE_VALID_TIME;
                 if ($update_date < time()) {
		                      $this->saveMesResult($list['id'],'验证码失效');
                        $this->_resp['code']   = '0021';
                        $this->_resp['result'] = '验证码失效';
                }else{
		                       //更新用户数据
                $where = array();
                       $where['id'] = $userList['id'];
                        $data = array();
                       $data['id_code']=$id_code;
               $id=    M('AppRegist')->where($where)->save($data);
                if($id){
                               $this->_resp['result'] = '修改成功';
                    }else{
                                $this->_resp['code']   = '0023';
                                $this->_resp['result'] = '修改失败';
                        }
                       $this->saveMesResult($list['id'],'成功');
                }
               // $this->_resp['result'] = '修改成功';
                $this->output();
        } 


    /*
     * 个人中心
     */
    public function myInfo(){
    	$mobile = session('appUser');
		$this->app_getRecord($mobile);
    	if (empty($mobile)) {
    		$this->_resp['code']   = '-1';
    		$this->_resp['result'] = '请登陆';
    		$this->output();
    	}
    	$info = M("app_regist")->where("mobile = '$mobile'")->field("name,mobile,id_code")->find();
    	$this->_resp['result'] = $info;
    	$this->output();
    }
    
    
  /*
     * 更新版本
     */
    public function appUpdate(){    	
    	$response = array (); //定义JSON响应数组
    	$this->checkNecessaryParams('version');
    	$myFile = file_get_contents("./readme.txt") or die("Unable to open file!");
        $upFile = file_get_contents("./updateText.txt") or die("Unable to open file!");
    	$this->log("版本:".$myFile);   	
    	//判断是否获取到所需的输入
    	if (isset($_POST['version']))
	     {
			$uploadver=$_POST['version'];
	    	$this->log("请求参数:".$uploadver);
			if($myFile != $uploadver)
			{

                	$response ["success"] =1;
		    	$response ["message"] = $upFile;
   		    	$response ['data']="http://anju-test.zwtapp.win:81/anju/Web/download/AnJu-debug.apk";
				$this->_resp['result'] = $response;
     			$this->output();
			}else{
    			$response ["success"] = 0;
    			$response ["message"] = "您的软件版本已经是最新的了!";
    			$response ['data']="";
    			$this->_resp['result'] = $response;
    			$this->output();
    	}
	   	
		 }	
    }
    
    /*
     * ios获取推送缓存消息
     */
    public function getMessage(){
		$mobile = session('appUser');
		$this->checkNecessaryParams('number');
		$id   = $this->_post['number'];
//	    $message = M("ios_message")->where("mobile = '$mobile'")->order('id desc')->find();
    	$message = M("ios_message")->db(1,C('DB_CONFIG1'))->where("mobile = '$mobile'")->order('id desc')->limit($id)->select();
    	$this->_resp['result'] = $message;
    	$this->output();
	
	}
   

	//ios删除推送缓存消息
	public function delMessage(){
		$this->checkNecessaryParams('id');
		$id   = $this->_post['id'];
		M('ios_message')->where('id="'.$id.'"')->select();
		$this->output();
	}



	public function logins(){
		$this->checkNecessaryParams('mobile,ver_code');
		$mobile   = $this->_post['mobile'];
		$ver_code = $this->_post['ver_code'];
		if (!is_mobile($mobile)) {
			$this->_resp['code'] = '0011';
			$this->_resp['result'] = '手机号错误,请检查后确认';
			$this->output();
		}
		$where = array();
		$where['mobile'] = $mobile;
		$userList = M('AppRegist')->field('id,id_code')->where($where)->find();
		if (empty($userList['id_code'])) {
			$this->_resp['code'] = '0020';
			$this->_resp['result'] = '请先注册或完善身份信息';
			$this->output();
		}
		$list = M('AppSmsCode')->field('id,code,update_date')->where($where)->find();
		if ($list['code'] != $ver_code) {
			$this->saveMesResult($list['id'], '验证码错误');
			$this->_resp['code'] = '0020';
			$this->_resp['result'] = '验证码错误';
			$this->output();
		}

	
		$update_date = strtotime($list['update_date']);
		$update_date = $update_date + VERIFY_CODE_VALID_TIME;
		if ($update_date < time()) {
			$this->saveMesResult($list['id'], '验证码失效');
			$this->_resp['code'] = '0021';
			$this->_resp['result'] = '验证码失效';
		}else {


		
			$where = array();
			$where['id'] = $userList['id'];
			$data = array();
			M('AppRegist')->where($where)->save($data);
			$this->saveMesResult($list['id'], '成功');
			session('appUserId', $userList['id']);
			session('appUser', $mobile);
		}
		$this->output();
	}

}
