<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

//如果该uid的uid存在表中那他就是员工
function check_agent($uid=0){
    global $_W;
    $auth=pdo_fetchcolumn('select merchid from '.tablename('sz_yi_staff').' where uniacid= :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$uid));
    
    $cauth=pdo_fetch('select * from '.tablename('sz_yi_staff').' where uniacid= :uniacid and uid = :uid',array(':uniacid'=>$_W['uniacid'],':uid'=>$auth));       
    if ($cauth) {
        return $cauth;
    }
    return false;
}

function autowrap($fontsize, $angle, $fontface, $string, $width) {
    // 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
    $content = "";

    // 将字符串拆分成一个个单字 保存到数组 letter 中
    for ($i=0;$i<mb_strlen($string);$i++) {
        $letter[] = mb_substr($string, $i, 1,'utf-8');
    }
    foreach ($letter as $l) {
        $teststr = $content." ".$l;
        $testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
        // 判断拼接后的字符串是否超过预设的宽度
        if (($testbox[2] > $width) && ($content !== "")) {
                $content .= "\n";
        }
        $content .= $l;   
    }

    return $content;
}


function send_zhangjun($mobile,$content){//掌骏
    $set = m('common')->getSysset();         
    // if(empty($code)){             
        // $content = "【金顺科技】您的清洗套餐即将到期请尽快使用：".$code."。如非本人操作，可不用理会！";
    // }else{
        // $content = "【易货联盟】您的手机验证码为：".$code."，该短信1分钟内有效。如非本人操作，可不用理会！";
    // }                                          

    
    $time=date('ymdhis',time());
    $arr=array(                          
        'uname'=>$set['sms']['account'],         
        'pwd'=>$set['sms']['password'],      
        'time'=>$time
    );
    $signPars='';
    foreach($arr as $v) {
        $signPars .=$v;
    }

    $sign = strtolower(md5($signPars));
 
    $arrs=array(
        'userid'=>$set['sms']['userid'],
        'timestamp'=>$time,
        'sign'=>$sign,
        'mobile'=>$mobile,
        'content'=>$content,
        'action'=>'send'    
    );
    $url='http://120.77.14.55:8888/v2sms.aspx';
    $ret=call($url, $arrs);
    return $ret;            
}


function call($url,$arr,$second = 30){


    $ch = curl_init();

    //设置超时
     
    curl_setopt($ch, CURLOPT_TIMEOUT, $second);
     
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);//严格校验
    //设置header
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    /*
     if($useCert == true){
     //设置证书
     //使用证书：cert 与 key 分别属于两个.pem文件
     curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
     curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
     curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
     curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
    } */
    //post提交方式
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
    //运行curl
    $data = curl_exec($ch);

    return xml_to_array($data);
}


function icheck_gpc($var)
{
    if (is_array($var)) {
        foreach ($var as $key => $value) {
            $var[stripslashes($key)] = icheck_gpc($value);
        }
    } else {
        $var = inject_check($var);
        if ($var) {
            exit('非法参数');
        }
    }
    return $var;
}

/**
 * 校验防止SQL注入
 */
function inject_check($sql_str)
{
    return preg_match('/eval|select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i', $sql_str);
}

