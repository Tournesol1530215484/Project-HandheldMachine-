<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;

$op = $_GPC['op'] ? $_GPC['op'] : 'display';
@session_start();
if($op=='display'){
   if(!empty($_POST['openid'])&&!empty($_POST['token'])&&!empty($_POST['token'])){
           /*  $token=json_decode($_POST["token"]); */
            $token=$_POST["token"];
            $token=json_decode($token,true);
            $openid=$_POST["openid"];
            $sign=$_POST["sign"];      
            $resault=str_replace("&quot;",'"',$_GPC["resault"]);
            $resault=json_decode($resault,true);
            $sql="select * from ".tablename('sz_yi_member')." where uniacid=:uniacid and unionid=:unionid";
            $member=pdo_fetch($sql,array(':uniacid'=>$_W['uniacid'],':unionid'=>$token['unionid']));
          
                if(empty($member)){
                /*   $dephp_7 = array(
                           'uniacid' => $_W['uniacid'],
                           'uid' => 0,
                           'appopenid' => $openid, 
                           'openid'=>$openid,
                           'createtime' => time(), 
                           'status' => 0,
                           'token'=>$token,
                           'sign'=>$sign,
                           'ret'=>$resault
                           
                       );  'token'=>$resault['access_token'],
                           'sign'=>$sign,*/
                        $dephp_7 = array(
                           'uniacid' => $_W['uniacid'],
                           'uid' => 0,
                           'appopenid' => $openid, 
                           'openid'=>$openid,
                           'createtime' => time(), 
                           'status' => 0,
                          
                           'unionid'=>$token['unionid'],
                           'nickname' =>  $token['nickname'],
                           'avatar' => $token['headimgurl'],
                           'province'=>$token['province'],
                           'city'=>$token['city'],
                           
                       ); 
                       pdo_insert('sz_yi_member',$dephp_7);
                      
                }else{
                    
                    pdo_update('sz_yi_member',array('appopenid'=>$openid),array('unionid'=>$resault['unionid']));
                    
                }
   }else{
       //$_GPC['openid']='ooeSG04HJODjXp-5edee_6hddaU4';
       $sql="select  * from ".tablename('sz_yi_member')." where uniacid=:uniacid and  unionid=:unionid ";
       $member=pdo_fetch($sql,array(':uniacid'=>$_W['uniacid'],':unionid'=>$_GPC['unionid']));
      
       $lifeTime = 24 * 3600 * 3;
       session_set_cookie_params($lifeTime);
       @session_start();
       $cookieid = "__cookie_sz_yi_userid_{$_W['uniacid']}";
       setcookie($cookieid, base64_encode($member['openid']));
       setcookie('member_mobile', $info['mobile']);
       setcookie('openid', $member['openid']);
       $_SESSION['logins']=1;
       $url = $this -> createMobileUrl('member');
       
       header("location:".$url);
   }

}