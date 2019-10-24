<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class ReturnMobile extends Plugin
{
    protected $set = null;
    public function __construct()
    {
        parent::__construct('return');
        $this->set = $this->getSet();
        global $_GPC;
    }
    

    public function api(){


            global $_W;
            $_var_0 = $this->getSet();

            //返利队列
            $data_money = pdo_fetchall("select * from " . tablename('sz_yi_return') . " where uniacid = '". $_W['uniacid'] ."' and status = 0 and returnrule = '".$_var_0['returnrule']."'");

            foreach ($data_money as $key => $value) {
                $r_each = $value['money'] * $_var_0['percentage'] / 100;//可返利金额
                
                $member = pdo_fetch("select * from " . tablename('sz_yi_member') . " where uniacid = '". $_W['uniacid'] ."' and id = '".$value['mid']."'");

                if(($value['money']-$value['return_money']) < $r_each){
                    pdo_update('sz_yi_return', array('return_money'=>$value['money'],'status'=>'1'), array('id' => $value['id'], 'uniacid' => $_W['uniacid']));
                    m('member')->setCredit($member['openid'],'credit2',$value['money']-$value['return_money']);

                    $messages = array(
                        'keyword1' => array('value' => '返现通知', 
                            'color' => '#73a68d'),
                            'keyword2' => array('value' => '本次返现金额'.$value['money']-$value['return_money']."元！",
                                            'color' => '#73a68d'
                             ),
                            'keyword3' => array('value' => '此返单已经全部返现完成！',
                                            'color' => '#73a68d'
                             )
                        );
                    m('message')->sendCustomNotice($member['openid'], $messages);

                }else
                {
                    pdo_update('sz_yi_return', array('return_money'=>$value['return_money']+$r_each), array('id' => $value['id'], 'uniacid' => $_W['uniacid']));
                    m('member')->setCredit($member['openid'],'credit2',$r_each);

                    $surplus = $value['money']-$value['return_money']-$r_each;
                    $messages = array(
                        'keyword1' => array(
                            'value' => '返现通知',
                            'color' => '#73a68d'),
                        'keyword2' =>array(
                            'value' => '本次返现金额'.$r_each,
                            'color' => '#73a68d'),
                        'keyword3' => array(
                            'value' => "此返单剩余返现金额".$surplus,
                            'color' => '#73a68d')
                        );
                    m('message')->sendCustomNotice($member['openid'], $messages);
                }
            }




    }



    public function task()
    {    
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function return_log()
    {    
        $this->_exec_plugin(__FUNCTION__, false);
    } 

}