<?php
namespace App\Controller;
use Think\Controller;
class IndexController extends BaseController {
    
	public function _initialize(){
		parent::_initialize();
	}
  

     public function  tests(){
		$mobile = I('get.mobile');
		$data = M('app_regist')->where("mobile='$mobile'")->select();
		print_r($data) ;
      // echo "1231123";
	}

	//web端个体通知推送
	public function webSmsPush2(){
	        $this->checkNecessaryParams("msg,mobile");
                $mobile    = I('param.mobile');
                $msg       = I('param.msg');
                //$mobile = $this->_post['mobile'];
                //$msg    = $this->_post['msg'];
                $RegistrationId=M("app_regist")->where("mobile=$mobile")->getField("reserve_1");
                $rand = rand(10000, 99999);
                $Android_title = "消息";
                $Android_value = "您有一个消息待查看";
                $ios_alert = "您有一个消息待查看";
                $ios_value = '{"type":"4","platform":"Web端个推送","message":"'.$msg.'"}';
                $ios_sound = 'waring.caf';
              if($RegistrationId==0){
                  $tag = $mobile.'SHzhaowei2017';
                  $Android_info = '{"type": "4","title": "消息","content": "查看消息","id": "'.$rand.'","message": "'.$msg.'"}';
                  $return=$this->addPush_tag($tag, $Android_info,$Android_title,$Android_value,$ios_alert,$ios_value,$ios_sound);
                  $this->log("短信通知：".json_decode($return));
                 
              }else{
                  $Message='{"type": "4","title": "消息","content": "查看消息","id": "'.$rand.'","message": "'.$msg.'"}';
                  $this->addpush($RegistrationId, $Message,$Android_title,$Android_value,$ios_alert,$ios_value,$ios_sound);
              }
        $add['mobile']  = $mobile;
        $add['message'] = $ios_value;
        $add['time']    = date('Y-m-d H:i:s',time());
        M("ios_message")->data($add)->add();
        $this->outputs();
        }


