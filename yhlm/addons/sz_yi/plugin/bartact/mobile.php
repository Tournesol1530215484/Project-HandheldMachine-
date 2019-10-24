<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}
class BartactMobile extends Plugin
{
    public function __construct()
    {
        parent::__construct('bartact');
    }
    public function af_dealmerch()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function forum()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function lise()
    {
        $this->_exec_plugin(__FUNCTION__, false);
    }
    public function esd(){
      $this->_exec_plugin(__FUNCTION__, false);
}
}