function sz_tpl_form_field_date($name, $value = '', $withtime = false)
{
	$s = '';
	if (!defined('TPL_INIT_DATA')) {
		$s = '
			<script type="text/javascript">
				require(["datetimepicker"], function(){
					$(function(){
						$(".datetimepicker").each(function(){
							var option = {
								lang : "zh",
								step : "10",
								timepicker : ' . (!empty($withtime) ? "true" : "false") .
			',closeOnDateSelect : true,
			format : "Y-m-d' . (!empty($withtime) ? ' H:i:s"' : '"') .
			'};
			$(this).datetimepicker(option);
		});
	});
});
</script>';
		define('TPL_INIT_DATA', true);
	}
	$withtime = empty($withtime) ? false : true;
	if (!empty($value)) {
		$value = strexists($value, '-') ? strtotime($value) : $value;
	} else {
		$value = TIMESTAMP;
	}
	$value = ($withtime ? date('Y-m-d H:i:s', $value) : date('Y-m-d', $value));
	$s .= '<input type="text" name="' . $name . '"  value="'.$value.'" placeholder="请选择日期时间" readonly="readonly" class="datetimepicker form-control" style="padding-left:12px;" />';
	return $s;
}

function isMobile() {
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])){
        return true;
    }
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])) {
        //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    //判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array (
            'nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile',
            'WindowsWechat'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

function chmod_dir($dir,$chmod='') {
    if(is_dir($dir)) {
        if($handle = opendir($dir)) {
            while(false !== ($file = readdir($handle))) {
                if(is_dir($dir.'/'.$file)) {
                    if($file != '.' && $file != '..') {
                        $path = $dir.'/'.$file;
                        $chmod ? chmod($path,$chmod) : FALSE;
                        chmod_dir($path);
                    }
                }else{
                    $path = $dir.'/'.$file;
                    $chmod ? chmod($path,$chmod) : FALSE;
                }
            }
        }
        closedir($handle);
    }
}

function curl_download($url, $dir) {
    $ch = curl_init($url);
    $fp = fopen($dir, "wb");
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $res=curl_exec($ch);
    curl_close($ch);
    fclose($fp);
    return $res;
}

function send_sms($account, $pwd, $mobile, $code)
{
    $content = "您的验证码是：". $code ."。请不要把验证码泄露给其他人。如非本人操作，可不用理会！";
    
    $smsrs = file_get_contents('http://106.ihuyi.cn/webservice/sms.php?method=Submit&account='.$account.'&password='.$pwd.'&mobile=' . $mobile . '&content='.urldecode($content));


 

   return xml_to_array($smsrs);
}

function send_sms_alidayu($mobile, $code, $templateType){
    $set = m('common')->getSysset();
    include IA_ROOT . "/addons/sz_yi/alifish/TopSdk.php";
    //$appkey = '23355246';
    //$secret = '0c34a4887d2f52a6365a266bb3b38d25';

    switch ($templateType) {
        case 'reg':
            $templateCode = $set['sms']['templateCode'];
            break;
        case 'forget':
            $templateCode = $set['sms']['templateCodeForget'];
            break;
        default:
            $templateCode = $set['sms']['templateCode'];
            break;
    }

    $c = new TopClient;
    $c->appkey = $set['sms']['appkey'];
    $c->secretKey = $set['sms']['secret'];
    $req = new AlibabaAliqinFcSmsNumSendRequest;
    $req->setExtend("123456");
    $req->setSmsType("normal");
    $req->setSmsFreeSignName($set['sms']['signname']);
    $req->setSmsParam("{\"code\":\"{$code}\",\"product\":\"{$set['sms']['product']}\"}");
    $req->setRecNum($mobile);
    $req->setSmsTemplateCode($templateCode);
    $resp = $c->execute($req);
    return objectArray($resp);
}

function xml_to_array($xml)
{
    $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
    if(preg_match_all($reg, $xml, $matches)){
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++){
            $subxml= $matches[2][$i];
            $key = $matches[1][$i];
                    if(preg_match( $reg, $subxml )){
                            $arr[$key] = xml_to_array( $subxml );
                    }else{
                            $arr[$key] = $subxml;
                    }
            }
    }
    return $arr;
}

