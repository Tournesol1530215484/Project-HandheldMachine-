<?php
/**
 * 排行榜
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */

global $_W, $_GPC;
$apido = $_GPC['apido'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$openid    = m('user')->getOpenid();
$uniacid   = $_W['uniacid'];
$trade     = m('common')->getSysset('trade');
$num = 1;
$paihang = pdo_fetch("SELECT * FROM " . tablename('paihang') . ' where uniacid=:uniacid',array(':uniacid' => $_W['uniacid']));
$bool = pdo_fetch("SELECT y_name,j_name,x_name FROM " . tablename('paihang') . ' where uniacid=:uniacid',array(':uniacid' => $_W['uniacid']));
    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 2;
    $condition = ' WHERE `uniacid` = :uniacid AND `credit1` != :credit1';
    $params    = array(
        ':uniacid' => $_W['uniacid'],
        ':credit1 ' => 0
    );

		$sql = 'SELECT credit1, realname FROM ' . tablename('sz_yi_member') . $condition . ' ORDER BY `credit1` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
		$sqls = 'SELECT COUNT(*) FROM ' . tablename('sz_yi_member') . $condition;
		$total = pdo_fetchcolumn($sqls, $params);
    
    $list  = pdo_fetchall($sql, $params);
    $pager = pagination($total, $pindex, $psize);
    $items = pdo_fetchall("SELECT credit1, realname FROM " . tablename('sz_yi_member') . " WHERE uniacid = :uniacid AND `credit1` != :credit1 ORDER BY `credit1` DESC LIMIT 0,10", array(
        ':uniacid' => $_W['uniacid'],
        ':credit1' => 0
    ));
    $member = m('member')->getMember();
    foreach ($member as $key => $value) {
        $a = $member[$key];
        if($member[$key-1]){
                if( $member[$key]['credit1']> $member[$key-1]['credit1']){  
                    $member[$key] = $member[$key-1];
                    $member[$key-1] = $a;
                }
                if($key>10){
                    unset($member[$key]);
                }
            }
    }

    $pindex    = max(1, intval($_GPC['page']));
    $psize     = 20;
    $condition = " and dm.uniacid=:uniacid";
    $params    = array(
        ':uniacid' => $_W['uniacid']
    );
    $sql = "select dm.*,l.levelname,g.groupname,a.nickname as agentnickname,a.avatar as agentavatar from " . tablename('sz_yi_member') . " dm " . " left join " . tablename('sz_yi_member_group') . " g on dm.groupid=g.id" . " left join " . tablename('sz_yi_member') . " a on a.id=dm.agentid" . " left join " . tablename('sz_yi_member_level') . " l on dm.level =l.id" . " left join " . tablename('mc_mapping_fans') . "f on f.openid=dm.openid  and f.uniacid={$_W['uniacid']}" . " where 1 {$condition}  ORDER BY dm.id DESC";
    $list = pdo_fetchall($sql, $params);
    foreach ($list as &$row) {
        $row['levelname']  = empty($row['levelname']) ? (empty($shop['levelname']) ? '普通会员' : $shop['levelname']) : $row['levelname'];
        $row['ordercount'] = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_order') . ' where uniacid=:uniacid and openid=:openid and status=3', array(
            ':uniacid' => $_W['uniacid'],
            ':openid' => $row['openid']
        ));
        $row['ordermoney'] = pdo_fetchcolumn('select sum(goodsprice) from ' . tablename('sz_yi_order') . ' where uniacid=:uniacid and openid=:openid and status=3', array(
            ':uniacid' => $_W['uniacid'],
            ':openid' => $row['openid']
        ));
        $row['credit1']    = m('member')->getCredit($row['openid'], 'credit1');
        $row['credit2']    = m('member')->getCredit($row['openid'], 'credit2');
        $row['followed']   = m('user')->followed($row['openid']);
    }
    unset($row);
    
    $sort = array(  
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序  
            'field'     => 'credit1',       //排序字段  
    );  
    $arrSort = array();  
    foreach($list AS $uniqid => $row){  
        foreach($row AS $key=>$value){  
            $arrSort[$key][$uniqid] = $value;  
        }  
    }  
    if($sort['direction']){  
        array_multisort($arrSort[$sort['field']], constant($sort['direction']), $list);  
    } 
    foreach ($list as $key => $value) {
        if($value['credit1'] == 0){
            unset($list[$key]);
        }
        if($key>10){
            unset($list[$key]);
        }
    }
