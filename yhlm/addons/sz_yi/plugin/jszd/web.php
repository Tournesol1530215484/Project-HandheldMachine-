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
class JszdWeb extends Plugin
{
	
    public function __construct()
    {
        parent::__construct('jszd');
    }
    public function index()
    {
        if (cv('jszd.temp')) {
			
            header('location: ' . $this->createPluginWebUrl('jszd/temp'));

            exit;
        } else if (cv( 'jszd.dividend')) {
            header('location: ' . $this->createPluginWebUrl('jszd/dividend'));
            exit;
        }else if (cv('jszd.category')) {
            header('location: ' . $this->createPluginWebUrl('jszd/category'));
            exit;
        }
    }
    public function temp()
    {
	
		
        $this->_exec_plugin(__FUNCTION__);
    }
	public function dividend()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
	public function region()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
	
    public function api(){
        $this->_exec_plugin(__FUNCTION__);
    }
}