function redirect($url, $sec=0){
    echo "<meta http-equiv=refresh content='{$sec}; url={$url}'>";
    exit;
}
function m($name = '')
{
    static $_modules = array();
    if (isset($_modules[$name])) {
        return $_modules[$name];
    }
    $model = SZ_YI_CORE . "model/" . strtolower($name) . '.php';
    if (!is_file($model)) {
        die(' Model ' . $name . ' Not Found!');
    }
    require $model;
    $class_name      = 'Sz_DYi_' . ucfirst($name);
    $_modules[$name] = new $class_name();
    return $_modules[$name];
}
function isEnablePlugin($name){
    $plugins = m("cache")->getArray("plugins", "global");
    if($plugins){
        foreach($plugins as $p){
            if($p['identity'] == $name){
                if($p['status']){
                    return true;
                }
                else{
                    return false;
                }
            }
        }
    }
}
function p($name = '')
{
    if(!isEnablePlugin($name)){
        return false;
    }
    if ($name != 'perm' && !IN_MOBILE) {
        static $_perm_model;
        if (!$_perm_model) {
            $perm_model_file = SZ_YI_PLUGIN . 'perm/model.php';
            if (is_file($perm_model_file)) {
                require $perm_model_file;
                $perm_class_name = 'PermModel';
                $_perm_model     = new $perm_class_name('perm');
            }
        }
        if ($_perm_model) {
            if (!$_perm_model->check_plugin($name)) {
                return false;
            }
        }
    }

    static $_plugins = array();
    if (isset($_plugins[$name])) {
        return $_plugins[$name];
    }
    $model = SZ_YI_PLUGIN . strtolower($name) . '/model.php';
    if (!is_file($model)) {
        return false;
    }
    require $model;
    $class_name      = ucfirst($name) . 'Model';
    $_plugins[$name] = new $class_name($name);
    return $_plugins[$name];
}
function byte_format($input, $dec = 0)
{
    $prefix_arr = array(
        ' B',
        'K',
        'M',
        'G',
        'T'
    );
    $value      = round($input, $dec);
    $i          = 0;
    while ($value > 1024) {
        $value /= 1024;
        $i++;
    }
    $return_str = round($value, $dec) . $prefix_arr[$i];
    return $return_str;
}
function save_media($url)
{
    $config = array(
        'qiniu' => false
    );
    $plugin = p('qiniu');
    if ($plugin) {
        $config = $plugin->getConfig();
        if ($config) {
            if (strexists($url, $config['url'])) {
                return $url;
            }
            $qiniu_url = $plugin->save(tomedia($url), $config);
            if (empty($qiniu_url)) {
                return $url;
            }
            return $qiniu_url;
        }
        return $url;
    }
    return $url;
}
function is_array2($array)
{
    if (is_array($array)) {
        foreach ($array as $k => $v) {
            return is_array($v);
        }
        return false;
    }
    return false;
}
function set_medias($list = array(), $fields = null)
{
    if (empty($fields)) {
        foreach ($list as &$row) {
            $row = tomedia($row);
        }
        return $list;
    }
    if (!is_array($fields)) {
        $fields = explode(',', $fields);
    }
    if (is_array2($list)) {
        foreach ($list as $key => &$value) {
            foreach ($fields as $field) {
                if (isset($list[$field])) {
                    $list[$field] = tomedia($list[$field]);
                }
                if (is_array($value) && isset($value[$field])) {
                    $value[$field] = tomedia($value[$field]);
                }
            }
        }
        return $list;
    } else {
        foreach ($fields as $field) {
            if (isset($list[$field])) {
                $list[$field] = tomedia($list[$field]);
            }
        }
        return $list;
    }
}
function get_last_day($year, $month)
{
    return date('t', strtotime("{$year}-{$month} -1"));
}
function show_message($msg = '', $url = '', $type = 'success')
{
    $scripts = "<script language='javascript'>require(['core'],function(core){ core.message('" . $msg . "','" . $url . "','" . $type . "')})</script>";
    die($scripts);
}
function show_json($status = 1, $return = null)
{
    $ret = array(
        'status' => $status
    );
    if ($return) {
        $ret['result'] = $return;
    }
    die(json_encode($ret));
}


function show_myjson($status = 1,$result, $return = null)
{       
    $ret = array(
        'ret' => $status,
        'msg' => $result,
    );
                         
    if ($return) {                       
        $ret['model'] = $return;
    }
    die(json_encode($ret));
}

