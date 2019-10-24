<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}

class Core extends WeModuleSite{
    public $footer = array();
    public $header = null;
    public $yzShopSet = array();
    public function __construct(){
        global $_W, $_GPC;
        if (is_weixin()){
            m('member') -> checkMember();                                     
        }else{
            $dephp_0 = array('poster', 'postera');
            if (p('commission') && (!in_array($_GPC['p'], $dephp_0)) && !strpos($_SERVER['SCRIPT_NAME'], 'notify')){
                if (strexists($_SERVER['REQUEST_URI'], '/web/')){
                    return;
                }    
                p('commission') -> checkAgent();
            }    
        }
        $this -> yzShopSet = m('common') -> getSysset('shop');
    }
    public function sendSms($dephp_1, $dephp_2, $dephp_3 = 'reg'){
        $dephp_4 = m('common') -> getSysset();

        if($dephp_4['sms']['type'] == 1){

            $res = send_sms($dephp_4['sms']['account'], $dephp_4['sms']['password'], $dephp_1, $dephp_2);
             
            return  $res;
        }else{
            return send_sms_alidayu($dephp_1, $dephp_2, $dephp_3);
        }
    }
    public function runTasks(){
        global $_W;
        load() -> func('communication');
        $dephp_5 = strtotime(m('cache') -> getString('receive', 'global'));
        $dephp_6 = intval(m('cache') -> getString('receive_time', 'global'));
        if (empty($dephp_6)){
            $dephp_6 = 60;
        }
        $dephp_6 *= 60;
        $dephp_7 = time();
        if ($dephp_5 + $dephp_6 <= $dephp_7){
            m('cache') -> set('receive', date('Y-m-d H:i:s', $dephp_7), 'global');
            ihttp_request($_W['siteroot'] . 'addons/sz_yi/core/mobile/order/receive.php', null, null, 1);
        }
        $dephp_5 = strtotime(m('cache') -> getString('closeorder', 'global'));
        $dephp_6 = intval(m('cache') -> getString('closeorder_time', 'global'));
        if (empty($dephp_6)){
            $dephp_6 = 60;
        }
        $dephp_6 *= 60;
        $dephp_7 = time();
        if ($dephp_5 + $dephp_6 <= $dephp_7){
            m('cache') -> set('closeorder', date('Y-m-d H:i:s', $dephp_7), 'global');
            ihttp_request($_W['siteroot'] . 'addons/sz_yi/core/mobile/order/close.php', null, null, 1);
        }
        if (p('coupon')){
            $dephp_8 = strtotime(m('cache') -> getString('couponbacktime', 'global'));
            $dephp_9 = p('coupon') -> getSet();
            $dephp_10 = intval($dephp_9['backruntime']);
            if (empty($dephp_10)){
                $dephp_10 = 60;
            }
            $dephp_10 *= 60;
            $dephp_11 = time();
            if ($dephp_8 + $dephp_10 <= $dephp_11){
                m('cache') -> set('couponbacktime', date('Y-m-d H:i:s', $dephp_11), 'global');
                ihttp_request($_W['siteroot'] . 'addons/sz_yi/plugin/coupon/core/mobile/back.php', null, null, 1);
            }
        }
        exit('run finished.');
    }
    public function setHeader(){
        global $_W, $_GPC;
        $dephp_12 = m('user') -> getOpenid();
        $dephp_13 = m('user') -> followed($dephp_12);
        $dephp_14 = intval($_GPC['mid']);
        $dephp_15 = m('member') -> getMid();
        $this -> setFooter();
        @session_start();
        if (!$dephp_13 && $dephp_15 != $dephp_14 && isMobile()){
            $dephp_4 = m('common') -> getSysset();
            $this -> header = array('url' => $dephp_4['share']['followurl']);
            $dephp_16 = false;
            if (!empty($dephp_14)){
                if (!empty($_SESSION[SZ_YI_PREFIX . '_shareid']) && $_SESSION[SZ_YI_PREFIX . '_shareid'] == $dephp_14){
                    $dephp_14 = $_SESSION[SZ_YI_PREFIX . '_shareid'];
                }
                $dephp_17 = m('member') -> getMember($dephp_14);
                if (!empty($dephp_17)){
                    $_SESSION[SZ_YI_PREFIX . '_shareid'] = $dephp_14;
                    $dephp_16 = true;
                    $this -> header['icon'] = $dephp_17['avatar'];
                    $this -> header['text'] = '来自好友 <span>' . $dephp_17['nickname'] . '</span> 的推荐';
                }
            }
            if (!$dephp_16){
                $this -> header['icon'] = tomedia($dephp_4['shop']['logo']);
                $this -> header['text'] = '欢迎进入 <span>' . $dephp_4['shop']['name'] . '</span>';
            }
        }
    }
    public function setFooter(){
        global $_W, $_GPC;
        $dephp_18 = strtolower(trim($_GPC['p']));
        $dephp_19 = strtolower(trim($_GPC['method']));
        if (strexists($dephp_18, 'poster') && $dephp_19 == 'build'){
            return;
        }
        if (strexists($dephp_18, 'designer') && ($dephp_19 == 'index' || empty($dephp_19)) && $_GPC['preview'] == 1){
            return;
        }
        $dephp_12 = m('user') -> getOpenid();
        $dephp_20 = p('designer');
        if ($dephp_20 && $_GPC['p'] != 'designer'){
            $dephp_21 = $dephp_20 -> getDefaultMenu();
            if (!empty($dephp_21)){
                $this -> footer['diymenu'] = true;
                $this -> footer['diymenus'] = $dephp_21['menus'];
                $this -> footer['diyparams'] = $dephp_21['params'];
                return;
            }
        }
        $dephp_14 = intval($_GPC['mid']);
        $this -> footer['first'] = array('text' => '首页', 'ico' => 'home', 'url' => $this -> createMobileUrl('shop'));
        $this -> footer['second'] = array('text' => '分类', 'ico' => 'list', 'url' => $this -> createMobileUrl('shop/category'));
        $this -> footer['commission'] = false;
        $dephp_17 = m('member') -> getMember($dephp_12);
        if(!empty($dephp_17['isblack'])){
            if($_GPC['op'] != 'black'){
                header('Location: ' . $this -> createMobileUrl('member/login', array('op' => 'black')));
            }
        }
        if (p('commission')){
            $dephp_4 = p('commission') -> getSet();
			//是否关闭"我的小店"功能 data2016.08.25
			$dephp_4['closemyshop'] = m('commission')->getAuthority('is_shop', $dephp_4['closemyshop'] );
			//end
            if (empty($dephp_4['level'])){
                return;
            }
            $dephp_22 = $dephp_17['isagent'] == 1 && $dephp_17['status'] == 1;
            if ($_GPC['do'] == 'plugin'){
                $this -> footer['first'] = array('text' => empty($dephp_4['closemyshop']) ? $dephp_4['texts']['shop'] : '首页', 'ico' => 'home', 'url' => empty($dephp_4['closemyshop']) ? $this -> createPluginMobileUrl('commission/myshop', array('mid' => $dephp_17['id'])) : $this -> createMobileUrl('shop'));
                if ($_GPC['method'] == ''){
                    $this -> footer['first']['text'] = empty($dephp_4['closemyshop']) ? $dephp_4['texts']['myshop'] : '首页';
                }
                if (empty($dephp_17['agentblack'])){
                    $this -> footer['commission'] = array('text' => $dephp_4['texts']['center'], 'ico' => 'sitemap', 'url' => $this -> createPluginMobileUrl('commission'));
                }
            }else{
                if (empty($dephp_17['agentblack'])){
                    if (!$dephp_22){
                        $this -> footer['commission'] = array('text' => $dephp_4['texts']['become'], 'ico' => 'sitemap', 'url' => $this -> createPluginMobileUrl('commission/register'));
                    }else{
                        $this -> footer['commission'] = array('text' => empty($dephp_4['closemyshop']) ? $dephp_4['texts']['shop'] : $dephp_4['texts']['center'], 'ico' => empty($dephp_4['closemyshop']) ? 'heart' : 'sitemap', 'url' => empty($dephp_4['closemyshop']) ? $this -> createPluginMobileUrl('commission/myshop', array('mid' => $dephp_17['id'])) : $this -> createPluginMobileUrl('commission'));
                    }
                }
            }
        }
		//是否关闭"级差代理分红"功能 data2016.09.09 start 
		$this -> footer['bonus'] = false;
		if (p('bonus')){
            $bonusSet = p('bonus')->getSet();
            if (empty($bonusSet['start'])){
                return;
            }
            $this -> footer['bonus'] = array('text' => $bonusSet['texts']['center'], 'ico' => 'sitemap', 'url' => $this -> createPluginMobileUrl('bonus'));
        }
		//end
		
        if(strstr($_SERVER['REQUEST_URI'], 'app')){
            if(!isMobile()){
                if($this -> yzShopSet['ispc'] == 0){
                }
            }
        }
        if(is_weixin()){
            if(!empty($this -> yzShopSet['isbindmobile'])){
                if(empty($dephp_17) || $dephp_17['isbindmobile'] == 0){
                    if($_GPC['p'] != 'bindmobile' && $_GPC['p'] != 'sendcode'){
                        $dephp_23 = $this -> createMobileUrl('member/bindmobile');
                        redirect($dephp_23);
                        exit();
                    }
                }
            }
        }
    }
    public function createMobileUrl($dephp_24, $dephp_25 = array(), $dephp_26 = true){
        global $_W, $_GPC;
        $dephp_24 = explode('/', $dephp_24);
        if (isset($dephp_24[1])){
            $dephp_25 = array_merge(array('p' => $dephp_24[1]), $dephp_25);
        }
        if (empty($dephp_25['mid'])){
            $dephp_14 = intval($_GPC['mid']);
            if (!empty($dephp_14)){
                $dephp_25['mid'] = $dephp_14;
            }
        }
        return $_W['siteroot'] . 'app/' . substr(parent :: createMobileUrl($dephp_24[0], $dephp_25, true), 2);
    }
    public function createWebUrl($dephp_24, $dephp_25 = array()){
        global $_W;
        $dephp_24 = explode('/', $dephp_24);
        if (count($dephp_24) > 1 && isset($dephp_24[1])){
            $dephp_25 = array_merge(array('p' => $dephp_24[1]), $dephp_25);
        }
        return $_W['siteroot'] . 'web/' . substr(parent :: createWebUrl($dephp_24[0], $dephp_25, true), 2);
    }
    public function createPluginMobileUrl($dephp_24, $dephp_25 = array()){
        global $_W, $_GPC;
        $dephp_24 = explode('/', $dephp_24);
        $dephp_25 = array_merge(array('p' => $dephp_24[0]), $dephp_25);
        $dephp_25['m'] = 'sz_yi';
        if (isset($dephp_24[1])){
            $dephp_25 = array_merge(array('method' => $dephp_24[1]), $dephp_25);
        }
        if (isset($dephp_24[2])){
            $dephp_25 = array_merge(array('op' => $dephp_24[2]), $dephp_25);
        }
        if (empty($dephp_25['mid'])){
            $dephp_14 = intval($_GPC['mid']);
            if (!empty($dephp_14)){
                $dephp_25['mid'] = $dephp_14;
            }
        }
        return $_W['siteroot'] . 'app/' . substr(parent :: createMobileUrl('plugin', $dephp_25, true), 2);
    }
    public function createPluginWebUrl($dephp_24, $dephp_25 = array()){
        global $_W;
        $dephp_24 = explode('/', $dephp_24);
        $dephp_25 = array_merge(array('p' => $dephp_24[0]), $dephp_25);
        if (isset($dephp_24[1])){
            $dephp_25 = array_merge(array('method' => $dephp_24[1]), $dephp_25);
        }
        if (isset($dephp_24[2])){
            $dephp_25 = array_merge(array('op' => $dephp_24[2]), $dephp_25);
        }
        return $_W['siteroot'] . 'web/' . substr(parent :: createWebUrl('plugin', $dephp_25, true), 2);
    }
    public function _exec($dephp_24, $dephp_27 = '', $dephp_28 = true){
        global $_GPC;
                         
        $dephp_24 = strtolower(substr($dephp_24, $dephp_28 ? 5 : 8));
        $dephp_29 = trim($_GPC['p']);
        empty($dephp_29) && $dephp_29 = $dephp_27;
        if ($dephp_28){
            $dephp_30 = IA_ROOT . '/addons/sz_yi/core/web/' . $dephp_24 . '/' . $dephp_29 . '.php';
        }else{
            $this -> setFooter();
            $dephp_30 = IA_ROOT . '/addons/sz_yi/core/mobile/' . $dephp_24 . '/' . $dephp_29 . '.php';
        }
        if (!is_file($dephp_30)){
            message("未找到 控制器文件 {$dephp_24}::{$dephp_29} : {$dephp_30}");
        }
        include $dephp_30;
        exit;
    }
    public function _execFront($dephp_24, $dephp_27 = '', $dephp_28 = true){
        global $_W, $_GPC;
        define('IN_SYS', true);
        $_W['templateType'] = 'web';
        $dephp_24 = strtolower(substr($dephp_24, 5));
        $dephp_29 = trim($_GPC['p']);
        empty($dephp_29) && $dephp_29 = $dephp_27;
        $dephp_30 = IA_ROOT . '/addons/sz_yi/core/web/' . $dephp_24 . '/' . $dephp_29 . '.php';
        if (!is_file($dephp_30)){
            message("未找到 控制器文件 {$dephp_24}::{$dephp_29} : {$dephp_30}");
        }
        include $dephp_30;
        exit;
    }
    public function template($dephp_31, $dephp_32 = TEMPLATE_INCLUDEPATH){
        global $_W;
        $dephp_33 = (isMobile()) ? 'mobile' : 'pc';
        $dephp_4 = m('common') -> getSysset('shop');
        if(strstr($_SERVER['REQUEST_URI'], 'app')){
            if(!isMobile()){
                if($dephp_4['ispc'] == 0){
                    $dephp_33 = 'mobile';
                }
            }
        }
        if($_W['templateType'] && $_W['templateType'] == 'web'){
        }
        $dephp_34 = strtolower($this -> modulename);
        if (defined('IN_SYS')){
            $dephp_35 = IA_ROOT . "/web/themes/{$_W['template']}/{$dephp_34}/{$dephp_31}.html";
            $dephp_36 = IA_ROOT . "/data/tpl/web/{$_W['template']}/{$dephp_34}/{$dephp_31}.tpl.php";
            if (!is_file($dephp_35)){
                $dephp_35 = IA_ROOT . "/web/themes/default/{$dephp_34}/{$dephp_31}.html";
            }
            if (!is_file($dephp_35)){
                $dephp_35 = IA_ROOT . "/addons/{$dephp_34}/template/{$dephp_31}.html";
            }
            if (!is_file($dephp_35)){
                $dephp_35 = IA_ROOT . "/web/themes/{$_W['template']}/{$dephp_31}.html";
            }
            if (!is_file($dephp_35)){
                $dephp_35 = IA_ROOT . "/web/themes/default/{$dephp_31}.html";
            }
            if (!is_file($dephp_35)){
                $dephp_37 = explode('/', $dephp_31);
                $dephp_38 = array_slice($dephp_37, 1);
                $dephp_35 = IA_ROOT . "/addons/{$dephp_34}/plugin/" . $dephp_37[0] . '/template/' . implode('/', $dephp_38) . '.html';
            }
        }else{
            $dephp_39 = m('cache') -> getString('template_shop');
            if (empty($dephp_39)){
                $dephp_39 = 'default';
            }
            if (!is_dir(IA_ROOT . '/addons/sz_yi/template/' . $dephp_33 . '/' . $dephp_39)){
                $dephp_39 = 'default';
            }
            $dephp_36 = IA_ROOT . "/data/tpl/app/sz_yi/{$dephp_39}/{$dephp_33}/{$dephp_31}.tpl.php";
            $dephp_35 = IA_ROOT . "/addons/{$dephp_34}/template/{$dephp_33}/{$dephp_39}/{$dephp_31}.html";
            if (!is_file($dephp_35)){
                $dephp_35 = IA_ROOT . "/addons/{$dephp_34}/template/{$dephp_33}/default/{$dephp_31}.html";
            }
            if (!is_file($dephp_35)){
                $dephp_40 = explode('/', $dephp_31);
                $dephp_41 = $dephp_40[0];
                $dephp_42 = m('cache') -> getString('template_' . $dephp_41);
                if (empty($dephp_42)){
                    $dephp_42 = 'default';
                }
                if (!is_dir(IA_ROOT . '/addons/sz_yi/plugin/' . $dephp_41 . "/template/{$dephp_33}/" . $dephp_42)){
                    $dephp_42 = 'default';
                }
                $dephp_43 = $dephp_40[1];
                $dephp_35 = IA_ROOT . '/addons/sz_yi/plugin/' . $dephp_41 . "/template/{$dephp_33}/" . $dephp_42 . "/{$dephp_43}.html";
            }
            if (!is_file($dephp_35)){
                $dephp_35 = IA_ROOT . "/app/themes/{$_W['template']}/{$dephp_31}.html";
            }
            if (!is_file($dephp_35)){
                $dephp_35 = IA_ROOT . "/app/themes/default/{$dephp_31}.html";
            }
        }
        if (!is_file($dephp_35)){
            exit("Error: template source '{$dephp_31}' is not exist!");
        }
        if (DEVELOPMENT || !is_file($dephp_36) || filemtime($dephp_35) > filemtime($dephp_36)){
            shop_template_compile($dephp_35, $dephp_36, true);
        }
        return $dephp_36;
    }
    public function getUrl(){
        if (p('commission')){
            $dephp_4 = p('commission') -> getSet();
            if (!empty($dephp_4['level'])){
                return $this -> createPluginMobileUrl('commission/myshop');
            }
        }
        return $this -> createMobileUrl('shop');
    }
}