if($apido == 'selectlist'){

        
}else if($apido == 'selectlist1'){
    $item = pdo_fetchall("select openid,SUM(oldprice) as xiaofei from " . tablename('sz_yi_order') . "   where uniacid ={$_W['uniacid']} and status = 3 and oldprice != 0  group by openid order by SUM(oldprice) desc limit 0,10");
        foreach ($item as $key => $value) {
                $arr = pdo_fetch("select realname,nickname,avatar from " . tablename('sz_yi_member') . " where openid = :openid ",array(
                    ':openid' => $value['openid']
                ));
                $item[$key]['realname'] = $arr['realname'];
                $item[$key]['nickname'] = $arr['nickname'];
                $item[$key]['avatar'] = $arr['avatar'];
        }

}else if($apido == 'selectlist2'){

    $item = pdo_fetchall("select nickname,credit7,avatar from " . tablename('mc_members') . " where uniacid=$uniacid and credit7!=0 order by credit7 desc limit 0,10");
    foreach ($item as $key => $value) {
        $item[$key]['num'] = $key+1;
    }
    /*foreach ($item as &$row) {
        $info = $this->model->getInfo($row['openid'], array(
            'total',
            'pay'
        ));

        $row['commission_total'] = $info['commission_total'];
          
    }*/
    
}
/*    if($apido == 'selectlist'){
    	$name = '积分榜';
    	$bool = pdo_fetch("SELECT y_name,j_name,x_name FROM " . tablename('paihang') . ' where uniacid=:uniacid',array(':uniacid' => $_W['uniacid']));
    	if($name == $bool['j_name']){
    		$item = pdo_fetchall("SELECT credit1, realname,nickname,avatar FROM " . tablename('sz_yi_member') . " WHERE uniacid = :uniacid AND `credit1` != :credit1 ORDER BY `credit1` DESC LIMIT 0,10", array(
    		    ':uniacid' => $_W['uniacid'],
    		    ':credit1' => 0
    		));
        $result=echo_json(1,0,$item);
    	}
    }
    if($_W['isajax']){
        $name = '积分榜';
        $bool = pdo_fetch("SELECT y_name,j_name,x_name FROM " . tablename('paihang') . ' where uniacid=:uniacid',array(':uniacid' => $_W['uniacid']));
        if($name == $bool['j_name']){
            $item = pdo_fetchall("SELECT credit1, realname,nickname,avatar FROM " . tablename('sz_yi_member') . " WHERE uniacid = :uniacid AND `credit1` != :credit1 ORDER BY `credit1` DESC LIMIT 0,10", array(
                ':uniacid' => $_W['uniacid'],
                ':credit1' => 0
            ));
        $result=echo_json(1,0,$item);
        }
    }*/
        /*else if($name == $bool['x_name']){

            $item = pdo_fetchall("select openid,SUM(oldprice) from " . tablename('sz_yi_order') . "   where uniacid ={$_W['uniacid']} and oldprice != 0 group by openid order by SUM(oldprice) desc limit 0,10");
            foreach ($item as $key => $value) {
                    $arr = pdo_fetch("select realname,avatar from " . tablename('sz_yi_member') . " where openid = :openid ",array(
                        ':openid' => $value['openid']
                    ));
                    $item[$key]['realname'] = $arr['realname'];
                    $item[$key]['avatar'] = $arr['avatar'];
                }
        $result=echo_json(2,0,$item);
    	}else if($name == $bool['y_name']){
    		$item = pdo_fetchall("SELECT realname,commission,avatar FROM " . tablename('sz_yi_commission_apply') . " d " .
		        " left join " . tablename('sz_yi_member') . ' m on m.id = d.mid '.
		        " WHERE d.uniacid = ".$_W['uniacid'] . " AND d.commission != 0 ORDER BY `commission` DESC LIMIT 0,10");
        $result=echo_json(3,0,$item);
    	}
   		
    }*/
    



include $this->template('member/paihang');
