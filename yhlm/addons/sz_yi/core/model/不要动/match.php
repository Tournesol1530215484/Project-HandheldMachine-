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

================================================================`=============*/

if (!defined('IN_IA')) {

    exit('Access Denied');

}

class Sz_DYi_Match{

    public function createQrcode($url = '')
    {
        global $_W,$_GPC;
        $_var_100 = IA_ROOT . '/addons/sz_yi/data/activity_poster/match/' . $_W['uniacid'];
        if (!is_dir($_var_100)) {   
            load()->func('file');                         
            mkdirs($_var_100);      
        }
                        
        $_var_102 = 'picture_' . md5($url) . '.png';           
        $_var_103 = $_var_100 . '/' . $_var_102;     
        if (!is_file($_var_103)) {                
            require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
            QRcode::png($url, $_var_103, QR_ECLEVEL_H, 4);     
        }
        return $_W['siteroot'] . 'addons/sz_yi/data/activity_poster/match/' . $_W['uniacid'] . '/' . $_var_102;
    }
    function calcNo($id,$vote){
        global $_W;

        $no=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and vote > :vote ',array(':uniacid'=>$_W['uniacid'],':id'=>$id,':vote'=>$vote));
        $no=$no?:0;
        $no=$no+1;
        return $no;
    }
    function createCardPoster($openid,$id,$url=0) //创建活动海报
    {
        global $_W,$_GPC;

        $poster = pdo_fetch('select * from ' . tablename('sz_yi_poster') . ' where isdefault = 1 and uniacid=:uniacid and type = :id limit 1', array(
            ':uniacid' => $_W['uniacid'],
            ':id' => $id
        ));

        if (empty($poster)) {
            return '';
        }

        $qr=array(
            'current_qrimg'=>$this->createVQrcode($url),
            'mediaid'=>'',
            'qrimg'=>'',
            'createtime'=>''
        );

        if (empty($qr['current_qrimg'])) {
            return "";
        }

        return $this->myCreatePoster($poster, $openid, $qr, false);
    }
    public function createVQrcode($url = '')
    {

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
    function myCreatePoster($poster, $openid, $qr, $upload = true)
    {
        global $_W,$_GPC;
        $path = IA_ROOT . "/addons/sz_yi/data/activity_poster/match/" . $_W['uniacid'] . "/";
        if (!is_dir($path)) {
            load()->func('file');
            mkdirs($path);
        }

        $member=m('member')->getMember($openid);
        $fontface=IA_ROOT . '/addons/sz_yi/static/fonts/msyh.ttf';

        $activity=pdo_fetch('select * from '.tablename('sz_yi_activity').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$_GPC['act_id']));

        if ($poster['type'] == 11) {
            $activity=m('activity')->getact($_GPC['id'],5);

            $sginfo=m('match')->getMatchPlayer($_GPC['id'],$_GPC['sgid']);

            $tempdata=unserialize($sginfo['data']);

            $sginfo['name']=$tempdata['name']['data'];
            $sginfo['slogan']=$tempdata['slogan']['data'];
            $tempthumbs=unserialize($sginfo['thumbs']);
            $sginfo['cover']=tomedia($tempthumbs['0']);

        }
//        print_r($_GPC);exit;
        $md5  = md5(json_encode(array(
            'openid' => $member['openid'],
            'bg' => $poster['bg'],
            'data' => $poster['data'],
            'sgid' => $_GPC['sgid'],
            'id' => $_GPC['id'],
            'version' => 1
        )));

        $file = $md5 . '.png';

        if (!is_file($path . $file) || $qr['qrimg'] != $qr['current_qrimg']) {
            set_time_limit(0);
            @ini_set('memory_limit', '256M');

            $bg     = m('tools')->createImage(tomedia($poster['bg']));

            if ($_GPC['actid']) {
                $target = imagecreatetruecolor(640,380);
                imagecopy($target, $bg, 0, 0, 0, 0,640,380);
            }else{
                $target = imagecreatetruecolor(640,1008);
                imagecopy($target, $bg, 0, 0, 0, 0,640,1008);
            }
            imagedestroy($bg);
//            print_r($poster['data']);exit;
            $data = json_decode(str_replace('&quot;', "'", $poster['data']), true);
//            print_r($data);exit;
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
                } else if ($d['type'] == 'mat_title') {
                    $tstr=$activity['title'];
                    $tstr=autowrap($d['size'],0,$fontface,$tstr,$d['width']);
                    $target = p('poster')->mergeText($target, $d,$tstr);
                } else if ($d['type'] == 'mat_name') {
                    $tstr=$sginfo['name'];
                    $tstr=autowrap($d['size'],0,$fontface,$tstr,$d['width']);
                    $target = p('poster')->mergeText($target, $d,$tstr);
                } else if ($d['type'] == 'mat_slogan') {
                    $tstr=$sginfo['slogan'];
                    $tstr=autowrap($d['size'],0,$fontface,$tstr,$d['width']);
                    $target = p('poster')->mergeText($target, $d,$tstr);
                } else if ($d['type'] == 'mat_sgno') {
                    $tstr=$sginfo['sgno'];
                    $tstr=autowrap($d['size'],0,$fontface,$tstr,$d['width']);
                    $target = p('poster')->mergeText($target, $d,$tstr);
                } else if ($d['type'] == 'mat_cover') {             //选手封面
                    $target = p('poster')->mergeImage($target, $d,$sginfo['cover']);
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

        $img = $_W['siteroot'] . "addons/sz_yi/data/activity_poster/match/" . $_W['uniacid'] . "/" . $file;
        if (!$upload) {
            return $img;
        }

        if ($qr['qrimg'] != $qr['current_qrimg'] || empty($qr['mediaid']) || empty($qr['createtime']) || $qr['createtime'] + 3600 * 24 * 3 - 7200 < time()) {
            $mediaid       = m('tools')->uploadImage($path . $file);
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
    function getVisitingInfo($openid){
        global $_W;
        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':openid'=>$openid
        );
        $info=pdo_fetch('select * from '.tablename('sz_yi_visiting_info').' where uniacid = :uniacid and openid = :openid ',$params);
        return $info;
    }


    function setVisitingInfo($data,$openid){
        global $_W;      

        $exists=$this->getVisitingInfo($openid);
        if ($exists) {
            $params=array(
                'openid'=>$openid,
                'uniacid'=>$_W['uniacid'],
            );          
            
            $re=pdo_update('sz_yi_visiting_info',$data,$params);
        }else{
            $data['uniacid']=$_W['uniacid'];
            $data['openid']=$openid;
            $re=pdo_insert('sz_yi_visiting_info',$data);
        }

        return $re?:false;

    }
            
    function getAccountInfo($openid){            
        global $_W;

        $accountinfo=pdo_fetch('select * from '.tablename('sz_yi_match_account_info').' where uniacid = :uniacid and openid = :openid ',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
        return $accountinfo;
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

    function getPayitem($id,$do=true){
        global $_W;      
        if ($do) {
            $py=pdo_fetch('select * from '.tablename('sz_yi_match_payitem').' where uniacid = :uniacid and atid = :id and enabled  = 1 and ismobile = 1',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
        }else{
            $py=pdo_fetchall('select * from '.tablename('sz_yi_match_payitem').' where uniacid = :uniacid and atid = :id and enabled  = 1 ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));        
        }                
        return $py;      
    }
    
    function reSort($data, $parent_id = 0) {     
        $return = array();//不能用static    
        foreach($data as $v) {                                       
            if($v['pId'] == $parent_id) {
                $relationship=array(           
                    'children_num'=>$this->countChild($data,$v['id']),
                    'parent_num'=>1,  
                    'sibling_num'=>0     
                );                                      
                $v['children']=array();                  
                $v['relationship']=$relationship;
                foreach($data as $subv) {               
                    if($subv['pId'] == $v['id']) {
                        $v['children']= $this->reSort($data, $v['id']);         
                        break;       
                    }                
                }                        
                $return[] = $v;                              
            }                                  
        }
        return $return;      
    }


    function setMuser($data,$params)
    {
        global $_W;
        $re=pdo_update('sz_yi_member_user',$data,$params);
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
            $info=pdo_fetch('select * from '.tablename('sz_yi_match').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$atid));                 
        }else if($type == 2){
            $info=pdo_fetch('select * from '.tablename('sz_yi_match_picture').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$atid));                 
        }                           
        return $info?:array();                  
    }

    function countChild($arr=array(),$id){
        $num=0;
        foreach ($arr as $key => $value) {
            if ($value['pId'] == $id) {      
                $num+=1;         
            }
        }

        return $num;
    }

    function setBrowse($atid,$type=1){
        global $_W;
        $at=$this->getact($atid,$type);

        if (!$at) {
            return false;
        }

        if ($type==1) {

            $info=pdo_update('sz_yi_match',array('browse'=>$at['browse']+1),array('id'=>$at['id'],'uniacid'=>$_W['uniacid'])); 

        }else if($type == 2){

            $info=pdo_update('sz_yi_match_picture',array('browse'=>$at['browse']+1),array('id'=>$at['id'],'uniacid'=>$_W['uniacid']));

        }else if($type == 3){
            
            $info=pdo_update('sz_yi_member_user',array('browse'=>$at['browse']+1),array('id'=>$at['id'],'uniacid'=>$_W['uniacid']));

        }
        
        return $info?true:false;


    }

    function getSignUp($actid,$openid,$condition=''){
        global $_W;

        $params=array(
            ':uniacid'=>$_W['uniacid'],         
            ':openid'=>$openid,         
            ':id'=>$actid                    
        );                                   
        $sql='select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and openid = :openid and actid = :id ';
        $sql.=$condition;
        
        $re=pdo_fetch($sql,$params);

        if ($re) {
            $minsql='select data,thumbs from '.tablename('sz_yi_match_signup_data').' where uniacid = :uniacid and sgid = :sgid';
            $minparams=array(':uniacid'=>$_W['uniacid'],':sgid'=>$re['id']);
            $data=pdo_fetch();
        }

        $re=$re?:array();
        return $re;             
    }

    function auditSuppleir($status,$id){
        global $_W;

        $openid = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_af_supplier') . " where uniacid={$_W['uniacid']} and id={$id}");
        if (empty($openid)) {       
            m('tools')->tip('没有该条申请记录');
        } else {                
            pdo_update('sz_yi_af_supplier', array('status' => $status), array('id' => $id, 'uniacid' => $_W['uniacid']));
            if ($status == 1) {
                $msg = '驳回申请成功';
            } else {
                $data = array();
                $msg = '审核通过成功';
                $af_user = pdo_fetch('select * from ' . tablename('sz_yi_af_supplier') . " where uniacid={$_W['uniacid']} and id={$id}");
                $data['uid'] = user_register(array('username' => $af_user['username'], 'password' => $af_user['password']));
                $pwd = pdo_fetch('select password from ' . tablename('users') . ' where uid=:uid', array(':uid' => $data['uid']));
                // $perm_role = pdo_fetch ('select id,status from ' . tablename('sz_yi_perm_role') . ' where status1=1 and status=1 and uniacid = '.$_W['uniacid']); 50 
                $data['password'] = $pwd['password'];
                $data['username'] = $af_user['username'];
                $data['company'] = $af_user['qq'];
                $data['roleid'] = 50;
                $data['status'] = 1; 
                $data['uniacid'] = $_W['uniacid'];
                $data['perms'] = '';
                $data['provance']=$af_user['province']; 
                $data['city']   =$af_user['city'];
                $data['area']=$af_user['district'];
                $data['openid'] = $openid; 
                
                pdo_insert('uni_account_users', array('uid' => $data['uid'], 'uniacid' => $af_user['uniacid'], 'role' => 'operator'));
                $arr=array(
                    'uniacid'=>$af_user['uniacid'],
                    'uid'=>$data['uid'],
                    'openid'=>$af_user['openid'],
                    'mobile'=>$af_user['mobile'],
                    'realname'=>$af_user['realname'],
                    'orgName'   => $af_user['qq'],
                );  
                pdo_insert('sz_yi_member_user',$arr);
                $data['muserid']=pdo_insertid();     //易货商家 通过审核后的标记
                pdo_insert('sz_yi_perm_user', $data);
            }
                m('tools')->tip($msg);       
        }
    }

    function getAuthor($atid){
        global $_W;
        $act=pdo_fetch('select * from '.tablename('sz_yi_match').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$atid));
        $m=m('member')->getMember($act['openid']);
        return $m;
    }

    function trArrayKey($arr=array()){
        $temp=array();
        foreach ($arr as $key => $value) {
            $temp['m'.$key]=$value;
        }
        return $temp;
    }

    public function sendMsg($openid = '', $oldlevel = null, $level = null)
    {
        global $_W, $_GPC;
        $member     = m('member')->getMember($openid);
        $usernotice = unserialize($member['noticeset']);
        if (!is_array($usernotice)) {
            $usernotice = array();
        }
        $shop      = m('common')->getSysset('shop');
        $tm        = m('common')->getSysset('notice');
        $detailurl = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=sz_yi&do=member';
        if (strexists($detailurl, '/addons/sz_yi/')) {
            $detailurl = str_replace("/addons/sz_yi/", '/', $detailurl);
        }
        if (strexists($detailurl, '/core/mobile/order/')) {
            $detailurl = str_replace("/core/mobile/order/", '/', $detailurl);
        }
        if (!$level) {
            $level = m('member')->getLevel($openid);
        }
        $defaultlevelname = empty($shop['levelname']) ? '普通会员' : $shop['levelname'];
        $msg              = array(
            'first' => array(
                'value' => "亲爱的" . $member['nickname'] . ', 恭喜您成功升级！',
                "color" => "#4a5077"
            ),
            'keyword1' => array(
                'title' => '任务名称',
                'value' => '会员升级',
                "color" => "#4a5077"
            ),
            'keyword2' => array(
                'title' => '通知类型',
                'value' => '您会员等级从 ' . $defaultlevelname . ' 升级为 ' . $level['levelname'] . ', 特此通知!',
                "color" => "#4a5077"
            ),
            'remark' => array(
                'value' => "\r\n您即可享有" . $level['levelname'] . '的专属优惠及服务！',
                "color" => "#4a5077"
            )
        );
        if (!empty($tm['upgrade']) && empty($usernotice['upgrade'])) {
            m('message')->sendTplNotice($openid, $tm['upgrade'], $msg, $detailurl);
        } else if (empty($usernotice['upgrade'])) {
            m('message')->sendCustomNotice($openid, $msg, $detailurl);
        }
    }

    function borwseStatis($heMember,$openid){
        global $_W;                                            
        if ($heMember['openid']!=$openid) {
            $tre=$this->getBStatis($heMember['id']);
            if ($tre) {
                pdo_update('sz_yi_match_browse_statis',array('statis'=>$tre['statis']+1),array('id'=>$tre['id'],'uniacid'=>$_W['uniacid']));                              

            }else{
                $browsestatis=array(
                    'uniacid'=>$_W['uniacid'],
                    'mid'=>$heMember['id'],
                    'date'=>date('Ymd'),         
                    'statis'=>1      
                );                       
                pdo_insert('sz_yi_match_browse_statis',$browsestatis); 
            }
        }
    }

    function getBStatis($mid,$statis=false){
        global $_W;              
        $params=array(                      
            ':uniacid'=>$_W['uniacid'],
            ':mid'=>$mid,         
            ':date'=>date('Ymd')                 
        );
        $exists=pdo_fetch('select * from '.tablename('sz_yi_match_browse_statis').' where uniacid = :uniacid and mid = :mid and date = :date',$params);
        if ($exists) {                                                      
            return $statis?$exists['statis']:$exists;        
        }                          
        return false;
    } 

    public function gettotal($openid,$set=null,$stime=null,$etime=null){
        
        global $_W;
        $member = p('commission')->getInfo($openid, array());
        $agentLevel = p('commission') ->getLevel($member['openid']);
        $level = intval($set['level']);
        $orders     = array();
        $level1     = $member['level1'];
        $level2     = $member['level2'];
        $level3     = $member['level3'];
        if ($level >= 1) {
            // $level1_memberids = pdo_fetchall('select id from ' . tablename('sz_yi_member') . ' where uniacid=:uniacid and agentid=:agentid', array(':uniacid' => $_W['uniacid'], ':agentid' => $member['id']), 'id');
            $level1_orders = pdo_fetchall('select commission1,o.id,o.createtime,o.price,og.commissions from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on og.orderid=o.id ' . " where o.uniacid=:uniacid and o.agentid=:agentid {$condition} and og.status1>=0 and o.status>0 and  og.nocommission=0 and o.createtime >= :stime and o.createtime <= :etime ", array(':uniacid' => $_W['uniacid'], ':agentid' => $member['id'],':stime'=>$stime,':etime'=>$etime));
            foreach ($level1_orders as $o) {
                if (empty($o['id'])) {
                    continue;
                }                
                $commissions = iunserializer($o['commissions']);
                $commission = iunserializer($o['commission1']);
                
                if (empty($commissions)) {
                    $commission_ok = isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
                } else {
                    $commission_ok = isset($commissions['level1']) ? floatval($commissions['level1']) : 0;
                }
                foreach ($orders as &$or) {
                    if ($or['id'] == $o['id'] && $or['level'] == 1) {
                        $or['commission'] += $commission_ok;
                        break;
                    }
                }
                unset($or);
            
                $commissioncount += $commission_ok;
            }
            
        }
        if ($level >= 2) {
            if ($level1 > 0) {
                $level2_orders = pdo_fetchall('select commission2 ,o.id,o.createtime,o.price,og.commissions   from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on og.orderid=o.id ' . " where o.uniacid=:uniacid and o.agentid in( " . implode(',', array_keys($member['level1_agentids'])) . ")  {$condition}  and og.status2>=0 and o.status>0 and og.nocommission=0 and o.createtime >= :stime and o.createtime <= :etime ", array(':uniacid' => $_W['uniacid'],':stime'=>$stime,':etime'=>$etime));
                foreach ($level2_orders as $o) {
                    if (empty($o['id'])) {
                        continue;
                    }
                    $commissions = iunserializer($o['commissions']);
                    $commission = iunserializer($o['commission2']);
                    if (empty($commissions)) {
                        $commission_ok = isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
                    } else {
                        $commission_ok = isset($commissions['level2']) ? floatval($commissions['level2']) : 0;
                    }
                    foreach ($orders as &$or) {
                        if ($or['id'] == $o['id'] && $or['level'] == 2) {
                            $or['commission'] += $commission_ok;
                            break;
                        }
                    }
                    unset($or);
                 
                    $commissioncount += $commission_ok;
                }
            }
        }
        
        if ($level >= 3) {
            
            if ($level2 > 0) {
                $level3_orders = pdo_fetchall('select commission3 ,o.id,o.createtime,o.price,og.commissions  from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join  ' . tablename('sz_yi_order') . ' o on og.orderid=o.id ' . ' where o.uniacid=:uniacid and o.agentid in( ' . implode(',', array_keys($member['level2_agentids'])) . ")  {$condition} and og.status3>=0 and o.status>0 and og.nocommission=0 and o.createtime >= :stime and o.createtime <= :etime ", array(':uniacid' => $_W['uniacid'],':stime'=>$stime,':etime'=>$etime));
                
                foreach ($level3_orders as $o) {
                    if (empty($o['id'])) {
                        continue;
                    }
                    $commissions = iunserializer($o['commissions']);
                    $commission = iunserializer($o['commission3']);
                    if (empty($commissions)) {
                        $commission_ok = isset($commission['level' . $agentLevel['id']]) ? $commission['level' . $agentLevel['id']] : $commission['default'];
                    } else {
                        $commission_ok = isset($commissions['level3']) ? floatval($commissions['level3']) : 0;
                    }
                    foreach ($orders as &$or) {
                        if ($or['id'] == $o['id'] && $or['level'] == 3) {
                            $or['commission'] += $commission_ok;
                            break;
                        }
                    }
                    unset($or);
                               
                    $commissioncount += $commission_ok;
                }
            }   
        }
        usort($orders, 'sortByCreateTime');
        $commissioncount = number_format($commissioncount, 2);
        return $commissioncount;
    
    }
    function str2arr1 ($str){   //报名所用

        $arr = explode(",",$str);
        $r = array();
        foreach ($arr as $val ){
            $t = explode("=",$val);
            $r[trim($t[0])]= trim($t[1]);
        }
        return $r;
    }
    public function getMatchPlayer($id,$openid){        //检查该会员是否参加过该比赛
        global $_W;


        if (is_numeric($openid)) {
            $sql='select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and id = :openid ';
        }else{
            $sql='select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and openid = :openid ';
        }
        $params=array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':id'=>$id);

        $exists=pdo_fetch($sql,$params);

        if ($exists) {

            $no=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and actid = :id and vote > :vote ',array(':uniacid'=>$_W['uniacid'],':id'=>$id,':vote'=>$exists['vote']));
            $no=$no?:0;
            $exists['no']=$no+1;
        }
        return $exists?:false;

    }
//    3.25注释
//    public function getMatchPlayer($id,$openid){
//        global $_W;
//
//        $condition=' and ms.uniacid = :uniacid and ms.actid = :id and ms.openid = :openid ';
//        $params=array(
//            ':uniacid'=>$_W['uniacid'],
//            ':id'=>$id,
//            ':openid'=>$openid,
//        );
//
//        $exists=pdo_fetch('select ms.*,msd.data,msd.thumbs from '.tablename('sz_yi_match_signup').' ms left join '.tablename('sz_yi_match_signup_data').' msd on ms.id = msd.sgid where 1 '.$condition,$params);
//
//        return $exists?:false;
//    }
                 
}

