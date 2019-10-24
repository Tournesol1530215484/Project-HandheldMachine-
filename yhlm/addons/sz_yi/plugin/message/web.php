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
class MessageWeb extends Plugin
{

    public function __construct()
    {
        parent::__construct('message');
    }
    public function index()
    {
        if (cv('message.temp')) {
            header('location: ' . $this->createPluginWebUrl('message/temp'));

            exit;
        } else if (cv('message.category')) {
            header('location: ' . $this->createPluginWebUrl('message/category'));
            exit;
        }
    }
    public function temp()
    {	
        $this->_exec_plugin(__FUNCTION__);
    }
    public function data()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function category()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function import()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function xitong()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function set()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function jichu()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
}