    public function webSmsPush(){
	    $datas=array();
            $mobile='';
            $tempId='';
            $mobile=$this->_post['mobile'];
            //把to换成以英文逗号隔开数据
            $to = preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)|(\.)/",',',$mobile);
            $accountSid= '8aaf070866235bc501668a50cffb3d8e';
	          $accountToken= 'a4d8e112625847c4b19b1358e98fdad7';
           // $appId='8a216da86812593601684a475cd218cf';
		          $appId='8aaf070866235bc501668a50d05c3d94';
            $serverIP='app.cloopen.com';
            $serverPort='8883';
            $softVersion='2013-12-26';
            //$tempId='408169';   //模板id
            $tempId='459090';  
            $datas=array($datas);
            include("/data/www/anju/Web/yuntongxun/CCPRestSmsSDK.php");
            //include("/yuntongxun/CCPRestSmsSDK.php");
          //  global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
            $rest = new \REST($serverIP,$serverPort,$softVersion);
            $rest->setAccount($accountSid,$accountToken);
            $rest->setAppId($appId);
            $result = $rest->sendSMS($to,$datas,$tempId);
            if($result == NULL ) {
		
		$this->return_msg(1,'error');
             	//$this->_resp['code'] = '1';
                //$this->_resp['result'] = 'error';
		//var_dump(json_encode($this->_resp));
                //return json_encode($this->_resp);
               // exit;
            }
            if($result->statusCode!=0) {
		$this->return_msg(1,'error');
               // $this->_resp['code'] = '1';
               // $this->_resp['result'] ='error';
		//var_dump(json_encode($this->_resp));
                //return json_encode($this->_resp);

            }else{
                $add['mobile']  = $to;
                $arr=explode(',',$to);
               foreach($arr as $key=>$value){
                   if(preg_match("/^1[34578]\d{9}$/",$value)){
                       $add['mobile']  = $value;
                       $add['message'] = '{"type":"4","message":"'.$datas[0].'"}';
                       $add['time']    = date('Y-m-d H:i:s',time());
                       M("ios_message")->data($add)->add();
                   }


               }
		$this->return_msg(0,'success');
		//$this->_resp['code'] = '0';
               // $this->_resp['result'] = 'success';
		//var_dump($this->_resp);
                //return json_encode($this->_resp);
            }

    }
 
	
	


    //Web端群发通知
    public function massNotification(){
        $mobiles = I('param.mobile');
        $msg = I('param.msg');
    //    $mobiles = preg_split('/\r\n/',$mob);
        $ar=array();
        foreach($mobiles as $k=>$v){
            $ar[$k]=$v;
            $mobile=$ar[$k];
            $RegistrationId=M("app_regist")->where("mobile=$mobile")->getField("reserve_1");
            //var_dump($RegistrationId);
            $rand = rand(10000, 99999);
            $Android_title = "消息";
            $Android_value = "您有一个消息待查看";
            $ios_alert = "您有一个消息待查看";
            $ios_value = '{"type":"4","platform":"Web端群推送","message":"'.$msg.'"}';
            $ios_sound = 'waring.caf';
            if($RegistrationId==0){
                $tag = $mobile.'SHzhaowei2017';
                $Android_info = '{"type": "4","title": "消息","content": "查看消息","id": "'.$rand.'","message": "'.$msg.'"}';
                $return=$this->addPush_tag($tag, $Android_info,$Android_title,$Android_value,$ios_alert,$ios_value,$ios_sound);
                $this->log("web群发通知：".json_decode($return));
               
            }else{
                $Message='{"type": "4","title": "消息","content": "查看消息","id": "'.$rand.'","message": "'.$msg.'"}';
                $this->addpush($RegistrationId, $Message,$Android_title,$Android_value,$ios_alert,$ios_value,$ios_sound);
            }
    } 
        $add['mobile']  = $mobile;
        $add['message'] = $ios_value;
        $add['time']    = date('Y-m-d H:i:s',time());
        M("ios_message")->data($add)->add();
        $this->outputs(); 
    }


        //手持机指定消息推送
        public function HandsetNotification(){
                  $mobile =$this->_post['mobile'];
                  $msg    =$this->_post['msg'];
                  $appRegist = M('app_regist')->where('mobile="'.$mobile.'"')->find();
                  if(empty($appRegist)){
                      $this->_resp['code']   = '0002';
                      $this->_resp['result'] = '推送账号不存在！';
                      $this->output();
                  }
        $RegistrationId = M("app_regist")->where("mobile=$mobile")->getField("reserve_1");
        $rand = rand(10000, 99999);
        $Android_title = "消息";
        $Android_value = "您有一个消息待查看";
        $ios_alert = "您有一个消息待查看";
        $ios_value = '{"type":"4","platform":"手持机推送","message":"' . $msg . '"}';
        $ios_sound = 'waring.caf';
        if ($RegistrationId == 0) {
            $tag = $mobile . SHZHAOWEI;
            $Android_info = '{"type": "4","title": "消息","content": "查看消息","id": "' . $rand . '","message": "' . $msg . '"}';
            $return = $this->addPush_tag($tag, $Android_info, $Android_title, $Android_value, $ios_alert, $ios_value, $ios_sound);
            $this->log("手持机指点消息推送：" . json_decode($return));
        } else {
            $Message = '{"type": "4","title": "消息","content": "查看消息","id": "' . $rand . '","message": "' . $msg . '"}';
            $this->addpush($RegistrationId, $Message, $Android_title, $Android_value, $ios_alert, $ios_value, $ios_sound);
        
}
            $add['mobile']  = $mobile;
            $add['message'] = $ios_value;
            $add['time']    = date('Y-m-d H:i:s',time());
            M("ios_message")->data($add)->add();      
  
            $this->_resp['result'] = '推送成功';
            $this->output();
    }


        //app端推送记录查询
        public  function  pushRecord(){
        $mobile =$_SESSION['appUser'];
        $this->app_getRecord($mobile);
        $data = M("ios_message")->where("mobile='".$mobile."' and DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(time)")
            //->limit((($pageinfo-1)*50)-($pageinfo-1),50)
            ->select();
        if( $data=='' && $data!==false){
            $this->_resp['code']   = '-903';
            $this->_resp['result'] = '暂无记录';
            $this->output();
        }

        $arr = array();
        foreach($data as $key=>$v){
            $arr[$key]['message']=$v['message'];
            $arr[$key]['time'] = $v['time'];
        }
        $this->_resp['result'] = $arr;
        $this->output();
    }

    public function index(){
    	echo "Hello World!";
    }

    public function protocol(){
    	$this->display();
    }
}