// 商家专用 查找perm_user获取openid
function uid2openid($uid){
    global $_W;
    $sql='select openid from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and uid = :uid ';
    $params=array(
        ':uniacid'=>$_W['uniacid'],
        ':uid'=>$uid,
    );                       

    $openid=pdo_fetchcolumn($sql,$params);
    return $openid?:false;          
}
function is_weixin()
{
    if (empty($_SERVER['HTTP_USER_AGENT']) || strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false && strpos($_SERVER['HTTP_USER_AGENT'], 'Windows Phone') === false) {
        return false;
    }
    return true;
}
function b64_encode($obj)
{
    if (is_array($obj)) {
        return urlencode(base64_encode(json_encode($obj)));
    }
    return urlencode(base64_encode($obj));
}
function b64_decode($str, $is_array = true)
{
    $str = base64_decode(urldecode($str));
    if ($is_array) {
        return json_decode($str, true);
    }
    return $str;
}
function create_image($img)
{
    $ext = strtolower(substr($img, strrpos($img, '.')));
    if ($ext == '.png') {
        $thumb = imagecreatefrompng($img);
    } else if ($ext == '.gif') {
        $thumb = imagecreatefromgif($img);
    } else {
        $thumb = imagecreatefromjpeg($img);
    }
    return $thumb;
}
function get_authcode()
{
    $auth = get_auth();
    return empty($auth['code']) ? '' : $auth['code'];
}
function get_auth()
{
    global $_W;
    $set  = pdo_fetch('select sets from ' . tablename('sz_yi_sysset') . ' order by id asc limit 1');
    $sets = iunserializer($set['sets']);
    if (is_array($sets)) {
        return is_array($sets['auth']) ? $sets['auth'] : array();
    }
    return array();
}
function check_shop_auth($url = '', $type = 's')
{
    global $_W, $_GPC;
    if ($_W['ispost'] && $_GPC['do'] != 'auth') {
        $auth = get_auth();
        load()->func('communication');
        $domain  = $_SERVER['HTTP_HOST'];
        $ip      = gethostbyname($domain);
        $setting = setting_load('site');
        $id      = isset($setting['site']['key']) ? $setting['site']['key'] : '0';
        if (empty($type) || $type == 's') {
            $post_data = array(
                'type' => $type,
                'ip' => $ip,
                'id' => $id,
                'code' => $auth['code'],
                'domain' => $domain
            );
        } else {
            $post_data = array(
                'type' => 'm',
                'm' => $type,
                'ip' => $ip,
                'id' => $id,
                'code' => $auth['code'],
                'domain' => $domain
            );
        }
        $resp   = ihttp_post($url, $post_data);
        $status = $resp['content'];
        if ($status != '1') {
            message(base64_decode('6K+35Yiw5b6u6LWe5a6Y5pa56LSt5LmwLeS6uuS6uuWVhuWfjuaooeWdly1iYnMuMDEyd3ouY29tIQ=='), '', 'error');
        }
    }
}
$my_scenfiles = array();
function my_scandir($dir)
{
    global $my_scenfiles;
    if ($handle = opendir($dir)) {
        while (($file = readdir($handle)) !== false) {
            if ($file != ".." && $file != "." && $file != ".git"  && $file != "tmp") {
                if (is_dir($dir . "/" . $file)) {
                    my_scandir($dir . "/" . $file);
                } else {
                    $my_scenfiles[] = $dir . "/" . $file;
                }
            }
        }
        closedir($handle);
    }
}
function shop_template_compile($from, $to, $inmodule = false)
{
    $path = dirname($to);
    if (!is_dir($path)) {
        load()->func('file');
        mkdirs($path);
    }
    $content = shop_template_parse(file_get_contents($from), $inmodule);
    if (IMS_FAMILY == 'x' && !preg_match('/(footer|header|account\/welcome|login|register)+/', $from)) {
        $content = str_replace('微赞', '系统', $content);
    }
    file_put_contents($to, $content);
}
function shop_template_parse($str, $inmodule = false)
{
    $str = template_parse($str, $inmodule);
    $str = preg_replace('/{ifp\s+(.+?)}/', '<?php if(cv($1)) { ?>', $str);
    $str = preg_replace('/{ifpp\s+(.+?)}/', '<?php if(cp($1)) { ?>', $str);
    $str = preg_replace('/{ife\s+(\S+)\s+(\S+)}/', '<?php if( ce($1 ,$2) ) { ?>', $str);
    return $str;
}
function ce($permtype = '', $item = null)
{
    $perm = p('perm');
    if ($perm) {
        return $perm->check_edit($permtype, $item);
    }
    return true;
}
function cv($permtypes = '')
{

    $perm = p('perm');
    if ($perm) {
        return $perm->check_perm($permtypes);
    }
    return true;
}
function ca($permtypes = '')
{                
    if (!cv($permtypes)) {
        message('您没有权限操作，请联系管理员!', '', 'error');
    }
}
function cp($pluginname = '')    
{
    $perm = p('perm');
    if ($perm) {        
        return $perm->check_plugin($pluginname);
    }

    return true;
}
function cpa($pluginname = '')
{
    if (!cp($pluginname)) {
        message('您没有权限操作，请联系管理员!', '', 'error');
    }
}
function plog($type = '', $op = '')
{
    $perm = p('perm');
    if ($perm) {
        $perm->log($type, $op);
    }
}
//stdClass Object 转 数组
function objectArray($array){
    if(is_object($array)){
        $array = (array)$array;
    }
    if(is_array($array)){
        foreach($array as $key=>$value){
            $array[$key] = objectArray($value);
        }
    }
    return $array;
}

