<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
class BonusWeb extends Plugin{
    protected $set = null;
    public function __construct(){
        parent :: __construct('bonus');
        $this -> set = $this -> getSet();
    }
    public function index(){
        global $_W;
        if (cv('bonus.agent')){
            header('location: ' . $this -> createPluginWebUrl('bonus/agent'));
            exit;
        }else if (cv('bonus.notice')){
            header('location: ' . $this -> createPluginWebUrl('bonus/set'));
            exit;
        }else if (cv('bonus.set')){
            header('location: ' . $this -> createPluginWebUrl('bonus/set'));
            exit;
        }else if (cv('bonus.level')){
            header('location: ' . $this -> createPluginWebUrl('bonus/level'));
            exit;
        }else if (cv('bonus.cover')){
            header('location: ' . $this -> createPluginWebUrl('bonus/cover'));
            exit;
        }else if (cv('bonus.send')){
            header('location: ' . $this -> createPluginWebUrl('bonus/send'));
            exit;
        }else if (cv('bonus.sendall')){
            header('location: ' . $this -> createPluginWebUrl('bonus/sendall'));
            exit;
        }
    }
    public function upgrade(){
        $this -> _exec_plugin(__FUNCTION__);
    }
    public function agent(){
        $this -> _exec_plugin(__FUNCTION__);
    }
    public function level(){
        $this -> _exec_plugin(__FUNCTION__);
    }
    public function send(){
        $this -> _exec_plugin(__FUNCTION__);
    }
    public function sendall(){
        $this -> _exec_plugin(__FUNCTION__);
    }
    public function notice(){
        $this -> _exec_plugin(__FUNCTION__);
    }
    public function cover(){
        $this -> _exec_plugin(__FUNCTION__);
    }
    public function set(){
        $this -> _exec_plugin(__FUNCTION__);
    }
    public function detail(){
        $this -> _exec_plugin(__FUNCTION__);
    }
    public function agency(){
        $this -> _exec_plugin(__FUNCTION__);
    }
}
