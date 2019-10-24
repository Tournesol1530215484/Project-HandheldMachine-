<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}
global $_W, $_GPC;
$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition = ' and g.uniacid=:uniacid and g.type = 8 and g.ischeck = 0 and l.status = 0 ';
$params = array(':uniacid' => $_W['uniacid']); 
// 

if ($op == 'check') { 
    $lid=intval($_GPC['logid']); 
    $ischeck=intval($_GPC['ischeck']);
    if ($lid>0){  
        $log=[
            'status'=>$ischeck,
            'note'  => trim($_GPC['note']),
            'audit_time'=>time()
        ];

        $data=array(
            'status'=>$ischeck
        );
        
        $id=pdo_fetchcolumn('select ad_id from '.tablename('sz_yi_ad_for_log').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$lid));
        $log['ad_id']=$id;
        if ($ischeck == 1) { 
            $tempad=m('tools')->getAd($id);
            $smember=p('bonus')->getMerch($tempad['uid']);
            switch ($tempad['putInType']) {
                case '1':
                    $str='credit2';
                    break;

                case '2':
                    $str='credit3';
                    break;

                default:
                    break;
            }
            m('member')->setCredit($smember['openid'],$str,-floatval($tempad['bonus'] * 0.3 ));  //扣除红包所需
            $data['version']=(intval($tempad['version']) + 1);
            $sure=pdo_update('sz_yi_ad_model',$data,array('id'=>$id)); 
            m('log')->putAdLog($log);  //记录日志
            $sure?message('审核成功!','','success'):message('审核失败!','','error');   
        }else if ($ischeck == 2){        
            $sure=pdo_update('sz_yi_ad_model',$data,array('id'=>$id)); 
             m('log')->putAdLog($log);  //记录日志
            $sure?message('驳回成功!','','success'):message('驳回失败!','','error');
        }
              
    }
    message('操作失败!','','error'); 
}else if($op == 'display' ){
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $params = array(':uniacid' => $_W['uniacid']);
    $condition=' and af.uniacid = :uniacid and af.status = 0 ';
    
    // if ($op == 'bonus') {
    //     $condition.=' and putInType = 1 '; //现金红包
    // }else{
    //     $condition.=' and putInType = 2 '; //易货码红包
    // }

    if ($_GPC['adsn']) {
        $condition .= ' and ad.adsn like :adsn';
        $params[':adsn'] = "%{$_GPC['adsn']}%";
    }

    if ($_GPC['title']) {
        $condition .= ' and ad.title like :title';
        $params[':title'] = "%{$_GPC['title']}%";
    }

    $sql='select af.id lid,ad.* from '.tablename('sz_yi_ad_for_log').' af left join '.tablename('sz_yi_ad_model').' ad on ad.id= af.ad_id where 1 '.$condition;
    
    $totals = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_ad_for_log'). " af left join ".tablename('sz_yi_ad_model')." ad on ad.id= af.ad_id where 1 {$condition} ", $params);
     
    $sql .= 'LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
    $list = pdo_fetchall($sql, $params);

    foreach ($list as $key => $value) {
        $tmerch=p('bonus')->getMerch($value['uid']);        
        $list[$key]['username']=$tmerch['username'];
    }

    $pager = pagination($totals, $pindex, $psize);
}


load()->func('tpl'); 
include $this->template('ad_for');