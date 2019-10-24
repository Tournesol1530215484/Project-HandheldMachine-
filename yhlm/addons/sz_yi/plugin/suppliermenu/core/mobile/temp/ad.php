<?php

global $_W, $_GPC;

$op = empty($_GPC['op']) ? 'display': $_GPC['op'];
$ac = $_GPC['ac'];
$popenid        = m('user')->islogin();
$openid = m('user')->getOpenid();
$openid = $openid?$openid:$popenid;
$member = m('member')->getMember($openid);
if (true) {
    if ($_GPC['merchtype'] == '1') {
        $merch=p('bonus')->getMerch($openid,'common');
    }elseif($_GPC['merchtype'] == '2'){
        $merch=p('bonus')->getMerch($openid,'merch');
    }elseif($_GPC['merchtype'] == '3'){
        $merch=p('bonus')->getMerch($openid,'deal');
    }else{
        // m('tools')->tip('非法请求');
    }
}
$sets=m('tools')->getSet();

if ( $op == 'add'){ 
    $cate=pdo_fetchall('select * from '.tablename('sz_yi_ad_type').' where uniacid = :uniacid and status = 1',array(':uniacid'=>$_W['uniacid']));
    foreach ($cate as $key => $value) {
        if ($value['title'] == '推荐' || $value['title'] == '附近' || $value['title'] == '最新') {
            unset($cate[$key]);
        }
    }
    $currency['credit2']=m('member')->getCredit($openid,'credit2');
    $currency['credit3']=m('member')->getCredit($openid,'credit3');
    $id=$_GPC['id'];
    if ($ac == 'getad') {
        if ($id) {
            $ad=m('tools')->getAd($id);
        }
        if ($ad) {          
            $ad['thumb']=unserialize($ad['thumb']);
            foreach ($ad['thumb'] as $key => $value) {

               $ad['thumb'][$key] =tomedia($value);
            }
            $tempmerch=p('bonus')->getMerch($ad['recuid']);
            $temparr=array();
            if ($tempmerch['dealmerchid'] > 0) {
            	$du=pdo_fetch('select * from '.tablename('sz_yi_dealmerch_user').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$ad['recuid']));
            	$ad['merchname']=$du['merchname'];
            }elseif($tempmerch['merchid'] > 0){
				$du=pdo_fetch('select * from '.tablename('sz_yi_merch_user').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$ad['recuid']));
            	$ad['merchname']=$du['merchname'];
            }elseif($tempmerch['merchid'] == 0 && $tempmerch['dealmerchid'] == 0){
				$du=pdo_fetch('select * from '.tablename('sz_yi_store_data').' where uniacid = :uniacid and storeid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$ad['recuid']));
            	$ad['merchname']=$du['storename'];
            }
            $ad['stime']=$ad['stime'] > 0 ? date('Y-m-d H:i:s',$ad['stime']):'';
            $ad['links']=$ad['link'];
            $ad['etime']=$ad['etime'] > 0 ? date('Y-m-d H:i:s',$ad['etime']):'';
            $ad['desc']=strip_tags(html_entity_decode($ad['desc']));
            show_json(1,$ad);            
        }
        show_json(0,'没有该条广告');
    }
    //获取商品
    if ($ac == 'getgoods') {    
        $pindex=max(1,intval($_GPC['page']));
        $psize =10;

        $tuid=array();
        $merchall=pdo_fetchall('select * from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and openid = :openid and status = 1',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
        foreach ($merchall as $key => $value) {
            $tuid[]=$value['uid'];
        }
        unset($merchall);
        $sql='select * from '.tablename('sz_yi_goods').' where uniacid = :uniacid and supplier_uid in ('.implode(',',$tuid).') and status = 1 ';
        $params=array(
            'uniacid'=>$_W['uniacid']
        );
        $sql.=' limit '.($pindex-1) * $psize.' , '.$psize;
        $list=pdo_fetchall($sql,$params);
        if ($list) {
            foreach ($list as $key => $value) {
                $list[$key]['thumb']=tomedia($value['thumb']);

                $temp=pdo_fetch('select * from '.tablename('sz_yi_goods_option').' where uniacid = :uniacid and goodsid = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$value['id']));

                $list[$key]['marketprice']=$temp['marketprice'];
                $list[$key]['shopprice']=$temp['shopprice'];
            }    
            show_json(1,array('list'=>$list,'total'=>count($list),'pagesize'=>$psize));
        }
        show_json(0,array('list'=>$list));
    }

    //获取商家
    if ($ac == 'getmerch') {    
        $pindex=max(1,intval($_GPC['page']));
        $psize =10;	 	
        $tuid=array(); 	 	
        $list=pdo_fetchall('select * from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and openid = :openid and status = 1 and muserid = 0',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));
        if ($list) {	 	 	 	 		 		 
            foreach ($list as $key => $value) {
                if ($value['dealmerchid'] > 0) {
                   	$list[$key]['merchtype']='平台易货店'; 	 	  
                   	$tempinfo=pdo_fetch('select * from '.tablename('sz_yi_dealmerch_user').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$value['dealmerchid']));
                    $list[$key]['logo']=tomedia($tempinfo['logo'])?:tomedia($tempinfo['img']);
                    $list[$key]['logo']=!empty($list[$key]['logo'])?$list[$key]['logo']:$member['avatar'];
                   	$list[$key]['merchname']=$tempinfo['merchname'];         
                   	$list[$key]['favorite']=m('tools')->statisFavorite($value['uid']);         
                   	$list[$key]['sales']=m('tools')->statisSales($value['uid']);         
                }elseif($value['merchid'] > 0){
                   	$list[$key]['merchtype']='本地商家店';  	 	 
                    $tempinfo=pdo_fetch('select * from '.tablename('sz_yi_merch_user').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$value['merchid']));
                    $list[$key]['logo']=tomedia($tempinfo['logo'])?:tomedia($tempinfo['img']);
                    $list[$key]['logo']=!empty($list[$key]['logo'])?$list[$key]['logo']:$member['avatar']; 
                	$list[$key]['merchname']=$tempinfo['merchname'];  
                	$list[$key]['favorite']=m('tools')->statisFavorite($value['uid']);         
                   	$list[$key]['sales']=m('tools')->statisSales($value['uid']);  
                }elseif($value['dealmerchid'] == 0 and $value['merchid'] == 0){
                   	$list[$key]['merchtype']='全国卖货店';  	 	 
                	$tempinfo=pdo_fetch('select * from '.tablename('sz_yi_store_data').' where uniacid = :uniacid and storeid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$value['uid']));
                    $list[$key]['logo']=tomedia($tempinfo['logo'])?:tomedia($tempinfo['signboard']);
                    $list[$key]['logo']=!empty($list[$key]['logo'])?$list[$key]['logo']:$member['avatar']; 
                    $list[$key]['merchname']=$tempinfo['storename'];
                    $list[$key]['favorite']=m('tools')->statisFavorite($value['uid']);         
                   	$list[$key]['sales']=m('tools')->statisSales($value['uid']);  
                }
            }	 	 
            show_json(1,array('list'=>$list,'total'=>count($list),'pagesize'=>$psize));
        }
        show_json(0,array('list'=>$list));
    }

    //新增修改
    if ($ac == 'sub') {

        if ($id) {
            $ad=m('tools')->getAd($id);
        }
        $data=array(         
            'uid'       =>$merch['uid'],
            'recuid'    =>$_GPC['uid'],
            'uniacid'   =>$_W['uniacid'],
            'title'     =>$_GPC['title'],
            'core'      =>$_GPC['core'],
            'status'    =>0,
            'cate'      =>intval($_GPC['adcate']),
            'thumb'     =>serialize($_GPC['post1']),
            'mobile'    =>$_GPC['mobile'],
            'desc'      =>$_GPC['desc'],
            'video'     =>trim($_GPC['video']),
            'link'      =>trim($_GPC['link']),
            'usermax'   =>trim($_GPC['usermax']),
            'daymax'    =>trim($_GPC['daymax']),
            'ctime'     =>time(),       
            'putInType' =>$_GPC['putInType'],       //投放设置
            'goodsid'   =>trim($_GPC['goodsid'],','),
            'money'     =>floatval(floatval($_GPC['bonus']) * 0.3 ),
            'bonus'     =>floatval($_GPC['bonus']),
            'residual'  =>floatval($_GPC['bonus']),
            'gender'    =>intval($_GPC['gender']),
            'minimum'   =>0,        
            'maximum'   =>0,
            'minage'    =>0,
            'maxage'    =>0,
            'stime'     =>strtotime($_GPC['stime']),
            'etime'     =>strtotime($_GPC['etime']),
        );
        
        if ($_GPC['status'] == 1) {
            $data['status']='0';
            $data['now']='0';
        }else if ($_GPC['status'] == 2){
            $data['status']='0';
            $data['now']='1';   //立即投放 如果开始时间到了 且now =1 status =1 将开始播放
        }else if($_GPC['status'] == 3){
            $data['status']=3;
        }               

        if ($id) {
            $oldad=m('tools')->getad($id);
            $data['type']=$oldad['type'];
        }else{
            $data['type']=$member['default_ad_model'];
        }

        if (!empty($_GPC['setage'])) {
            $data['minage'] =intval($_GPC['minage']);
            $data['maxage'] =intval($_GPC['maxage']);
        }

        if (!empty($_GPC['setincome'])) {
            $data['minimum'] = floatval($_GPC['minimum']);
            $data['maximum'] = floatval($_GPC['maximum']);
        }
        if ($member['default_ad_model'] == 2) {
            $data['video']=trim($_GPC['video']);
        }else if ($member['default_ad_model'] == 3){
            $data['outside']=trim($_GPC['outside']);
        }

        if (!empty($_GPC['national'])) {
            $data['national']=$_GPC['national'];
        }else{
            if ($_GPC['province'] || $_GPC['city'] || $_GPC['area']) {
                $data['province']=$_GPC['province'];
                $data['city']=$_GPC['city'];
                $data['area']=$_GPC['area'];
                $data['national']='0';
            }else{
                $data['national']='1';
            }
        }

        $str='';
        if($data['putInType'] == '1'){
           $money=m('member')->getCredit($openid,'credit2');
           $str='credit2';
        }else if ($data['putInType'] == '2'){
           $money=m('member')->getCredit($openid,'credit3');
           $str='credit3';
        }else{
            show_json(0,'添加失败');
        }

        $fee=floatval($data['money']);
        if ($fee > $money) {
            show_json(0,'添加失败，你的余额不足发布广告');
        }else{                  
           // $money=m('member')->setCredit($openid,$str,-$fee);       扣除
        }

        // show_json(0,array('GPC'=>$_GPC,'data'=>$data,'ad'=>$ad));

        if (empty($ad)) {
            pdo_insert('sz_yi_ad_model',$data);
            $id=pdo_insertid();

            $log=[
                'uniacid'=>$_W['uniacid'],
                'ad_id'=>$id,
                'sub_time'=>time()
            ];

            if ($id) {
                $adsn='31';         //获取id 设置编号
                for ($i=0; $i <  7-strlen($id); $i++) { 
                    $adsn.='0';
                }
                $adsn.=$id;         
                pdo_update('sz_yi_ad_model',array('adsn'=>$adsn),array('id'=>$id));
                m('log')->putAdLog($log);  //记录日志
                show_json(1,'添加成功');
            }else{
                show_json(0,'添加失败');      
            }
        }else{  //修改
            $re=pdo_update('sz_yi_ad_model',$data,array('id'=>$ad['id']));
            $log=[
                'uniacid'=>$_W['uniacid'],
                'ad_id'=>$ad['id'],
                'sub_time'=>time()
            ];
            m('log')->putAdLog($log);
            $re?show_json(1,'修改成功!'):show_json(1,'修改失败!');
        }
        
    }
    include $this->template('addAd');
    exit;
}else if($op == 'demo'){                //广告模版

   include $this->template('demoAd');
    exit;
}else if($op == 'draft'){               //草稿  
    $pindex=max(1,intval($_GPC['page']));
    $psize=10;
    if ($ac == 'getDraft') {
        $list=m('tools')->getManyAd(' and status = 3 and uid = :uid',array(':uid'=>$merch['uid']),$pindex,$psize);  // 1 , 5

        if ($list) {
            foreach ($list as $key => $value) {
                $list[$key]['stime']=date('Y-m-d H:i:s',$list[$key]['stime']);
                $list[$key]['etime']=date('Y-m-d H:i:s',$list[$key]['etime']);
                $list[$key]['ctime']=date('Y-m-d H:i:s',$list[$key]['ctime']);
            }
            show_json(1,array('list'=>$list,'total'=>count($list),'pagesize'=>$psize));
        }       
        show_json(0,array('list'=>array(),'total'=>count($list),'pagesize'=>$psize));
    }        
    include $this->template('draft');
    exit;
}else if($op == 'list'){
               //列表
    if ($ac == 'put') {
        $id=$_GPC['id'];
        $ad=m('tools')->getAd($id);
        if ($ad) {
            $exists=pdo_update('sz_yi_ad_model',array('now'=>'1'),array('id'=>$id));
            $exists?show_json(1,'投放成功'):show_json(0,'投放失败');
        }
        show_json(0,'找不到该广告');
    }if ($ac =='getad') {
        $pindex=max(1,intval($_GPC['page']));
        $psize =10;

        $sql='select * from '.tablename('sz_yi_ad_model').' where uniacid = :uniacid and uid = :uid ';
        $params=array(
            'uniacid'=>$_W['uniacid'],
            'uid'=>$merch['uid']
        );
        $condition='';
        if ($_GPC['status']) {
            if ($_GPC['status'] == 1) {
                $condition.=' and status = 1 ';
            }else if($_GPC['status'] == 2){
                $condition.=' and status = 0 ';
            }else if ($_GPC['status'] == 3){
                $condition.=' and status = 2 ';
            }
        }
        
        $sql.=$condition.' limit '.($pindex-1) * $psize.' , '.$psize;
        $list=pdo_fetchall($sql,$params);
        if ($list) {
            foreach ($list as $key => $value) {
                $list[$key]['thumb']=unserialize($value['thumb']);
                $list[$key]['etime']=date('Y-m-d H:i:s',$value['etime']);
                $log=pdo_fetchcolumn('select max(audit_time) from '.tablename('sz_yi_ad_for_log').' where uniacid= :uniacid and ad_id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$value['id']));
                if ($log) {
                    $list[$key]['audit_time']=date('Y-m-d H:i:s',$log);
                }else{
                    $list[$key]['audit_time']='暂未审核';
                }
                foreach ($list[$key]['thumb'] as $k => $v) {
                    $list[$key]['thumb'][$k]=tomedia($v);
                }
            }  
            show_json(1,array('list'=>$list,'total'=>count($list),'pagesize'=>$psize));
        }
        show_json(0,array('list'=>$list,'total'=>count($list),'pagesize'=>$psize));
    }
    include $this->template('listAd');
    exit;
}else if($op == 'bonusAd'){            //红包 换货码广告

    if ($ac == 'getbonus') {
        $pindex=max(1,intval($_GPC['page']));
        $psize =10;
        $condition='';
        $sql='select * from '.tablename('sz_yi_ad_model').' where uniacid = :uniacid and uid = :uid ';
        $params=array(
            'uniacid'=>$_W['uniacid'],
            'uid'=>$merch['uid']
        );

        if ($_GPC['status']) {
            if ($_GPC['status'] == 1) {
                $condition.=' and status = 1 and now = 1 and stime < :time and etime > :time '; //playing
                $params[':time']=time();     
            }else if($_GPC['status'] == 2){ 
                $condition.='and status = 1 and ( stime > :time or now = 0 )'; 
                $params[':time']=time();         
            }else if ($_GPC['status'] == 3){    
                $condition.=' and status = 4 and now = 0 '; //playend 领取完最后一个将结束       
            }else if ($_GPC['status'] == 4){    
                $condition.=' and etime < :time '; 
                $params[':time']=time();        
            }   
        }   
        
        $sql.=$condition.' limit '.($pindex-1) * $psize.' , '.$psize;
        $list=pdo_fetchall($sql,$params);
        // pdo_debug();             
        if ($list) {
            foreach ($list as $key => $value) {
                $list[$key]['timed']= $_GPC['status'] == 4 ? true:false;
                $list[$key]['url']=$this->createPluginMobileUrl('suppliermenu/ad',array('op'=>'bonusAdDetail','merchtype'=>$_GPC['merchtype'],'id'=>$value['id']));          
                $list[$key]['ratio']=floatval($value['got'] / $value['bonus']) * 100;
                $list[$key]['thumb']=unserialize($value['thumb']);
                $list[$key]['etime']=date('Y-m-d H:i:s',$value['etime']);
                $log=pdo_fetchcolumn('select max(audit_time) from '.tablename('sz_yi_ad_for_log').' where uniacid= :uniacid and ad_id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$value['id']));
                if ($log) {
                    $list[$key]['audit_time']=date('Y-m-d H:i:s',$log);
                }else{
                    $list[$key]['audit_time']='暂未审核';
                }
                foreach ($list[$key]['thumb'] as $k => $v) {
                    $list[$key]['thumb'][$k]=tomedia($v);
                }
            }  
            show_json(1,array('list'=>$list,'total'=>count($list),'pagesize'=>$psize));
        }
        show_json(0,array('list'=>$list,'total'=>count($list),'pagesize'=>$psize));   
    }
    include $this->template('bonusAd');
    exit;
}else if ($op == 'bonusAdDetail'){
    $id=intval($_GPC['id']);        
    if ($_W['isajax']) {
        $ad=m('tools')->getAd($id);
        $pindex=max(1,intval($_GPC['page']));
        $psize=10;
        $condition='';
        $params=array(
            ':uniacid'=>$_W['uniacid'],
            ':id'=>$ad['id'],
            ':version'=>$ad['version']
        );
        
        $sql='select ob.*,m.realname,m.nickname,m.mobile from '.tablename('sz_yi_obtain_bonus').' ob left join '.tablename('sz_yi_member').' m on ob.openid = m.openid where ob.uniacid = :uniacid and ob.adid = :id and ob.version = :version ';
        $sql.=' order by ob.id desc ';
        $sql.=' limit '.($pindex -1 )* $psize.','.$psize;
        $list=pdo_fetchall($sql,$params);
        if ($list) {
            foreach ($list as $key => $value) {                         
                $list[$key]['realname']=$value['realname']?:$value['nickname'];
                $list[$key]['realname']=m('tools')->strReplace($list[$key]['realname']);
                $list[$key]['mobile']=$value['mobile'] ? m('tools')->mobileReplace($value['mobile']) : '';
                $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
            }
            show_json(1,array('list'=>$list,'total'=>count($list),'pagesize'=>$pindex));
        }
            show_json(0,array('list'=>array(),'total'=>count(array()),'pagesize'=>$pindex));         
    }      
    include $this->template('bonusAdDetail');         
    exit;
}else if ($op == 'delete'){
    $id=intval($_GPC['id']);
    $re=pdo_delete('sz_yi_ad_model',array('id'=>$id));
    $re?show_json(1,'删除成功1'):show_json(0,'删除失败!');
}else if ($op == 'set'){
    $id=intval($_GPC['value']);
    if ($id) {
        $sure=pdo_update('sz_yi_member',array('default_ad_model'=>$id),array('openid'=>$openid));
        $sure?show_json(1,'设置成功!'):show_json(0,'设置失败!');
    }else{  
        show_json(0,'非法参数');   
    }
}else if($op == 'preview'){
    $id=$_GPC['id'];
    if ($id) {
        $ad=m('tools')->getAd($id);
        $url=$this->createPluginMobileUrl('suppliermenu/ad',array('op'=>'showPreview','merchtype'=>$_GPC['merchtype'],'id'=>$ad['id']));
    }else{
        $data=array(         
            'uid'       =>$merch['uid'],
            'uniacid'   =>$_W['uniacid'],
            'title'     =>$_GPC['title'],
            'core'      =>$_GPC['core'],
            'status'    =>0,
            'cate'      =>intval($_GPC['adcate']),
            'thumb'     =>serialize($_GPC['post1']),
            'mobile'    =>$_GPC['mobile'],
            'desc'      =>$_GPC['desc'],
            'video'     =>trim($_GPC['video']),
            'link'      =>trim($_GPC['link']),
            'usermax'   =>trim($_GPC['usermax']),
            'daymax'    =>trim($_GPC['daymax']),
            'ctime'     =>time(),       
            'putInType' =>$_GPC['putInType'],       //投放设置
            'goodsid'   =>trim($_GPC['goodsid'],','),
            'money'     =>floatval(floatval($_GPC['bonus']) * 0.3 ),
            'bonus'     =>floatval($_GPC['bonus']),
            'residual'  =>floatval($_GPC['bonus']),
            'gender'    =>intval($_GPC['gender']),
            'minimum'   =>0,        
            'maximum'   =>0,
            'minage'    =>0,
            'maxage'    =>0,
            'stime'     =>strtotime($_GPC['stime']),
            'etime'     =>strtotime($_GPC['etime']),
            'outside'   =>trim($_GPC['outside'])
        );
        
        if ($_GPC['status'] == 1) {
            $data['status']='0';
            $data['now']='0';
        }else if ($_GPC['status'] == 2){
            $data['status']='0';
            $data['now']='1';   //立即投放 如果开始时间到了 且now =1 status =1 将开始播放
        }else if($_GPC['status'] == 3){
            $data['status']=3;
        }               

        if ($id) {
            $oldad=m('tools')->getad($id);
            $data['type']=$oldad['type'];
        }else{
            $data['type']=$member['default_ad_model'];
        }

        if (!empty($_GPC['setage'])) {
            $data['minage'] =intval($_GPC['minage']);
            $data['maxage'] =intval($_GPC['maxage']);
        }

        if (!empty($_GPC['setincome'])) {
            $data['minimum'] = floatval($_GPC['minimum']);
            $data['maximum'] = floatval($_GPC['maximum']);
        }

        if (!empty($_GPC['national'])) {
            $data['national']=$_GPC['national'];
        }else{
            if ($_GPC['province'] || $_GPC['city'] || $_GPC['area']) {
                $data['province']=$_GPC['province'];
                $data['city']=$_GPC['city'];
                $data['area']=$_GPC['area'];
                $data['national']='0';
            }else{
                $data['national']='1';
            }
        }

        pdo_insert('sz_yi_virtual_ad',$data);

        $vid=pdo_insertid();
        $ad=pdo_fetch('select * from '.tablename('sz_yi_virtual_ad').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$vid));
        $url=$this->createPluginMobileUrl('suppliermenu/ad',array('op'=>'showPreview','merchtype'=>$_GPC['merchtype'],'id'=>$ad['id'],'exists'=>'no'));
    }

    show_json(1,$url);
}

if ($op =='showPreview') {
    $id=$_GPC['id'];        
    if ($_W['isajax']) {        
            if (!empty($_GPC['exists'])) {
                $ad=pdo_fetch('select * from '.tablename('sz_yi_virtual_ad').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
            }else{
                $ad=m('tools')->getAd($id);      
            }
            $ad['desc']=strip_tags(html_entity_decode($ad['desc']));
            $ad['thumb']=unserialize($ad['thumb']);
            foreach ($ad['thumb'] as $key => $value) {
                $ad['thumb'][$key]=tomedia($value);
            }
            $pm=p('bonus')->getMerch($ad['recuid']);                                   
            // $member=m('member')->getMember($openid); //mobile
            $sMember=m('member')->getMember($pm['openid']);
            $merch=array();
            if (!empty($pm['merchid'])) {
                $temp=pdo_fetch('select * from '.tablename('sz_yi_merch_user').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$ad['recuid']));
                $merch['logo']=$temp['logo']?:$temp['img'];
                $merch['logo']=tomedia($merch['logo'])?:$sMember['avatar'];
                $merch['merchname']=$temp['merchname'];
                // http://jhzh66.com/app/index.php?i=8&c=entry&p=merch&op=detail&id=70&merch=3&do=member&m=sz_yi
                // $merch['url']=$this->crteateMobileUrll();
            }else if (!empty($pm['dealmerchid'])){
                $temp=pdo_fetch('select * from '.tablename('sz_yi_dealmerch_user').' where uniacid = :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$ad['recuid']));
                $merch['logo']=$temp['logo']?:$temp['img'];
                $merch['logo']=tomedia($merch['logo'])?:$sMember['avatar'];
                $merch['merchname']=$temp['merchname'];
                $merch['url']=$this->createPluginMobileUrl('supplier/store', array('op' => 'skip', 'merch' =>'5' ,'storeid' =>$temp['uid']));
            }else{
                $temp=pdo_fetch('select * from '.tablename('sz_yi_store_data').' where uniacid = :uniacid and storeid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$ad['recuid']));
                $merch['logo']=$temp['logo']?:$temp['signboard'];
                $merch['logo']=tomedia($merch['logo'])?:$sMember['avatar'];
                $merch['merchname']=$temp['storename'];     
            }
            $merch['favorite']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_member_favorite').' where uniacid = :uniacid and merchid = :uid and deleted = 0',array(':uniacid'=>$_W['uniacid'],':uid'=>$ad['uid']));

            $ad['totalgoods']=pdo_fetchcolumn('select count(*) from '.tablename('sz_yi_goods').' where uniacid = :uniacid and supplier_uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$ad['uid']));
            $ad['merch']=$merch;
            $ad['goodsid']=$ad['goodsid']?:0;
            $ad['goods']=pdo_fetchall('select * from '.tablename('sz_yi_goods').' where uniacid = :uniacid and id in ('.$ad['goodsid'].')',array(':uniacid'=>$_W['uniacid']));
            foreach ($ad['goods'] as $key => $value) {
                $ad['goods'][$key]['thumb']=tomedia($value['thumb']);
                if ($value['type'] == 8) {               
                    $option=pdo_fetch('select * from '.tablename('sz_yi_goods_option').' where uniacid = :uniacid and goodsid  = :id order by marketprice',array(':uniacid'=>$_W['uniacid'],':id'=>$value['id']));
                    $ad['goods'][$key]['url']=$this->createMobileUrl('shop/detail',array('id'=>$value['id']));
                    $ad['goods'][$key]['marketprice']=$option['marketprice'];
                    $ad['goods'][$key]['shopprice']=$option['productprice'];
                }
                
            }   
            show_json(1,array('ad'=>$ad));
        }

        if (!empty($_GPC['exists'])) {
            $core=pdo_fetchcolumn('select core from '.tablename('sz_yi_virtual_ad').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
        }else{       
            $core=pdo_fetchcolumn('select core from '.tablename('sz_yi_ad_model').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));   
        }       
        $length=mb_strlen($core,'utf8');
        $str=m('tools')->getChar(9-$length);
        $full=$str.$core;
        $full=m('tools')->expStr($full);
        $corearr=m('tools')->expStr($core);
        shuffle($full);
        if (!empty($_GPC['exists'])) {
            $tempad=pdo_fetch('select * from '.tablename('sz_yi_virtual_ad').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
        }else{
            $tempad=m('tools')->getAd($id);      
        }
        $tmp=pdo_fetch('select * from '.tablename('sz_yi_member_favorite').' where uniacid = :uniacid and openid = :openid and merchid = :uid',array(':uniacid'=>$_W['uniacid'],':openid'=>$openid,':uid'=>$tempad['uid']));
        $isfavorite=$tmp['deleted']==1?false:true;
        
        $sMerch=p('bonus')->getMerch($tempad['uid']);
        $sMember=m('member')->getMember($sMerch['openid']);
        $consult=$this->createPluginMobileUrl('commission/team',array('op'=>'zixun','sender'=>'superior','id'=>$sMember['id']));
        $sure=false;
    
include $this->template('preview');
    exit;
}
    

include $this->template('ad');
            