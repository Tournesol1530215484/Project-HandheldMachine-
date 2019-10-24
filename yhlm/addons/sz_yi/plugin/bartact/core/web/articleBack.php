<?php
//decode by QQ:270656184 http://www.yunlu99.com/
global $_W, $_GPC;
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$plugin_diyform = p('diyform');
$totals = array();

if ($op == 'display') {
	$pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $params = array(':uniacid' => $_W['uniacid']);
    $condition=' and uniacid = :uniacid ';
    
    if ($_GPC['adsn']) {
        $condition .= ' and adsn like :adsn';
        $params[':adsn'] = "%{$_GPC['adsn']}%";
    }       

    if ($_GPC['title']) {
        $condition .= ' and title like :title';
        $params[':title'] = "%{$_GPC['title']}%";
    }
         
    $sql='select * from '.tablename('sz_yi_activity_article').' where 1 '.$condition;
    $sql.=' order by id desc ';
    // $sql.=' limit '.($pindex -1 )* $psize.','.$psize;
    $totals = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_activity_article'). " where 1 {$condition} ", $params);
    
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;       
    $list = pdo_fetchall($sql, $params);
    foreach ($list as $key => $value) {               
        if ($value['stime'] > time()) {
           $time=$value['etime'] - $value['stime'];
        }else{
            $time= $value['etime'] - time();
        }
       $list[$key]['calctime']= $time < 1 ? '已过期' : m('tools')->calcTime($time);
    }
    $pager = pagination($totals, $pindex, $psize);
}else if ($op == 'post'){
    $id=$_GPC['id'];

    if ($_W['isajax']) {
        $data=$_GPC['data'];
            
        show_json(0,array('msg'=>'呵呵呵呵呵'));
        if (empty($id)) {
            $data['uid']=$_W['uid'];
            pdo_insert('sz_yi_activity_article',$data);
            $id=pdo_insertid();

            if ($id) {  
                show_json(1,array('msg'=>'添加成功!','url'=>$this->createPLuginWebUrl('activity/article')));
            }else{
                show_json(0,array('msg'=>'添加失败!','url'=>$this->createPLuginWebUrl('activity/article')));
            }

        }else{
            $re=pdo_update('sz_yi_activity_article',$data,array('id'=>$id,'uid'=>$_W['uid']));

            if ($re) {
                show_json(1,array('msg'=>'更新成功!','url'=>$this->createPLuginWebUrl('activity/article')));
            }else{
                show_json(0,array('msg'=>'更新失败!','url'=>$this->createPLuginWebUrl('activity/article')));
            }

        }

    }
}else if($op == 'draft'){
    $pindex = max(1, intval($_GPC['page']));
    $psize = 20;
    $params = array(':uniacid' => $_W['uniacid'],':uid'=>$_W['uid']);
    $condition=' and uniacid = :uniacid and uid = :uid and status = 2 ';
    
    if ($_GPC['adsn']) {
        $condition .= ' and adsn like :adsn';
        $params[':adsn'] = "%{$_GPC['adsn']}%";
    }       

    if ($_GPC['title']) {
        $condition .= ' and title like :title';
        $params[':title'] = "%{$_GPC['title']}%";
    }
         
    $sql='select * from '.tablename('sz_yi_activity_article').' where 1 '.$condition;
    $sql.=' order by id desc ';
    $totals = pdo_fetchcolumn("select count(*) from " . tablename('sz_yi_activity_article'). " where 1 {$condition} ", $params);
    
    $sql .= ' LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;       
    $list = pdo_fetchall($sql, $params);
    foreach ($list as $key => $value) {               
        if ($value['stime'] > time()) {
           $time=$value['etime'] - $value['stime'];
        }else{
            $time= $value['etime'] - time();
        }
       $list[$key]['calctime']= $time < 1 ? '已过期' : m('tools')->calcTime($time);
    }
    $pager = pagination($totals, $pindex, $psize);
}

include $this -> template('article');