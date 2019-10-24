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
class ComeonWeb extends Plugin
{
    public function __construct()
    {
        parent::__construct('comeon');
    }
    public function index()
    {
        if (cv('comeon.temp')) {
            header('location: ' . $this->createPluginWebUrl('comeon/temp'));

            exit;
        } else if (cv('comeon.category')) {
            header('location: ' . $this->createPluginWebUrl('comeon/category'));
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
    public function export()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public function pinpai()
    {
    $this->_exec_plugin(__FUNCTION__);
    }
    public function chexing()
    {
        $this->_exec_plugin(__FUNCTION__);
    }
    public  function  cangshang(){
        $this->_exec_plugin(__FUNCTION__);
    }
    public  function  pailiang(){
        $this->_exec_plugin(__FUNCTION__);
    }
    public  function scri(){
        $this->_exec_plugin(__FUNCTION__);
    }
    public function jichu(){
        $this->_exec_plugin(__FUNCTION__);
    }
    public function api(){
        $this->_exec_plugin(__FUNCTION__);
    }
}