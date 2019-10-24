<?php
//decode by QQ:270656184 http://www.yunlu99.com/
if (!defined('IN_IA')) {
	exit('Access Denied');
}
global $_W, $_GPC;
$op = empty($_GPC['op']) ? 'display' : $_GPC['op'];

if($op == 'display' ){
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $params = array(':uniacid' => $_W['uniacid']);
    $condition=' and l.uniacid = :uniacid and l.status = 0 ';
                     
    if ($_GPC['title']) {
        $condition .= ' and a.title like :title';
        $params[':title'] = "%{$_GPC['title']}%";
    }

    $sql='select l.id lid,a.* from '.tablename("sz_yi_activity_log").' l left join '.tablename("sz_yi_activity").' a on a.id = l.actid where 1 ';
    $sql.=$condition;            
    $totals = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_activity_log'). " l left join ".tablename('sz_yi_activity')." a on a.id = l.actid where 1 {$condition} ",$params);          
    $sql .= 'LIMIT ' . ($pindex - 1) * $psize . ' , ' . $psize;
    $list = pdo_fetchall($sql, $params);                             

    foreach ($list as $key => $value) {
        $muser=m('activity')->getMuser($value['openid']);        
        $tmerch=p('bonus')->getMerch($muser['uid']);        
        $list[$key]['username']=$tmerch['username'];
    }

    $pager = pagination($totals, $pindex, $psize);

}else if ($op == 'check') { 
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
        
        $id=pdo_fetchcolumn('select actid from '.tablename('sz_yi_activity_log').' where uniacid = :uniacid and id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$lid));
        $log['actid']=$id;
        if ($ischeck == 1) { 
          
            $sure=pdo_update('sz_yi_activity',$data,array('id'=>$id)); 
            m('log')->putActLog($log);  //记录日志
            $sure?message('审核成功!','','success'):message('审核失败!','','error');   
        }else if ($ischeck == 2){        
            $sure=pdo_update('sz_yi_activity',$data,array('id'=>$id)); 
             m('log')->putActLog($log);  //记录日志
            $sure?message('驳回成功!','','success'):message('驳回失败!','','error');
        }
                                     
    }
    message('操作失败!','','error'); 
}


load()->func('tpl'); 
include $this->template('forum_for');