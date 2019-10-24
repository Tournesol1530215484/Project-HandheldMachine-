<?php
//多级分销商城 QQ:1084070868
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class TmessageWeb extends Plugin
{
    public function __construct()
    {
        parent::__construct('tmessage');
    }
    public function index()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
}