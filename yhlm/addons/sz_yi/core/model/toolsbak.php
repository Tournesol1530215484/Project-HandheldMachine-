<?php

/*=============================================================================

#     FileName: goods.php

#         Desc: ��Ʒ��

#       Author: Yunzhong - http://www.yunzshop.com

#        Email: 1084070868@qq.com

#     HomePage: http://www.yunzshop.com

#      Version: 0.0.1

#   LastChange: 2016-02-05 02:32:56

#      History:

=============================================================================*/

if (!defined('IN_IA')) {

    exit('Access Denied');

}

class Sz_DYi_Tools{

    function getSet(){
        global $_W ;
        $setdata = pdo_fetch('select * from ' . tablename('sz_yi_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
    
        $set = unserialize($setdata['sets']);
        return $set;
    }

    function trarr($arr=array(),$str=''){   //获得指定字段的1维数组
        $temparr=array();
        foreach ($arr as $key => $value) {
            $temparr[]=$value[$str];
        }
        return $temparr;
    }

    function getMaxArr($arr=array(),$str='')
    {               //获取而二维数组中某个值最大的数据
        $temparr=array();
        foreach ($arr as $key => $value) {
            if ($temparr) {
                $temparr=$value;
            }else{
                if (intval($temparr[$str]) < intval($value[$str])) {
                    $temparr=$value;
                }
            }
        }
        return $temparr;
    }
    
    function calcNowTime($unixEndTime=0){

        if ($unixEndTime <= time()) { // 如果过了活动终止日期
            return '0天0时0分';
        }
        
        // 使用当前日期时间到活动截至日期时间的毫秒数来计算剩余天时分
        $time = $unixEndTime - time();
        
        $days = 0;
        if ($time >= 86400) { // 如果大于1天
            $days = (int)($time / 86400);
            $time = $time % 86400; // 计算天后剩余的毫秒数
        }
        
        $xiaoshi = 0;
        if ($time >= 3600) { // 如果大于1小时
            $xiaoshi = (int)($time / 3600);
            $time = $time % 3600; // 计算小时后剩余的毫秒数
        }
        
        $fen = (int)($time / 60); // 剩下的毫秒数都算作分
        
        return $days.'天'.$xiaoshi.'时'.$fen.'分';
    }




    function calcTime($time=0){

        // if ($unixEndTime <= time()) { // 如果过了活动终止日期
        //     return '0天0时0分';
        // }
        
        // // 使用当前日期时间到活动截至日期时间的毫秒数来计算剩余天时分
        // $time = $unixEndTime - time();
        
        $days = 0;
        if ($time >= 86400) { // 如果大于1天
            $days = (int)($time / 86400);
            $time = $time % 86400; // 计算天后剩余的毫秒数
        }
        
        $xiaoshi = 0;
        if ($time >= 3600) { // 如果大于1小时
            $xiaoshi = (int)($time / 3600);
            $time = $time % 3600; // 计算小时后剩余的毫秒数
        }
        
        $fen = (int)($time / 60); // 剩下的毫秒数都算作分
        
        return $days.'天'.$xiaoshi.'时'.$fen.'分';
    }


    function getsure($min,$max,$str=''){ 
        $result=null;
        if (empty($min) && empty($max)) {
            $result='无限制';
        }else{
            if (!empty($min) && empty($max)) {
                $v=$str.'大于'.$min;
            }elseif(empty($min) && !empty($max)){
                $result=$str.'小于'.$max;
            }else{
                $result=$str.$min.' - '.$max.'之间';
            }
        }
        return $result;
    }

    function getAd($id,$where=''){  //获取1条ad
        global $_W ;
        $sql='select * from '.tablename('sz_yi_ad_model').' where uniacid = :uniacid and id = :id';
        $sql.=$where;
        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':id'=>$id
        );
        $ad=pdo_fetch($sql,$params);
        return $ad; 
    }


    function getManyAd($where='',$params=array(),$pindex=1,$psize=5){  //获取1条ad
        global $_W ;    
        
        $sql='select * from '.tablename('sz_yi_ad_model').' where uniacid = :uniacid ';
        $params[':uniacid']=$_W['uniacid'];

        $sql.=$where;
        $sql.=' limit '.($pindex - 1) * $psize .','.$psize;
        // show_json(0,$params);

        $ad=pdo_fetchall($sql,$params);
        // pdo_debug();
        if ($ad) {
            foreach ($ad as $key => $value) {
                $ad[$key]['thumb']=unserialize($ad[$key]['thumb']);
                foreach ($ad[$key]['thumb'] as $k => $v) {
                    $ad[$key]['thumb'][$k]=tomedia($v);
                }
            }
        }
        return $ad; 
    }

    function expStr($str){
        for ($i=0; $i < mb_strlen($str,'utf8'); $i++) { 
            $arr[]=mb_substr($str,$i,1,'utf8');
        }
        return $arr;
    }   

    function checkStr($str='',$core=''){
        $ratio=0;
        $str1=explode(',',$str);
        $str=$this->expStr($str);
        $carr=$this->expStr($core);
        $count=count($carr);
            
        foreach ($str as $key => $value) {
            if (in_array($value,$carr)) {
                $ratio+=1;
                foreach ($carr as $k => $v) {
                    if ($value == $v) {
                        unset($carr[$k]);
                        break;
                    }
                }
            }
        }
        
        $re=intval(($ratio / $count) * 100);
        return $re;
        
    }

    function favoriteStore($openid,$uid){
        global $_W,$_GPC;

        $puinfo=p('bonus')->getMerch($uid);

        if (empty($puinfo || $merchant)) {
            show_json(0,'商家未找到');
        }
        
        $data = pdo_fetch('select id,deleted from ' . tablename('sz_yi_member_favorite') . ' where uniacid=:uniacid and merchid=:merchid and openid=:openid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $openid, ':merchid' => $uid));

        if (empty($data)) { //如果没有收藏过
            $data = array('uniacid' => $_W['uniacid'], 'openid' => $openid, 'merchid' => $uid, 'createtime' => time());
            pdo_insert('sz_yi_member_favorite', $data);
            return true;
            // show_json(1, array('isfavorite' => true));
        } else {    //如果收藏过
            if (empty($data['deleted'])) {
                pdo_update('sz_yi_member_favorite', array('deleted' => 1), array('id' => $data['id'], 'uniacid' => $_W['uniacid'], 'openid' => $openid));
                return false;
                // show_json(1, array('isfavorite' => false));
            } else {         
                pdo_update('sz_yi_member_favorite', array('deleted' => 0), array('id' => $data['id'], 'uniacid' => $_W['uniacid'], 'openid' => $openid));
                return true;
                // show_json(1, array('isfavorite' => true));
            }
        }
    }


    function favoriteMember($openid,$faopenid){
        global $_W,$_GPC;


        $puinfo=m('activity')->getMuser($faopenid);
        
        if (empty($puinfo) || empty($puinfo['uid'])) {
            show_json(0,'该机构还未注册');
        }
        $uid=$puinfo['uid'];                                         
        $data = pdo_fetch('select id,deleted from ' . tablename('sz_yi_activity_favorite') . ' where uniacid=:uniacid and merchid=:merchid and openid=:openid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $openid, ':merchid' => $uid));

        if (empty($data)) { //如果没有收藏过
            $data = array('uniacid' => $_W['uniacid'], 'openid' => $openid, 'merchid' => $uid, 'createtime' => time());
            pdo_insert('sz_yi_activity_favorite', $data);
            return true;
        } else {    //如果收藏过
            if (empty($data['deleted'])) {
                pdo_update('sz_yi_activity_favorite', array('deleted' => 1), array('id' => $data['id'], 'uniacid' => $_W['uniacid'], 'openid' => $openid));     
                return false;
            } else {         
                pdo_update('sz_yi_activity_favorite', array('deleted' => 0), array('id' => $data['id'], 'uniacid' => $_W['uniacid'], 'openid' => $openid));
                return true;
            }
        }
    }

    function checkFavorite($openid,$uid){
        global $_W;
        $sql='select * from '.tablename('sz_yi_activity_favorite').' where uniacid = :uniacid and openid = :openid and merchid = :uid';
        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':openid'=>$openid,
            ':uid'=>$uid
        );          
        $fr=pdo_fetch($sql,$params);
        if ($fr) {
            if ($fr['deleted']) {
                return false;
            }else{
                return true;
            }
        }else{          
            return false;
        }
    }       



    function getChar($num) {

     // $num为生成汉字的数量
       $b = '';
       for ($i=0; $i<$num; $i++) {
           // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
           $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
           // 转码
           $b .= iconv('GB2312', 'UTF-8', $a);
       }
       return $b;
   }

   function tip($tips=''){
        die('<script>alert("'.$tips.'");history.go(-1);</script>');
    }

    function tips($tips='',$url){           
        die('<script>alert("'.$tips.'");location.href="'.$url.'";</script>');
    }

    
    function memberAd($openid,$adid,$sure=true){    //$sure --> false 删除  true 添加
        global $_W;
        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':openid'=>$openid,
            ':cate'=>$adid
        );
        $exists=pdo_fetch('select * from '.tablename('sz_yi_member_ad_cate').' where uniacid = :uniacid and openid = :openid and cate = :cate',$params);
        if ($exists) {
            if ($sure) {
                $data=array(
                    'is_del'=>0,
                    'uptime'=>time()
                );
            }else{
                $data=array(
                    'is_del'=>1,
                    'uptime'=>time()
                );
            }
            $re=pdo_update('sz_yi_member_ad_cate',$data,array('openid'=>$openid,'cate'=>$adid));
            if ($re){
                return true;
            }else{      
                return false;
            }
        }else{
            if (!$sure) {
                $data=array(
                    'uniacid'=>$_W['uniacid'],
                    'openid'=>$openid,
                    'cate'=>$adid,
                    'uptime'=>time()
                );
                pdo_insert('sz_yi_member_ad_cate',$data);
                if (pdo_insertid()) {
                    return true;
                }else{
                    return false;

                }
            }
            
        }
        
        
        pdo_update('');
    }

    function mobileReplace($string,$sym='*',$n1=3,$n2=5,$n3=3){  // 符号 开头 中间 结尾
        $pattern = "/(\d{".$n1."})\d{".$n2."}(\d{".$n3."})/";
        $symbol='';
        for ($i=0; $i < $n2; $i++) { 
            $symbol.=$sym;
        }
        $replacement = "\$1".$symbol."\$2";  
        return preg_replace($pattern, $replacement, $string);
    }

    function strReplace($str,$start=1){
        $tempname = mb_substr($str,$start,null,'utf8');
        $temp='*'.$tempname; 
        return $temp;
    }

    function getFastAndLastTime($y = "", $m = ""){
        if ($y == "") $y = date("Y");
        if ($m == "") $m = date("m");
        $m = sprintf("%02d", intval($m));
        $y = str_pad(intval($y), 4, "0", STR_PAD_RIGHT);
        
        $m>12 || $m<1 ? $m=1 : $m=$m;
        $firstday = strtotime($y . $m . "01000000");
        $firstdaystr = date("Y-m-01", $firstday);
        $lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstdaystr +1 month -1 day")));
     
        return array(
            "firstday" => $firstday,
            "lastday" => $lastday
        );
    }

    function trthumb($list=array(),$str='thumb'){
        foreach ($list as $key => $value) {
            $list[$key][$str]=unserialize($value[$str]);
            foreach ($list[$key][$str] as $k => $v) {
                $list[$key][$str][$k]=tomedia($v);
            }
        }
        return $list;
    }

    function getChild($id){
        global $_W;
        $total=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_member').' where uniacid = :uniacid and agentid = :id',array(':id'=>$id,':uniacid'=>$_W['uniacid']));
        return $total?:0;
    }

    function getCountChild($id){
        global $_W;
        $total=0;
        $all=pdo_fetchall('select * from '.tablename('sz_yi_member').' where uniacid = :uniacid and agentid = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
        foreach ($all as $key => $value) {
            $num=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_member').' where uniacid = :uniacid and agentid = :id',array(':id'=>$value['id'],':uniacid'=>$_W['uniacid']));
            $num=$num?:0;
            $total+=$num;
        }
        return $total;
    }

    function statisFavorite($uid,$sure=true){
        global $_W;
        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':uid'=>$uid,

        );
        if ($sure) {
            $condition=' and deleted = 0 ';     //关注
        }else{
            $condition=' and deleted = 1 '; //取关
        }
        $sql='select count(*) from '.tablename('sz_yi_member_favorite').' where uniacid = :uniacid and merchid = :uid ';
        $sql.=$condition;
        $total=pdo_fetchcolumn($sql,$params);
        $total=$total?:0;
        return $total;
    }

    function statisSales($uid){
        global $_W;
        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':uid'=>$uid,
        );
        $sql='select sum(salesreal) from '.tablename('sz_yi_goods').' where uniacid = :uniacid and supplier_uid = :uid ';
        $total=pdo_fetchcolumn($sql,$params);       
        $total=$total?:0;   
        return $total;
    }

