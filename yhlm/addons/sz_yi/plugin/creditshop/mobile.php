<?php
//�༶�����̳� QQ:1084070868
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class CreditshopMobile extends Plugin
{
    public function __construct()
    {
        parent::__construct('creditshop');
        $this->set = $this->getSet();
    }
    public function index()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function lists()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function detail()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function log()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function creditlog()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function exchange()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
}