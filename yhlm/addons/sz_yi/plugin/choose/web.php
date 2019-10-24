<?php
//多级分销商城 QQ:1084070868
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class ChooseWeb extends Plugin
{
    public function __construct()
    {
        parent::__construct('choose');
    }
    public function index()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function basic()
    {
        $this->_exec_plugin(__FUNCTION__);

    }
    public function upgrade()
    {
        $this->_exec_plugin(__FUNCTION__);

    }
    // public function api()
    // {
    //     $this->_exec_plugin(__FUNCTION__);
    // }
    // public function menu()
    // {
    //     $this->_exec_plugin(__FUNCTION__);
    // }
}