    function getMuser($uid){
        global $_W;

        $params=array(
            ':uniacid'=>$_W['uniacid']
        );
        $sql='select * from '.tablename('sz_yi_member_user').' where uniacid  = :uniacid ';

        if (is_numeric($uid)) 
        {
            $params[':uid'] = $uid ; 
            $sql.=' and uid = :uid ';
        }else
        {
            $params[':openid'] = $uid ; 
            $sql.=' and openid = :openid ';
        }
        $info=pdo_fetch($sql,$params);
        return $info?:array();      
    }


    function setMuser($data,$params)
    {
        global $_W;              
        $re=pdo_update('sz_yi_member_user',$data,$params);
        return $re?true:false;
    }

    function getAccountInfo($openid){
        global $_W;

        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':openid'=>$openid,
        );
        $sql='select * from '.tablename('sz_yi_activity_account_info').' where uniacid  = :uniacid and openid = :openid ';

        $info=pdo_fetch($sql,$params);

        return $info?:array(); 
    }

    function setAccountInfo($data,$params)
    {
        global $_W;
        $exists=$this->getAccountInfo($params['openid']);
        if ($exists) {
            $re=pdo_update('sz_yi_activity_account_info',$data,$params);
        }else{
            $data['openid']=$params['openid'];
            $data['uniacid']=$params['uniacid'];
            $re=pdo_insert('sz_yi_activity_account_info',$data);
        } 
        return $re?true:false;
    }

    function getinfo($uid){
        global $_W;

        $info=pdo_fetch('select * from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and uid = :uid ',array(':uniacid'=>$_W['uniacid'],':uid'=>$uid));

        return $info;        
    }

    function getact($atid,$type=1){
        global $_W;         
        if ($type==1) {
            $info=pdo_fetch('select * from '.tablename('sz_yi_activity').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$atid));                 
        }else{
            $info=pdo_fetch('select * from '.tablename('sz_yi_activity_article').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$atid));                 
        }
        return $info?:array();
    }

    function long2short($longurl='')
    {
        load()->func('communication');
        $token = WeAccount::token(WeAccount::TYPE_WEIXIN);
        $url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token={$token}";
        $send = array();
        $send['action'] = 'long2short';
        $send['long_url'] = $longurl;
        $response = ihttp_request($url, json_encode($send));
        if(is_error($response)) {
            $result = array('errcode'=>-1,'errmsg'=>"访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if(empty($result)) {            
            $result =  array('errcode'=>-1,'errmsg'=>"接口调用失败, 元数据: {$response['meta']}");
        } elseif(!empty($result['errcode'])) {
            $result =array('errcode'=>-1,'errmsg'=>"访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']}");
        }
        if(is_error($result)) {                            
            return array('errcode' => -1, 'errmsg' => $result['message']);
        }
        return $result;
    }


    // function createActivityPoster($openid,$type,$showinfo=array()) //电子票
    // {
    //     global $_W,$_GPC;

    //     $poster = pdo_fetch('select * from ' . tablename('sz_yi_poster') . ' where uniacid=:uniacid and type=:type and isdefault=1 limit 1', array(
    //         ':uniacid' => $_W['uniacid'],
    //         ':type' => $type
    //     ));              

    //     // $tpl = pdo_fetch('select * from ' . tablename('sz_yi_activity_poster') . ' where uniacid=:uniacid and id=:id and enabled = 1 order by displayorder desc limit 1', array(
    //     //     ':uniacid' => $_W['uniacid'],
    //     //     ':id' => $tplid         
    //     // ));

    //     // $poster['bg']=$tpl['thumb'];
            
    //     if (empty($poster)) {
    //         return '';
    //     }

    //     $member = m('member')->getMember($openid);
    //     if (empty($poster)) {
    //         return "";
    //     }        

    //     $qr = p('poster')->getQR($poster, $member, $goodsid);
    //     if (empty($qr)) {
    //         return "";
    //     }

    //     if ($type == 7) {           
    //         return $this->createPaper($poster, $member, $qr, false,$showinfo);        
    //     }else{          
    //         return $this->createPoster($poster, $member, $qr, false);       
    //     }               
        
    // }



    function createCardPoster($openid,$id,$url=0) //创建活动海报
    {
        global $_W,$_GPC;            
 
         $poster = pdo_fetch('select * from ' . tablename('sz_yi_poster') . ' where isdefault = 1 and uniacid=:uniacid and type = :id limit 1', array(
            ':uniacid' => $_W['uniacid'],                        
            ':id' => $id                 
        ));               
                
        if ($_GPC['poster_tpl']) {       
            $ttpl=pdo_fetch('select * from '.tablename('sz_yi_activity_poster').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$_GPC['poster_tpl']));
            $poster['bg']=$ttpl['thumb'];
        }                                                                       

        if (empty($poster)) {                   
            return '';           
        }    

        // $member=m('member')->getMember($openid);
                                         
        if (empty($poster)) {                                                                    
            return "";                      
        }
        // $url=$this->createPluginMobileUrl(,array('tid'=>$tid));
        // $url=$this->createPluginMobileUrl('activity/card');
        // var_dump($url);      
        // exit;                                   
        $qr=array(
            'current_qrimg'=>$this->createVQrcode($url),
            'mediaid'=>'',    
            'qrimg'=>'',
            'createtime'=>''
        );

        // $qr = p('poster')->getQR($poster, $member,$tid);
        if (empty($qr['current_qrimg'])) {                                                              
            return "";                       
        }    

        // $a=Array(        
        //         [mediaid] =&gt; 
        //         [createtime] =&gt; 0
        //         [qrimg] =&gt; https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQHf8DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyMm50MUFjOGU4YmYxMDAwMDAwN2MAAgRIbshbAwQAAAAA
        //         [current_qrimg] =&gt; https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQHf8DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyMm50MUFjOGU4YmYxMDAwMDAwN2MAAgRIbshbAwQAAAAA
        //     );               
                                  
                     
        return $this->myCreatePoster($poster, $openid, $qr, false);       
    }



    function createShopImage($_var_105,$posterid=0)
    {
        global $_W, $_GPC;      
        $poster=pdo_fetch('select * from '.tablename('sz_yi_activity').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$posterid));
        $_var_105 = set_medias($_var_105, 'bg');
        $_var_100 = IA_ROOT . '/addons/sz_yi/data/activity_poster/' . $_W['uniacid'] . '/';
        if (!is_dir($_var_100)) {
            load()->func('file');
            mkdirs($_var_100);
        }
        $_var_98 = intval($_GPC['mid']);
        $_var_36 = m('user')->getOpenid();
        $_var_106 = m('member')->getMember($_var_36);
        if ($_var_106['isagent'] == 1 && $_var_106['status'] == 1) {
            $_var_107 = $_var_106;
        } else {
            $_var_98 = intval($_GPC['mid']);
            if (!empty($_var_98)) {
                $_var_107 = m('member')->getMember($_var_98);
            }
        }

        $_var_109 = md5(json_encode(array('openid' => $_var_36, 'signimg' => $_var_105['bg'], 'version' => 4,'posterid'=>$posterid)));
        $_var_102 = $_var_109 . '.jpg';
        
        if (!is_file($_var_100 . $_var_102)) {
            set_time_limit(0);
            @ini_set('memory_limit', '256M');
            $_var_110 = IA_ROOT . '/addons/sz_yi/static/fonts/msyh.ttf';
            $_var_111 = imagecreatetruecolor(640, 1008);
            $_var_124 = imagecolorallocate($_var_111, 0, 3, 51);
            $_var_125 = imagecolorallocate($_var_111, 240, 102, 0);
            $_var_126 = imagecolorallocate($_var_111, 255, 255, 255);
            $_var_127 = imagecolorallocate($_var_111, 255, 255, 0);              
          
                $_var_113 = imagecreatefromjpeg(IA_ROOT . '/addons/sz_yi/plugin/commission/images/poster_mobile.jpg');
                $_var_112 = 196;
          
            $_var_114 = $_var_107['realname'] ? $_var_107['realname'] : $_var_107['nickname'];

            
            
            $_var_114 = $_var_114 ? $_var_114 : $_var_107['mobile'];
            imagecopy($_var_111, $_var_113, 0, 0, 0, 0, 640, 1008);
            imagedestroy($_var_113);
            $_var_115 = preg_replace('/\\/0$/i', '/96', $_var_107['avatar']);
                 


            $_var_119 = $this->createImage($_var_105['bg']);
            $_var_117 = imagesx($_var_119);
            $_var_118 = imagesy($_var_119);
            imagecopyresized($_var_111, $_var_119, 0, 0, 0, 0, 640, 1008, $_var_117, $_var_118);             
            imagedestroy($_var_119);                      

           
            $_var_138 = tomedia($this->createMyShopQrcode($_var_107['id'],$_GPC['act_id']));
            $_var_123 = $this->createImage($_var_138);                                                  
            $_var_117 = imagesx($_var_123);         
            $_var_118 = imagesy($_var_123);                             

            imagecopyresized($_var_111, $_var_123, $_var_112, 433, 0, 0, 250, 250, $_var_117, $_var_118);    
            imagedestroy($_var_123);


            $_var_130 = $poster['title'];
            imagettftext($_var_111, 20, 0, 100, 910, $_var_125, $_var_110, $_var_130);
            $_var_131 = imagettfbbox(20, 0, $_var_110, $_var_130);
            $_var_132 = $_var_131[4] - $_var_131[6];

            $_var_130 = date('Y-m-d H:i',$poster['stime']);      
            imagettftext($_var_111, 17, 0, 137, 950, $_var_125, $_var_110, $_var_130);
            $_var_131 = imagettfbbox(20, 0, $_var_110, $_var_130);
            $_var_132 = $_var_131[4] - $_var_131[6];        


            $_var_130 = $poster['province'].$poster['city'].$poster['area'].$poster['address'];         
            imagettftext($_var_111, 17, 0, 137, 980, $_var_125, $_var_110, $_var_130);                  
            $_var_131 = imagettfbbox(20, 0, $_var_110, $_var_130);
            $_var_132 = $_var_131[4] - $_var_131[6];
        
            imagejpeg($_var_111, $_var_100 . $_var_102);
            imagedestroy($_var_111);
        }
        return $_W['siteroot'] . 'addons/sz_yi/data/activity_poster/' . $_W['uniacid'] . '/' . $_var_102;
    }


    function createCardImage($_var_105)
    {          
        global $_W, $_GPC;
        $_var_105 = set_medias($_var_105, 'bg');     
        $_var_100 = IA_ROOT . '/addons/sz_yi/data/activity_poster/card/' . $_W['uniacid'] . '/';
        if (!is_dir($_var_100)) {
            load()->func('file');
            mkdirs($_var_100);
        }
        $_var_98 = intval($_GPC['mid']);
        $_var_36 = m('user')->getOpenid();
        $_var_106 = m('member')->getMember($_var_36);
        if ($_var_106['isagent'] == 1 && $_var_106['status'] == 1) {
            $_var_107 = $_var_106;
        } else {
            $_var_98 = intval($_GPC['mid']);
            if (!empty($_var_98)) {
                $_var_107 = m('member')->getMember($_var_98);
            }       
        }

        $Signinfo=pdo_fetch('select * from '.tablename('sz_yi_activity_signin').' where uniacid = :uniacid and openid = :openid and date = :date',array(':uniacid'=>$_W['uniacid'],':openid'=>$_var_36,':date'=>date('Ymd')));
        $sure=$Signinfo?'yes':'no';          
        $_var_109 = md5(json_encode(array('openid' => $_var_36, 'signimg' => $_var_105['bg'], 'version' => 4,'date'=>date('Ymd'),'sign'=>$sure)));
        $_var_102 = $_var_109 . '.jpg';

        if (!is_file($_var_100 . $_var_102)) {                    
            set_time_limit(0);       
            @ini_set('memory_limit', '256M');
            $_var_110 = IA_ROOT . '/addons/sz_yi/static/fonts/msyh.ttf';
            $_var_111 = imagecreatetruecolor(640, 1008);
            // $_var_111 = imagecreatetruecolor(640, 1008);     
            $_var_124 = imagecolorallocate($_var_111, 0, 3, 51);                                         
            $_var_125 = imagecolorallocate($_var_111, 240, 102, 0);
            $_var_126 = imagecolorallocate($_var_111, 0, 0, 0);      
            $_var_127 = imagecolorallocate($_var_111, 255, 255, 0);
            // if (!is_weixin()) {
                $_var_113 = imagecreatefromjpeg(IA_ROOT . '/addons/sz_yi/plugin/commission/images/poster_mobile.jpg');
                $_var_112 = 196;
            // } else {      
                // $_var_113 = imagecreatefromjpeg(IA_ROOT . '/addons/sz_yi/plugin/commission/images/poster.jpg');
                // $_var_112 = 50;
            // }
            // $_var_114 = $_var_107['realname'] ? $_var_107['realname'] : $_var_107['nickname'];

            
            
            // $_var_114 = $_var_114 ? $_var_114 : $_var_107['mobile'];
            // imagecopy($_var_111, $_var_113, 0, 0, 0, 0, 640, 1008);
            // imagedestroy($_var_113);
            // $_var_115 = preg_replace('/\\/0$/i', '/96', $_var_107['avatar']);
            
    

            // $_var_116 = $this->createImage($_var_115);
            // $_var_117 = imagesx($_var_116);
            // $_var_118 = imagesy($_var_116);
            // imagecopyresized($_var_111, $_var_116, 24, 32, 0, 0, 88, 88, $_var_117, $_var_118);
            // imagedestroy($_var_116);
                 

            // $re=$this->imagecropper(tomedia($_var_105['bg']),640,1008,7);                                     
            // $_var_105['bg'] = $re;                                                        

            $_var_119 = $this->createImage($_var_105['bg']);


            $_var_117 = imagesx($_var_119);              
            $_var_118 = imagesy($_var_119);
            imagecopyresized($_var_111, $_var_119, 0, 0, 0, 0, 640, 1008, $_var_117, $_var_118);             
            imagedestroy($_var_119);                      

           
            $_var_138 = tomedia($this->createMyCardQrcode($_var_107['id']));
            $_var_123 = $this->createImage($_var_138);          
            $_var_117 = imagesx($_var_123);         
            $_var_118 = imagesy($_var_123);                     
           
            imagecopyresized($_var_111, $_var_123, $_var_112, 580, 0, 0, 250, 250, $_var_117, $_var_118);    
            imagedestroy($_var_123);

             

            if ($Signinfo) {
                $max=$Signinfo['continuous'];
                $max=$max?$max+1:0;
                $str1 = '早安，我今天'.date('H:i:s',$Signinfo['ctime']).'起床，获得'.$Signinfo['score'].'积分，坚持早';
                $str2 = '起'.$max.'天了，明天你能超过我么!';   
            }else{
                $str1 = '早安，我今天还没起床，什么也没获得。';
                $str2 = '';
            }
            imagettftext($_var_111, 18, 0, 70, 860, $_var_126, $_var_110, $str1);
            $_var_131 = imagettfbbox(20, 0, $_var_110, $str1);
            $_var_132 = $_var_131[4] - $_var_131[6];

            imagettftext($_var_111, 18, 0, 70, 900, $_var_126, $_var_110, $str2);
            $_var_131 = imagettfbbox(20, 0, $_var_110, $str2);
        
            imagejpeg($_var_111, $_var_100 . $_var_102);
            imagedestroy($_var_111);
        }
        return $_W['siteroot'] . 'addons/sz_yi/data/activity_poster/card/' . $_W['uniacid'] . '/' . $_var_102;
    }

    

    function createImage($_var_101)             
    {
        load()->func('communication');
        $_var_104 = ihttp_request($_var_101);
        return imagecreatefromstring($_var_104['content']);
    }


    public function createMyShopQrcode($_var_98 = 0, $_var_99 = 0)
    {
        global $_W,$_GPC;
        $_var_100 = IA_ROOT . '/addons/sz_yi/data/activity_poster/' . $_W['uniacid'];
        if (!is_dir($_var_100)) {
            load()->func('file');        
            mkdirs($_var_100);
        }
        if ($_GPC['what'] == 1) {
            $_var_101 = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=plugin&p=activity&method=center&mid=' . $_var_98.'&op=signin'; 
        }else if($_GPC['what'] == 2){          
            $_var_101 = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=plugin&p=activity&method=activity&mid=' . $_var_98.'&op=detail';
        }
        if (!empty($_var_99)) {             
            $_var_101 .= '&id=' . $_var_99;        
        }

        // die($_var_101);                                               
        
        $_var_102 = 'activity_'.$_GPC['what'].'_' . $_var_99 . '_' . $_var_98 . '.png';           
        
        $_var_103 = $_var_100 . '/' . $_var_102;
        if (!is_file($_var_103)) {
            require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
            QRcode::png($_var_101, $_var_103, QR_ECLEVEL_H, 4);
        }
        return $_W['siteroot'] . 'addons/sz_yi/data/activity_poster/' . $_W['uniacid'] . '/' . $_var_102;
    }


    public function createMyCardQrcode($_var_98 = 0)
    {
        global $_W,$_GPC;
        $_var_100 = IA_ROOT . '/addons/sz_yi/data/activity_poster/card/' . $_W['uniacid'];
        if (!is_dir($_var_100)) {
            load()->func('file');        
            mkdirs($_var_100);
        }
        
            $_var_101 = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=plugin&p=activity&method=center&mid=' . $_var_98.''; 
        if (!empty($_var_98)) {             
            $_var_101 .= '&mmid=' . $_var_98;        
        }

        // die($_var_101);                            
            
        $_var_102 = 'card_' . $_var_98 . '.png';           
        
        $_var_103 = $_var_100 . '/' . $_var_102;
        if (!is_file($_var_103)) {
            require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
            QRcode::png($_var_101, $_var_103, QR_ECLEVEL_H, 4);     
        }
        return $_W['siteroot'] . 'addons/sz_yi/data/activity_poster/card/' . $_W['uniacid'] . '/' . $_var_102;
    }


    public function createQrcode($url = '')
    {
        global $_W,$_GPC;
        $_var_100 = IA_ROOT . '/addons/sz_yi/data/activity_poster/article/' . $_W['uniacid'];
        if (!is_dir($_var_100)) {   
            load()->func('file');                         
            mkdirs($_var_100);      
        }

        $_var_102 = 'article_' . md5($url) . '.png';           
        $_var_103 = $_var_100 . '/' . $_var_102;     
        if (!is_file($_var_103)) {      
            require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
            QRcode::png($url, $_var_103, QR_ECLEVEL_H, 4);     
        }
        return $_W['siteroot'] . 'addons/sz_yi/data/activity_poster/article/' . $_W['uniacid'] . '/' . $_var_102;
    }


    public function createVQrcode($url = '')
    {   
        // var_dump(12312313);
        // exit;
        global $_W,$_GPC;
        $_var_100 = IA_ROOT . '/addons/sz_yi/data/activity_poster/cardlist/' . $_W['uniacid'];
        if (!is_dir($_var_100)) {   
            load()->func('file');                         
            mkdirs($_var_100);      
        }

        $_var_102 = 'vqr_' . md5($url) . '.png';           
        $_var_103 = $_var_100 . '/' . $_var_102;     
        if (!is_file($_var_103)) {      
            require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
            QRcode::png($url, $_var_103, QR_ECLEVEL_H, 4);           
        }
        return $_W['siteroot'] . 'addons/sz_yi/data/activity_poster/cardlist/' . $_W['uniacid'] . '/' . $_var_102;
    }


    function createPoster($poster, $member, $qr, $upload = true)
        {
            global $_W;
            $path = IA_ROOT . "/addons/sz_yi/data/activity_poster/" . $_W['uniacid'] . "/";
            if (!is_dir($path)) {
                load()->func('file');       
                mkdirs($path);
            }
            if (!empty($qr['goodsid'])) {
                $goods = pdo_fetch('select id,title,thumb,commission_thumb,marketprice,productprice from ' . tablename('sz_yi_goods') . ' where id=:id and uniacid=:uniacid limit 1', array(
                    ':id' => $qr['goodsid'],
                    ':uniacid' => $_W['uniacid']
                ));
                if (empty($goods)) {
                    m('message')->sendCustomNotice($member['openid'], '未找到商品，无法生成海报');
                    exit;        
                }        
            }        
            $md5  = md5(json_encode(array(
                'openid' => $member['openid'],
                'goodsid' => $qr['goodsid'],
        'bg' => $poster['bg'], 
                'data' => $poster['data'],
                'version' => 1
            )));
            $file = $md5 . '.png';

            if (!is_file($path . $file) || $qr['qrimg'] != $qr['current_qrimg']) {
                set_time_limit(0);
                @ini_set('memory_limit', '256M');
            
                $bg     = $this->createImage(tomedia($poster['bg']));

                $target = imagecreatetruecolor(640,1008);                  
                                   
                imagecopy($target, $bg, 0, 0, 0, 0,640,1008);         
                imagedestroy($bg);      
                $data = json_decode(str_replace('&quot;', "'", $poster['data']), true);
                foreach ($data as $d) {
                    $d = p('poster')->getRealData($d);
                    
                    if ($d['type'] == 'head') {
                        $avatar = preg_replace('/\/0$/i', '/96', $member['avatar']);
                        $target = p('poster')->mergeImage($target, $d, $avatar);
                    } else if ($d['type'] == 'img') {
                        $target = p('poster')->mergeImage($target, $d, $d['src']);
                    } else if ($d['type'] == 'qr') {
                        $target = p('poster')->mergeImage($target, $d, tomedia($qr['current_qrimg']));
                    } else if ($d['type'] == 'nickname') {
                        $target = p('poster')->mergeText($target, $d, $member['nickname']);
                    } else {
                        if (!empty($goods)) {
                            if ($d['type'] == 'title') {
                                $target = p('poster')->mergeText($target, $d, $goods['title']);
                            } else if ($d['type'] == 'thumb') {
                                $thumb  = !empty($goods['commission_thumb']) ? tomedia($goods['commission_thumb']) : tomedia($goods['thumb']);
                                $target = p('poster')->mergeImage($target, $d, $thumb);
                            } else if ($d['type'] == 'marketprice') {
                                $target = p('poster')->mergeText($target, $d, $goods['marketprice']);
                            } else if ($d['type'] == 'productprice') {
                                $target = p('poster')->mergeText($target, $d, $goods['productprice']);
                            }
                        }
                    }
                }
                                    
                imagejpeg($target, $path . $file);
                imagedestroy($target);
                if ($qr['qrimg'] != $qr['current_qrimg']) {
                    pdo_update('sz_yi_poster_qr', array(
                        'qrimg' => $qr['current_qrimg']
                    ), array(
                        'id' => $qr['id']
                    ));
                }
            }
            $img = $_W['siteroot'] . "addons/sz_yi/data/activity_poster/" . $_W['uniacid'] . "/" . $file;
            if (!$upload) {
                return $img;
            }
            if ($qr['qrimg'] != $qr['current_qrimg'] || empty($qr['mediaid']) || empty($qr['createtime']) || $qr['createtime'] + 3600 * 24 * 3 - 7200 < time()) {
                $mediaid       = $this->uploadImage($path . $file);
                $qr['mediaid'] = $mediaid;
                pdo_update('sz_yi_poster_qr', array(
                    'mediaid' => $mediaid,
                    'createtime' => time()
                ), array(
                    'id' => $qr['id']
                ));
            }
            return array(
                'img' => $img,
                'mediaid' => $qr['mediaid']
            );
        }



        function myCreatePoster($poster, $openid, $qr, $upload = true)
        {           
            global $_W,$_GPC;              
            $path = IA_ROOT . "/addons/sz_yi/data/activity_poster/cardlist/" . $_W['uniacid'] . "/";
            if (!is_dir($path)) {
                load()->func('file');                
                mkdirs($path);               
            }
            $fontface=IA_ROOT . '/addons/sz_yi/static/fonts/msyh.ttf';
            $activity=pdo_fetch('select * from '.tablename('sz_yi_activity').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$_GPC['act_id']));
            if ($_GPC['actid'] && $poster['type'] == 7) {  //电子票
                $member=m('member')->getMember($openid);                                         
                $muser=m('activity')->getMuser($openid);                   
                $sginfo=pdo_fetch('select * from '.tablename('sz_yi_activity_signup').' where uniacid = :uniacid and actid = :id and openid = :openid',array(':uniacid'=>$_W['uniacid'],':id'=>$_GPC['actid'],':openid'=>$openid));
                $sginfo['data']=unserialize($sginfo['data']);
                if ($sginfo['item']) {
                    $titem=pdo_fetchcolumn('select title from '.tablename('sz_yi_activity_payitem').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$activity['id']));
                    if ($titem) {
                        $sginfo['item']=$titem;
                    }else{                   
                        $sginfo['item']='无';
                    }
                }else{                               
                    $sginfo['item']='全部';
                }
            }    
            if ($poster['type'] == 8) {
                $muser=m('activity')->getVisitingInfo($openid);
            }                        
            // if (!empty($qr['goodsid'])) {
            //     $goods = pdo_fetch('select id,title,thumb,commission_thumb,marketprice,productprice from ' . tablename('sz_yi_goods') . ' where id=:id and uniacid=:uniacid limit 1', array(
            //         ':id' => $qr['goodsid'],
            //         ':uniacid' => $_W['uniacid']
            //     ));
            //     if (empty($goods)) {
            //         m('message')->sendCustomNotice($member['openid'], '未找到商品，无法生成海报');
            //         exit;
            //     }                
            // }         
            $md5  = md5(json_encode(array(       
                'openid' => $member['openid'],               
                'bg' => $poster['bg'],                                 
                'data' => $poster['data'],
                'poster_tpl' => $_GPC['poster_tpl'],
                'act_id' => $_GPC['act_id'],
                'what' => $_GPC['what'],         
                'version' => 1
            )));                                                        
            $file = $md5 . '.png';                           

            if (!is_file($path . $file) || $qr['qrimg'] != $qr['current_qrimg']) {
                set_time_limit(0);
                @ini_set('memory_limit', '256M');
            
                $bg     = $this->createImage(tomedia($poster['bg']));

                if ($_GPC['actid']) {
                    $target = imagecreatetruecolor(640,380);                                                                   
                    imagecopy($target, $bg, 0, 0, 0, 0,640,380);                                 
                }else{                                                                                
                    $target = imagecreatetruecolor(640,1008);                        
                    imagecopy($target, $bg, 0, 0, 0, 0,640,1008);        
                }
                imagedestroy($bg);             
                $data = json_decode(str_replace('&quot;', "'", $poster['data']), true);
                foreach ($data as $d) {
                    $d = p('poster')->getRealData($d);
                    if ($d['type'] == 'head') {
                        $avatar = preg_replace('/\/0$/i', '/96', $member['avatar']);
                        $target = p('poster')->mergeImage($target, $d, $avatar);
                        
                    } else if ($d['type'] == 'img') {                        
                        $target = p('poster')->mergeImage($target, $d, $d['src']);

                    } else if ($d['type'] == 'qr') {         
                        $target = p('poster')->mergeImage($target, $d, tomedia($qr['current_qrimg']));

                    } else if ($d['type'] == 'nickname') {                                        
                        $tstr=$member['nickname'];
                        $tstr=autowrap($d['size'],0,$fontface,$tstr,$d['width']);
                        $target = p('poster')->mergeText($target, $d, $tstr); 

                    } else if ($d['type'] == 'realname') {                               
                        $tstr='姓名 : '.$muser['realname'];
                        $tstr=autowrap($d['size'],0,$fontface,$tstr,$d['width']);
                        $target = p('poster')->mergeText($target, $d, $tstr );

                    } else if ($d['type'] == 'job') {
                        $tstr='职务 : '.$muser['job'];
                        $tstr=autowrap($d['size'],0,$fontface,$tstr,$d['width']);
                        $target = p('poster')->mergeText($target, $d, $tstr);  

                    } else if ($d['type'] == 'mobile') {                                                       
                        $tstr='电话 : '.$muser['mobile'];
                        $tstr=autowrap($d['size'],0,$fontface,$tstr,$d['width']);
                        $target = p('poster')->mergeText($target, $d,$tstr );

                    } else if ($d['type'] == 'company') {                                       
                        $tstr='公司 : '.$muser['orgName'];
                        $tstr=autowrap($d['size'],0,$fontface,$tstr,$d['width']);
                        $target = p('poster')->mergeText($target, $d, $tstr );

                    } else if ($d['type'] == 'address') {
                        $tstr='地址 : '.$muser['address'];
                        $tstr=autowrap($d['size'],0,$fontface,$tstr,$d['width']);
                        $target = p('poster')->mergeText($target, $d, $tstr);

                    } else if ($d['type'] == 'supplier') {               
                        $muser['supplier']=autowrap($d['size'],0,$fontface,$muser['supplier'],$d['width']);
                        $target = p('poster')->mergeText($target, $d, $muser['supplier']);

                    } else if ($d['type'] == 'need') {                                     
                        $muser['need']=autowrap($d['size'],0,$fontface,$muser['need'],$d['width']);
                        $target = p('poster')->mergeText($target, $d, $muser['need']);

                    } else if ($d['type'] == 'iv_title') {                               
                        $activity['title']=autowrap($d['size'],0,$fontface,$activity['title'],$d['width']);
                        $target = p('poster')->mergeText($target, $d,$activity['title']);

                    } else if ($d['type'] == 'iv_org') {                 
                        $activity['relOrg']=autowrap($d['size'],0,$fontface,$activity['relOrg'],$d['width']);
                        $target = p('poster')->mergeText($target, $d,$activity['relOrg']);

                    } else if ($d['type'] == 'iv_contact') {                                                       
                        $activity['ContactOrg']=autowrap($d['size'],0,$fontface,$activity['ContactOrg'],$d['width']);
                        $target = p('poster')->mergeText($target, $d,$activity['ContactOrg']);

                    } else if ($d['type'] == 'iv_mobile') {
                                                               
                        $target = p('poster')->mergeText($target, $d,$activity['mobileOrg']);

                    } else if ($d['type'] == 'iv_time') {                                        
                        $target = p('poster')->mergeText($target, $d,date('Y年m月d日 H点i分',$activity['stime']));
                                                         
                    } else if ($d['type'] == 'iv_address') {                                 
                        $activity['address']=autowrap($d['size'],0,$fontface,$activity['address'],$d['width']);
                        $target = p('poster')->mergeText($target, $d, $activity['address']);

                    } else if ($d['type'] == 'iv_desc') {                                                                  
                        $activity['desc']=autowrap($d['size'],0,$fontface,$activity['desc'],$d['width']);
                        $target = p('poster')->mergeText($target, $d, $activity['desc']);    

                    } else if ($d['type'] == 'sg_title') {
                        $activity['title']=autowrap($d['size'],0,$fontface,$activity['title'],$d['width']);
                        $target = p('poster')->mergeText($target, $d,$activity['title']);

                    } else if ($d['type'] == 'sg_org') {                                        
                        $activity['relOrg']=autowrap($d['size'],0,$fontface,$activity['relOrg'],$d['width']);
                        $target = p('poster')->mergeText($target, $d,$activity['relOrg']);   

                    } else if ($d['type'] == 'sg_time') {                                             
                        $target = p('poster')->mergeText($target, $d, date('Y年m月d日 H点i分'));

                    } else if ($d['type'] == 'sg_address') {
                        $activity['address']=autowrap($d['size'],0,$fontface,$activity['address'],$d['width']);
                        $target = p('poster')->mergeText($target, $d, $activity['address']);

                    } else if ($d['type'] == 'tk_title') {                                 
                        $activity['title']=autowrap($d['size'],0,$fontface,$activity['title'],$d['width']);
                        $target = p('poster')->mergeText($target, $d, $activity['title']);

                    } else if ($d['type'] == 'tk_org') {                                                                  
                        $activity['relOrg']=autowrap($d['size'],0,$fontface,$activity['relOrg'],$d['width']);
                        $target = p('poster')->mergeText($target, $d, $activity['relOrg']);   

                    }else if ($d['type'] == 'tk_name') {                                               
                        $tstr='报名人 :'.$sginfo['data']['realname']['data'].' '.$sginfo['data']['mobile']['data'];
                        $tstr=autowrap($d['size'],0,$fontface,$tstr,$d['width']);
                        $target = p('poster')->mergeText($target, $d,$tstr);

                    }else if ($d['type'] == 'tk_option') {
                        $tstr='报名项 :'.$sginfo['item'];
                        $tstr=autowrap($d['size'],0,$fontface,$tstr,$d['width']);
                        $target = p('poster')->mergeText($target, $d,$tstr);      

                    }else if ($d['type'] == 'tk_time') {                                             
                        $tstr='时  间 :'.date('Y年m月d日 H点i分',$activity['stime']).'-'.date('Y年m月d日 H点i分',$activity['etime']);
                        $tstr=autowrap($d['size'],0,$fontface,$tstr,$d['width']);
                        $target = p('poster')->mergeText($target, $d,$tstr);

                    } else if ($d['type'] == 'tk_address') {
                        $tstr='地  点 :'.$activity['address'];
                        $tstr=autowrap($d['size'],0,$fontface,$tstr,$d['width']);
                        $target = p('poster')->mergeText($target, $d,$tstr);

                    }  
                }                                                                              
                                           
                imagejpeg($target, $path . $file);
                imagedestroy($target);
                if ($qr['qrimg'] != $qr['current_qrimg']) {
                    pdo_update('sz_yi_poster_qr', array(
                        'qrimg' => $qr['current_qrimg']
                    ), array(
                        'id' => $qr['id']
                    ));     
                }
            }
            $img = $_W['siteroot'] . "addons/sz_yi/data/activity_poster/cardlist/" . $_W['uniacid'] . "/" . $file;
            if (!$upload) {      
                return $img;         
            }
            if ($qr['qrimg'] != $qr['current_qrimg'] || empty($qr['mediaid']) || empty($qr['createtime']) || $qr['createtime'] + 3600 * 24 * 3 - 7200 < time()) {
                $mediaid       = $this->uploadImage($path . $file);
                $qr['mediaid'] = $mediaid;
                pdo_update('sz_yi_poster_qr', array(         
                    'mediaid' => $mediaid,
                    'createtime' => time()
                ), array(
                    'id' => $qr['id']
                ));
            }
            return array(
                'img' => $img,
                'mediaid' => $qr['mediaid']
            );
        }


        // function createPaper($poster, $member, $qr, $upload = true,$info)
        // {
        //     global $_W;

        //     $_var_110 = IA_ROOT . '/addons/sz_yi/static/fonts/msyh.ttf';
        //     $_var_125 = imagecolorallocate($_var_111, 240, 102, 0);

        //     $activity=pdo_fetch('select * from '.tablename('sz_yi_activity').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$actid));

        //     $path = IA_ROOT . "/addons/sz_yi/data/paper/" . $_W['uniacid'] . "/";
        //     if (!is_dir($path)) {
        //         load()->func('file');       
        //         mkdirs($path);
        //     }        
        //     if (!empty($qr['goodsid'])) {
        //         $goods = pdo_fetch('select id,title,thumb,commission_thumb,marketprice,productprice from ' . tablename('sz_yi_goods') . ' where id=:id and uniacid=:uniacid limit 1', array(     
        //             ':id' => $qr['goodsid'],
        //             ':uniacid' => $_W['uniacid']
        //         ));
        //         if (empty($goods)) {
        //             m('message')->sendCustomNotice($member['openid'], '未找到商品，无法生成海报');
        //             exit;
        //         }
        //     }
        //     $md5  = md5(json_encode(array(
        //         'openid' => $member['openid'],
        //         'goodsid' => $qr['goodsid'],
        //         'bg' => $poster['bg'], 
        //         'data' => $poster['data'],
        //         'version' => 1
        //     )));
        //     $file = $md5 . '.png';

        // //     if (!is_file($path . $file) || $qr['qrimg'] != $qr['current_qrimg']) {
        //         set_time_limit(0);
        //         @ini_set('memory_limit', '256M');       
                
        //         $target = imagecreatetruecolor(1008,640);                
        //         // var_dump();                     
        //         $re=$this->imagecropper(tomedia($poster['bg']),1008,640,7);             
        //         $poster['bg'] = $re;                                        

        //         // var_dump(getimagesize(tomedia($poster['bg'])));        
        //         // exit;           
        //         $bg     = $this->createImage(tomedia($poster['bg']));        
        //         imagecopy($target,$bg, 0, 0, 0, 0,1008,640);                                                                             
        //         imagedestroy($bg);                  

        //         $data = json_decode(str_replace('&quot;', "'", $poster['data']), true);

        //         $_var_130 = $info['title'];
        //         imagettftext($target, 25, 0, 430, 150, $_var_125, $_var_110, $_var_130);

        //         $_var_130 = $info['desc'];
        //         imagettftext($target, 25, 0, 300, 220, $_var_125, $_var_110, $_var_130);

        //         $_var_130 = '报名人 : '.$info['data']['realname']['data'].'  '.$info['data']['mobile']['data'];
        //         imagettftext($target, 22, 0, 120, 400, $_var_125, $_var_110, $_var_130);

        //         $_var_130 = '报名项 : '.$info['title'];       
        //         imagettftext($target, 22, 0, 120, 450, $_var_125, $_var_110, $_var_130);

        //         $_var_130 = '时   间 : '.date('Y年m月d日 H点i分',$info['stime']).' - '.date('Y年m月d日 H点i分',$info['etime']);             
        //         imagettftext($target, 22, 0, 120, 500, $_var_125, $_var_110, $_var_130);

        //         $_var_130 = '地   点 :'.$info['province'].$info['city'].$info['area'].$info['address'];
        //         imagettftext($target, 22, 0, 120, 550, $_var_125, $_var_110
    }