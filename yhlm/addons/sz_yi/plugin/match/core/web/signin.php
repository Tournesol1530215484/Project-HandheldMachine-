<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$plugin_diyform = p('diyform');
$totals = array();
$muser=m('match')->getMuser($_W['uid']);
if ($op == 'display') {
	$pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $params = array(':uniacid' => $_W['uniacid'],':openid'=>$muser['openid']);
    $condition=' and uniacid = :uniacid and openid = :openid ';
     
    // if ($_GPC['title']) {
    //     $condition .= ' and title like :title';
    //     $params[':title'] = "%{$_GPC['title']}%";
    // }
         
    $sql='select * from '.tablename('sz_yi_match_signin').' where 1 '.$condition;
    $sql.=' order by ctime desc ';
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;       
    
    $list = pdo_fetchall($sql, $params);

    $totals = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_match_signin'). " where 1 {$condition} ", $params);

    foreach ($list as $key => $value) {          
        if ($value['stime'] > time()) {
           $time=$value['etime'] - $value['stime'];
        }else{
            $time= $value['etime'] - time();
        }
       $list[$key]['calctime']= $time < 1 ? '已过期' : m('tools')->calcTime($time);
    }
    $pager = pagination($totals, $pindex, $psize);
}else if ($op == 'comment'){
    
    $muser=m('match')->getMuser($_W['uid']);
    !$muser && message('你还没有注册!请先注册!',referer(),'error');
        $pindex=max(1,intval($_GPC['page']));
        $psize=20;              

        $condition=' and ac.uniacid = :uniacid ';
        $params=array(
            ':uniacid'=>$_W['uniacid'],              
            ':openid'=>$muser['openid']                   
        );        

        $condition.=' and ac.type = :type ';                
        if ($_GPC['type']) {       
            $params[':type']=$_GPC['type'];        
        }else{       
            $params[':type']=1;   
            $_GPC['type']=1;                                    
        }                                 

        $field='';
        if ($_GPC['type'] == 1) {
            $extsql=' left join '.tablename('sz_yi_match').' a on a.id = ac.atid ';
            $condition.=' and a.openid = :openid '; 
            $field.=' ,a.title ';              
        }else if($_GPC['type'] == 2){
            $extsql=' left join '.tablename('sz_yi_match_article').' a on a.id = ac.atid ';
            $condition.=' and a.openid = :openid ';
            $field.=' ,a.title ';                                    
        }else if($_GPC['type'] == 3){                           
            $extsql='';                                    
            $condition.=' and ac.openid = :openid ';                                          
        }                    
                            
        $sql='select ac.*,m.realname,m.nickname'.$field.' from '.tablename('sz_yi_match_comment').' ac left join '.tablename('sz_yi_member').' m on m.openid = ac.openid '.$extsql.' where 1 ';                                     
        $sql.=$condition;                        
        $sql.=' order by ac.id desc ';               
        $sql.=' limit '.($pindex - 1) * $psize.','.$psize;               
        $list=pdo_fetchall($sql,$params);     

         if ($list) {                
            foreach ($list as $key => $value) {
                $list[$key]['ctime']=date('Y-m-d H:i:s',$value['ctime']);
            }       
        }       
        $totals=pdo_fetchcolumn('select count(*) '.$field.' from '.tablename('sz_yi_match_comment').' ac left join '.tablename('sz_yi_member').' m on m.openid = ac.openid '.$extsql.' where 1 '.$condition,$params);                     
        $pager = pagination($totals, $pindex, $psize);

}
include $this -> template('signin');