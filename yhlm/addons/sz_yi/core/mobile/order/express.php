<?php

if (!defined('IN_IA')) {

    exit('Access Denied');

}

global $_W, $_GPC;



defined('EBusinessID') or define('EBusinessID', '1534195');

//电商加密私钥，快递鸟提供，注意保管，不要泄漏

defined('AppKey') or define('AppKey', '26815d0a-f35e-48a3-a8e8-1aa4b1d4cfcb');

//测试请求url

defined('ReqURL') or define('ReqURL', 'http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx');

//$logisticResult = orderTracesSubByJson('','');

//echo $logisticResult;



function orderTracesSubByJson($code,$sn)

{



    $requestData = "{'OrderCode': 'SF201608081055208281'," .

        "'ShipperCode':'$code'," .

        "'LogisticCode':'$sn'," .

        "'PayType':1," .

        "'ExpType':1," .

        "'IsNotice':0," .

        "'Cost':1.0," .

        "'OtherCost':1.0," .

        "'Sender':" .

        "{" .

        "'Company':'LV','Name':'Taylor','Mobile':'15018442396','ProvinceName':'上海','CityName':'上海','ExpAreaName':'青浦区','Address':'明珠路73号'}," .

        "'Receiver':" .

        "{" .

        "'Company':'GCCUI','Name':'Yann','Mobile':'15018442396','ProvinceName':'北京','CityName':'北京','ExpAreaName':'朝阳区','Address':'三里屯街道雅秀大厦'}," .

        "'Commodity':" .

        "[{" .

        "'GoodsName':'鞋子','Goodsquantity':1,'GoodsWeight':1.0}]," .

        "'Weight':1.0," .

        "'Quantity':1," .

        "'Volume':0.0," .

        "'Remark':'小心轻放'}";





    $datas = array(

        'EBusinessID' => EBusinessID,

        'RequestType' => '1002',

        'RequestData' => urlencode($requestData),

        'DataType' => '2',

    );

//    print_r($datas);exit;

    $datas['DataSign'] = encrypt($requestData, AppKey);

    $result = sendPost(ReqURL, $datas);



    //根据公司业务处理返回的信息......



    return $result;

}



/**

 *  post提交数据

 * @param  string $url 请求Url

 * @param  array $datas 提交的数据

 * @return url响应返回的html

 */

function sendPost($url, $datas)

{

    $temps = array();

    foreach ($datas as $key => $value) {

        $temps[] = sprintf('%s=%s', $key, $value);

    }

    $post_data = implode('&', $temps);

    $url_info = parse_url($url);

    if (empty($url_info['port'])) {

        $url_info['port'] = 80;

    }

    $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";

    $httpheader .= "Host:" . $url_info['host'] . "\r\n";

    $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";

    $httpheader .= "Content-Length:" . strlen($post_data) . "\r\n";

    $httpheader .= "Connection:close\r\n\r\n";

    $httpheader .= $post_data;

    $fd = fsockopen($url_info['host'], $url_info['port']);

    fwrite($fd, $httpheader);

    $gets = "";

    $headerFlag = true;

    while (!feof($fd)) {

        if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {

            break;

        }

    }

    while (!feof($fd)) {

        $gets .= fread($fd, 128);

    }

    fclose($fd);



    return $gets;

}

/**

 * 电商Sign签名生成

 * @param data 内容

 * @param appkey Appkey

 * @return DataSign签名

 */

function bianma($data)

{

    $data=substr($data,0,2);

   $list=array('顺丰速运'=>SF,

       '百世快递'=>HTKY,

        '中通快递'=>ZTO,

         '申通快递'=>STO,

         '圆通速递'=>YTO,

         '韵达快运'=>YD,

         '邮政快递包裹'=>YZPY,

         'EMS'=>EMS,

         '天天快递'=>HHTT,

         '京东快递'=>JD,

         '优速快递'=>UC,

         '德邦快递'=>DBL,

         '宅急送'=>ZJS,

         'TNT快递'=>TNT,

         'UPS'=>UPS,

         'DHL'=>DHL,

   );



   foreach($list as $k=>$v){

       $key=substr($k,0,2);



       if($data==$key){



           return $v;

       }

   }

}

function encrypt($data, $appkey)

{

    return urlencode(base64_encode(md5($data . $appkey)));

}



function sortByTime($a, $b)

{

    if ($a['ts'] == $b['ts']) {

        return 0;

    } else {

        return $a['ts'] > $b['ts'] ? 1 : -1;

    }

}



function getList($express, $expresssn)

{



//	$url = "http://wap.kuaidi100.com/wap_result.jsp?rand=" . time() . "&id={$express}&fromWeb=null&postid={$expresssn}";



//    load()->func('communication');

//	$resp = ihttp_request($url);

//    print_r($resp);exit;

//	$content = $resp['content'];

	if (empty($content)) {

		return array();

	}

	preg_match_all('/\\<p\\>&middot;(.*)\\<\\/p\\>/U', $content, $arr);

	if (!isset($arr[1])) {

		return false;

	}

	return $arr[1];

}

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';



