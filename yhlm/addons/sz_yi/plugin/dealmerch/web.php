<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
class dealmerchWeb extends Plugin
{
	protected $set = null;
 
	public function __construct() 
	{ 
        parent::__construct('dealmerch');
		$this->set = $this->getSet();
	}

	public function index()
	{
		global $_W;
		if (cv('dealmerch.dealmerch')) {
			header('location: ' . $this->createPluginWebUrl('dealmerch/dealmerch'));
			exit;
		} else if (cv('dealmerch.dealmerch_apply')) {
			header('location: ' . $this->createPluginWebUrl('dealmerch/dealmerch_apply'));
			exit;
		} else if (cv('dealmerch.dealmerch_finish')) {
			header('location: ' . $this->createPluginWebUrl('dealmerch/dealmerch_finish'));
			exit;
		} 
	}

	public function applyfor(){
	    $this->_exec_plugin(__FUNCTION__);
    }

    public function audit(){
        $this->_exec_plugin(__FUNCTION__);
    }

    public function member(){
        $this->_exec_plugin(__FUNCTION__);
    }

    public function enter(){
        $this->_exec_plugin(__FUNCTION__);
    }
    public function upgrade() 
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dealmerch()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dealmerch_apply()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dealmerch_finish()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dealmerch_for()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dealmerch_list()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dealmerch_local_list()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dealmerch_post_list()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dealmerch_sales_statis(){
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dealmerch_add()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dealmerch_type()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function notice()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function set()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dealmerch_for_resu()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function goods()
    { 
        $this->_exec_plugin(__FUNCTION__);
    }
    public function store(){
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dealmerch_work_number(){
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dealmerch_activation(){
        $this->_exec_plugin(__FUNCTION__);
    }
    public function transfer(){
        $this->_exec_plugin(__FUNCTION__);
    }
    //underline here mine......
    public function dealmerch_exchange()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dealmerch_adminset()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dealmerch_stockchange()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
 
    public function setmenu()
    { 
        $this->_exec_plugin(__FUNCTION__);
    }

    public function dealgoods_for()
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function dealinfo_for()
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function barter_currency()
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function barter_code_log()
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    //充值记录
    public function log()
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    //充值记录
    public function cur_log()
    {
        $this->_exec_plugin(__FUNCTION__);
    }

    public function adv()
    { 
        $this->_exec_plugin(__FUNCTION__);
    }

    public function consult() 
    { 
        $this->_exec_plugin(__FUNCTION__);
    }

    public function showmerchinfo() 
    { 
        $this->_exec_plugin(__FUNCTION__);
    }

    public function ad() 
    { 
        $this->_exec_plugin(__FUNCTION__);
    }

    public function ad_for() 
    { 
        $this->_exec_plugin(__FUNCTION__);
    }

    public function ad_set() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }
    
    public function ad_type() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }

    public function report_type() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }

    //新加模块
    public function report_goods_type() 
    {           
        $this->_exec_plugin(__FUNCTION__);
    }

    public function query() 
    {                    
        $this->_exec_plugin(__FUNCTION__);
    }

    public function city() 
    {                    
        $this->_exec_plugin(__FUNCTION__);
    }

    public function supplier_list() 
    {                    
        $this->_exec_plugin(__FUNCTION__);
    }

             
}
