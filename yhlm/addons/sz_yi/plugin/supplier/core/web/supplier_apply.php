<?php
 global $_W, $_GPC;
  ca('supplier.supplier_apply');
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];

if ($operation == 'display'){
    $where = '';
    if(!empty($_GPC['uid'])){
        $where .= ' and p.uid=' . $_GPC['uid'];
    }
    if(!empty($_GPC['applysn'])){
        $where .= ' and a.applysn=' . $_GPC['applysn'];
    }
    $list = pdo_fetchall('select a.*,p.accountname, p.openid ,mobile as telephone, accountbank, banknumber   from ' . tablename('sz_yi_supplier_apply') . ' a left join ' . tablename('sz_yi_perm_user') . ' p on p.uid=a.uid where a.status=0 and p.uniacid=' . $_W['uniacid'] . $where . ' order by id desc');
    foreach ($list as $key => $value) {
        $tm=m('member')->getMember($value['openid']);
        $list[$key]['nickname']=$tm['nickname'];
        $list[$key]['avatar']=$tm['avatar'];
    }
    $total = count($list);
}else if ($operation == 'detail'){
    $id = intval($_GPC['id']);
    if(!empty($id)){
        if ($_GPC['status'] == 2) {
            $data = array('status' => 2, 'finish_time' => time());

        }else{
            $set = m('common') -> getSysset('shop');
            $apply = pdo_fetch('select * from ' . tablename('sz_yi_supplier_apply') . ' where id = ' . $id);
            $openid = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_perm_user') . ' where uid=:uid and uniacid=:uniacid', array(':uid' => $apply['uid'], ':uniacid' => $_W['uniacid']));

            if($apply['type'] == 2){ // 微信
                $result = m('finance') -> pay($openid, 1, $apply['apply_money'] * 100, $apply['applysn'], $set['name'] . '供应商提现');
                if (is_error($result)){
                    message('微信钱包提现失败: ' . $result['message'], '', 'error');
                }
                m('notice') -> sendMemberLogMessage($apply['id']);
            } elseif ($apply['type'] == 3) { // 余额
                m('member')->setCredit($openid, 'credit2', $apply['apply_money']);
                $logno = m('common')->createNO('member_log', 'logno', 'JC');
                $data = array(
                    'openid'       => $openid,
                    'logno'        => $logno,
                    'uniacid'      => $_W['uniacid'],
                    'type'         => '0',
                    'createtime'   => TIMESTAMP,
                    'status'       => '1',
                    'title'        => '商家供应货款',
                    'money'        => $apply['apply_money'],
                    'rechargetype' => 'order', // 供应商/商家提现
                    );
                pdo_insert('sz_yi_member_log', $data);
            }
            $data = array('status' => 1, 'finish_time' => time());

        }


        $res = pdo_update('sz_yi_supplier_apply', $data, array('id' => $id));
        if (!empty($res)) {
            if ($apply['type'] == 1) {
                $msg = '手动打款成功';
            } elseif ($apply['type'] == 2) {
                $msg = '商家供应货款提现到微信钱包成功';
            } elseif ($apply['type'] == 3) {
                $msg = '商家供应货款提现到余额成功';
            }

            if ($_GPC['status'] ==2) {
                $msg = '商家供应货款提现已拒绝';
                $templog = pdo_fetch('select * from ' . tablename('sz_yi_supplier_apply') . ' where id = ' . $id);
                $templog['ogids']=unserialize($templog['ogids']);
                $tempdata=array(
                    'supplier_apply_status'=>0
                );
                foreach ($templog['ogids'] as $key => $value) {
                    //打回之后可以重新提现
                    pdo_update('sz_yi_order_goods',$tempdata,array('id'=>$value['id']));
                }
            }

            p('supplier') -> sendMessage($openid, array('money' => $apply['apply_money'], 'type' => $msg), TM_SUPPLIER_PAY);
            message($msg, $this -> createPluginWebUrl('supplier/supplier_apply'), 'success');
        }
    }
}
load() -> func('tpl');
include $this -> template('supplier_apply');