$openid    = m('user')->getOpenid();

$uniacid   = $_W['uniacid'];

$orderid   = intval($_GPC['id']);

if (!empty($_GPC['merch'])) {

	$condition='';

	

	if ($_GPC['merch'] == 2) {

		$condition.=' and dealmerchid = 0 and merchid = 0 ';

	}else if ($_GPC['merch'] == 3){

		$condition.=' and merchid > 0 ';

	}else if($_GPC['merch'] == 5){

		$condition.=' and dealmerchid > 0 ';

	}

 

	$uid=pdo_fetchcolumn('select uid from '.tablename('sz_yi_perm_user').' where uniacid = :uniacid and openid = :openid '.$condition,array(':uniacid'=>$_W['uniacid'],':openid'=>$openid));

	

}

if (!empty($_GPC['merch'])) {

    $order = pdo_fetch('select * from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid and supplier_uid=:supplier_uid limit 1', array(':id' => $orderid, ':uniacid' => $uniacid, ':supplier_uid' => $uid));



}else{

    $order = pdo_fetch('select * from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1', array(':id' => $orderid, ':uniacid' => $uniacid, ':openid' => $openid));

}



        $code=bianma($order['expresscom']);



//        $wuliu=$order['expresscom'];

        $wuliu_num=$order['expresssn'];



            $logisticResult = orderTracesSubByJson($code,$wuliu_num);

            $detail=json_decode($logisticResult,ture);

//            print_r($detail);exit;

//            echo $logisticResult;







//        $url = "http://wap.kuaidi100.com/wap_result.jsp?rand=20120517&id='.$wuliu.'&fromWeb=null&&postid='.$wuliu_num";

//

//        header("location:$url");

if ($_W['isajax']) {

	if ($operation == 'display') {

		if (!empty($_GPC['merch'])) {

			$order = pdo_fetch('select * from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid and supplier_uid=:supplier_uid limit 1', array(':id' => $orderid, ':uniacid' => $uniacid, ':supplier_uid' => $uid));

		}else{

			$order = pdo_fetch('select * from ' . tablename('sz_yi_order') . ' where id=:id and uniacid=:uniacid and openid=:openid limit 1', array(':id' => $orderid, ':uniacid' => $uniacid, ':openid' => $openid));

		}

//        $wuliu=$order['expresscom'];

//        $wuliu_num=$order['expresssn'];

//        print_r($wuliu);exit;

//        $url = "http://wap.kuaidi100.com/wap_result.jsp?rand=20120517&id='.$wuliu.'&fromWeb=null&&postid='.$wuliu_num";

//        header("location:$url");

		if (empty($order)) {

			show_json(0);

		}

		$goods = pdo_fetchall("select og.goodsid,og.price,g.title,g.thumb,og.total,g.credit,og.optionid,og.optionname as optiontitle,g.isverify,g.storeids  from " . tablename('sz_yi_order_goods') . " og " . " left join " . tablename('sz_yi_goods') . " g on g.id=og.goodsid " . " where og.orderid=:orderid and og.uniacid=:uniacid ", array(':uniacid' => $uniacid, ':orderid' => $orderid));

		$goods = set_medias($goods, 'thumb');

		$order['goodstotal'] = count($goods);

		$set = set_medias(m('common')->getSysset('shop'), 'logo');

		show_json(1, array('order' => $order, 'goods' => $goods, 'set' => $set));

	} else if ($operation == 'step') {

		$express = trim($_GPC['express']);     // 快递名称

        $code=bianma($order['expresscom']);    //编码，判断是哪个快递

        $wuliu_num=$order['expresssn'];      //单号



        $logisticResult = orderTracesSubByJson($code,$wuliu_num);

        $list=json_decode($logisticResult,ture);

        $list=$list['Traces'];

//        print_r($list);exit;

//		$arr = getList($express, $expresssn);

//

//		if (!$arr) {

//			$arr = getList($express, $expresssn);

//			if (!$arr) {

//				show_json(1, array('list' => array()));

//			}

//		}

//		$len = count($arr);

//		$step1 = explode("<br />", str_replace("&middot;", "", $arr[0]));

//		$step2 = explode("<br />", str_replace("&middot;", "", $arr[$len - 1]));

//		for ($i = 0; $i < $len; $i++) {

//			if (strtotime(trim($step1[0])) > strtotime(trim($step2[0]))) {

//				$row = $arr[$i];

//			} else {

//				$row = $arr[$len - $i - 1];

//			}

//			$step = explode("<br />", str_replace("&middot;", "", $row));

//			$list[] = array('time' => trim($step[0]), 'step' => trim($step[1]), 'ts' => strtotime(trim($step[0])));

//		}

		show_json(1, array('list' => $list));

	}

}

include $this->template('order/express');

