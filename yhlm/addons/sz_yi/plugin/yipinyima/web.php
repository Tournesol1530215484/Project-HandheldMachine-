<?php
/**
 * 
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.cocogd.com.cn)
 * @license    http://www.cocogd.com.cn
 * @link       http://www.cocogd.com.cn
 * @since      File available since Release v1.1
 */

if (!defined('IN_IA')) {
    exit('Access Denied');
}
require_once 'model.php';
class YipinyimaWeb extends Plugin
{
    public function __construct()
    {
        parent::__construct('yipinyima');
    }
    public function index()
    {
        if (cv('yipinyima.temp')) {
            header('location: ' . $this->createPluginWebUrl('yipinyima/temp'));

            exit;
        } else if (cv('yipinyima.category')) {
            header('location: ' . $this->createPluginWebUrl('yipinyima/category'));
            exit;
        }
    }
    public function temp()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function dayin()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
	    public function data()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function api(){
        $this->_exec_plugin(__FUNCTION__);
    }
}