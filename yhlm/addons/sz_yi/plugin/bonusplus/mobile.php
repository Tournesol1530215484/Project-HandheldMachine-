<?php

/*=============================================================================
#         Desc: 专业承接微信分销商城二次开发及相关微信功能模块的开发与定制
#       Author: Man.Dan - http://www.jzwshop.com
#        Email: 82089092@qq.com
#     HomePage: http://www.jzwshop.com
#      Version: 0.0.1
#   LastChange: 2016-02-05 02:08:51
#      History:
=============================================================================*/
if (!defined('IN_IA')) {
    exit('Access Denied');
}
function sortByCreateTime($a, $b)
{
    if ($a['createtime'] == $b['createtime']) {
        return 0;
    } else {
        return ($a['createtime'] < $b['createtime']) ? 1 : -1;
    }
}
class BonusplusMobile extends Plugin
{
    protected $set = null;
    public function __construct()
    {
        parent::__construct('bonusplus');
        $this->set = $this->getSet();
        global $_GPC;
        /*if ($_GPC['method'] != 'register' && $_GPC['method'] != 'myshop') {
            $openid = m('user')->getOpenid();
            $member = m('member')->getMember($openid);
            if ($member['isagent'] != 1 || $member['status'] != 1) {
                header('location:' . $this->createPluginMobileUrl('commission/register'));
                exit;
            }
        }*/
    }
    public function index()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function team()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function customer()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function order()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function withdraw()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function apply()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function shares()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function register()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function myshop()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function log()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
}
