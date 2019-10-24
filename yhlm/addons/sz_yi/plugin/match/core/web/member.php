<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$plugin_diyform = p('diyform');
$totals = array();

if ($op == 'display') {
	$pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $muser=m('match')->getMuser($_W['uid']);
    $params = array(':uniacid' => $_W['uniacid'],':openid'=>$muser['openid']);
    $condition=' and a.uniacid = :uniacid and a.openid = :openid ';
            
    if ($_GPC['realname']) {         
        $condition .= ' and s.data like :realname';
        $params[':realname'] = "%{$_GPC['realname']}%";
    }       
         
    if ($_GPC['mobile']) {
        $condition .= ' and s.data like :mobile';
        $params[':mobile'] = "%{$_GPC['mobile']}%";
    }

    if ($_GPC['orgName']) {     
        $condition .= ' and s.data like :orgName';
        $params[':orgName'] = "%{$_GPC['orgName']}%";
    }
        
    $sql='select s.*,count(s.openid) as num from '.tablename('sz_yi_match_signup').' s left join '.tablename('sz_yi_match').' a on a.id = s.actid  where 1 '.$condition.' group by s.openid ';     
    $sql.=' order by s.id desc ';                           
    $totals = pdo_fetchcolumn("select s.* from ".tablename('sz_yi_match_signup')." s left join ".tablename('sz_yi_match')." a on a.id = s.actid where 1 {$condition} ", $params);                      
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;              
    $list = pdo_fetchall($sql, $params);           
    	foreach ($list as $key => $value) {                            
            $list[$key]=array_merge($value,unserialize($value['data']));
	    }      
    $pager = pagination($totals, $pindex, $psize);
}else if($op == 'sub'){
    $id=intval($_GPC['id']);
    

    $sglog=pdo_fetch('select * from '.tablename('sz_yi_match_signup').' where uniacid = :uniacid and id = :id ',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
    $act=m('match')->getact($sglog['actid']);
    $muser=m('match')->getMuser($_W['uid']);  
    // show_json(0,array($sglog,$act,$muser));      
    if (!$sglog || !$act || $act['openid'] != $muser['openid']) {      
        show_json(0,'记录不存在');
    }
             
    $data=array(
        'level'=>$_GPC['level'],
        'etime'=>strtotime($_GPC['etime'])
    );                                           

    $re=pdo_update('sz_yi_match_signup',$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));
    if ($re) {
        show_json(1,'修改成功');
    }
    show_json(0,'修改失败');        

}           
include $this -> template('member');