if(!function_exists('tpl_form_field_category_3level')){
    function tpl_form_field_category_3level($name, $parents, $children, $parentid, $childid, $thirdid){
        return tpl_form_field_category_level3($name, $parents, $children, $parentid, $childid, $thirdid);
    }
}

if(!function_exists('tpl_form_field_category_2level')){
    function tpl_form_field_category_2level($name, $parents, $children, $parentid, $childid, $thirdid){
        return tpl_form_field_category_level2($name, $parents, $children, $parentid, $childid, $thirdid);
    }
}

// function tpl_form_field_category_level3($name, $parents, $children, $parentid, $childid, $thirdid)
// {
//     $html = '
// <script type="text/javascript">
// 	window._' . $name . ' = ' . json_encode($children) . ';
// </script>';
//     if (!defined('TPL_INIT_CATEGORY_THIRD')) {
//         $html .= '
// <script type="text/javascript">
// 	function renderCategoryThird(obj, name){
// 		var index = obj.options[obj.selectedIndex

function tpl_form_field_category_level3($name, $parents, $children, $parentid, $childid, $thirdid)
{
    $html = '
<script type="text/javascript">
    window._' . $name . ' = ' . json_encode($children) . ';
</script>';
    if (!defined('TPL_INIT_CATEGORY_THIRD')) {
        $html .= '
<script type="text/javascript">
    function renderCategoryThird(obj, name){
        var index = obj.options[obj.selectedIndex].value;
        require([\'jquery\', \'util\'], function($, u){
            $selectChild = $(\'#\'+name+\'_child\');
                                                      $selectThird = $(\'#\'+name+\'_third\');
            var html = \'<option value="0">请选择二级分类</option>\';
                                                      var html1 = \'<option value="0">请选择三级分类</option>\';
            if (!window[\'_\'+name] || !window[\'_\'+name][index]) {
                $selectChild.html(html);
                                                                        $selectThird.html(html1);
                return false;
            }
            for(var i=0; i< window[\'_\'+name][index].length; i++){
                html += \'<option value="\'+window[\'_\'+name][index][i][\'id\']+\'">\'+window[\'_\'+name][index][i][\'name\']+\'</option>\';
            }
            $selectChild.html(html);
                                                    $selectThird.html(html1);
        });
    }
        function renderCategoryThird1(obj, name){
        var index = obj.options[obj.selectedIndex].value;
        require([\'jquery\', \'util\'], function($, u){
            $selectChild = $(\'#\'+name+\'_third\');
            var html = \'<option value="0">请选择三级分类</option>\';
            if (!window[\'_\'+name] || !window[\'_\'+name][index]) {
                $selectChild.html(html);
                return false;
            }
            for(var i=0; i< window[\'_\'+name][index].length; i++){
                html += \'<option value="\'+window[\'_\'+name][index][i][\'id\']+\'">\'+window[\'_\'+name][index][i][\'name\']+\'</option>\';
            }
            $selectChild.html(html);
        });
    }
</script>
            ';
        define('TPL_INIT_CATEGORY_THIRD', true);
    }
    $html .= '<div class="row row-fix tpl-category-container">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <select class="form-control tpl-category-parent" id="' . $name . '_parent" name="' . $name . '[parentid]" onchange="renderCategoryThird(this,\'' . $name . '\')">
            <option value="0">请选择一级分类</option>';
    $ops = '';
    foreach ($parents as $row) {
        $html .= '
            <option value="' . $row['id'] . '" ' . (($row['id'] == $parentid) ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
    }
    $html .= '
        </select>
    </div>
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <select class="form-control tpl-category-child" id="' . $name . '_child" name="' . $name . '[childid]" onchange="renderCategoryThird1(this,\'' . $name . '\')">
            <option value="0">请选择二级分类</option>';
    if (!empty($parentid) && !empty($children[$parentid])) {
        foreach ($children[$parentid] as $row) {
            $html .= '
            <option value="' . $row['id'] . '"' . (($row['id'] == $childid) ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
        }
    }
    $html .= '
        </select>
    </div>
                  <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <select class="form-control tpl-category-child" id="' . $name . '_third" name="' . $name . '[thirdid]">
            <option value="0">请选择三级分类</option>';
    if (!empty($childid) && !empty($children[$childid])) {
        foreach ($children[$childid] as $row) {
            $html .= '
            <option value="' . $row['id'] . '"' . (($row['id'] == $thirdid) ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
        }
    }
    $html .= '</select>
    </div>
</div>';
    return $html;
}

function tpl_form_field_category_level2($name, $parents, $children, $parentid, $childid){
    $html = '
        <script type="text/javascript">
            window._' . $name . ' = ' . json_encode($children) . ';
        </script>';
            if (!defined('TPL_INIT_CATEGORY')) {
                $html .= '
        <script type="text/javascript">
            function renderCategory(obj, name){
                var index = obj.options[obj.selectedIndex].value;
                require([\'jquery\', \'util\'], function($, u){
                    $selectChild = $(\'#\'+name+\'_child\');
                    var html = \'<option value="0">请选择二级分类</option>\';
                    if (!window[\'_\'+name] || !window[\'_\'+name][index]) {
                        $selectChild.html(html);
                        return false;
                    }
                    for(var i=0; i< window[\'_\'+name][index].length; i++){
                        html += \'<option value="\'+window[\'_\'+name][index][i][\'id\']+\'">\'+window[\'_\'+name][index][i][\'name\']+\'</option>\';
                    }
                    $selectChild.html(html);
                });
            }
        </script>
                    ';
                define('TPL_INIT_CATEGORY', true);
            }

            $html .=
                '<div class="row row-fix tpl-category-container">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <select class="form-control tpl-category-parent" id="' . $name . '_parent" name="' . $name . '[parentid]" onchange="renderCategory(this,\'' . $name . '\')">
                    <option value="0">请选择一级分类</option>';
            $ops = '';
            foreach ($parents as $row) {
                $html .= '
                    <option value="' . $row['id'] . '" ' . (($row['id'] == $parentid) ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
            }
            $html .= '
                </select>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <select class="form-control tpl-category-child" id="' . $name . '_child" name="' . $name . '[childid]">
                    <option value="0">请选择二级分类</option>';
            if (!empty($parentid) && !empty($children[$parentid])) {
                foreach ($children[$parentid] as $row) {
                    $html .= '
                    <option value="' . $row['id'] . '"' . (($row['id'] == $childid) ? 'selected="selected"' : '') . '>' . $row['name'] . '</option>';
                }
            }
            $html .= '
                </select>
            </div>
        </div>
    ';
    return $html;
}



function is_app()
{

    if( strpos($_SERVER['HTTP_USER_AGENT'], 'CK 2.0')  ){
        return true;
    }

    return false;
}
