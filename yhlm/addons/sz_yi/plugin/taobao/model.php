<?php
 if (!defined('IN_IA')){
    exit('Access Denied');
}
if (!class_exists('TaobaoModel')){
    class TaobaoModel extends PluginModel{
        function get_item_taobao($dephp_0 = '', $dephp_1 = '', $dephp_2 = 0, $dephp_3 = 0, $dephp_4 = 0){
            global $_W;

             $dephp_5 = pdo_fetch('select * from ' . tablename('sz_yi_goods') . ' where uniacid=:uniacid and taobaoid=:taobaoid limit 1', array(':uniacid' => $_W['uniacid'], ':taobaoid' => $dephp_0));
            if ($dephp_5){
            }
            $dephp_6 = $this -> get_info_url($dephp_0);
            load() -> func('communication');
            $dephp_7 = ihttp_get($dephp_6);
            if (!isset($dephp_7['content'])){
                return array('result' => '0', 'error' => '未从淘宝获取到商品信息!');
            }
            $dephp_8 = $dephp_7['content'];
            if (strexists($dephp_7['content'], 'ERRCODE_QUERY_DETAIL_FAIL')){
                return array('result' => '0', 'error' => '宝贝不存在!');
            }



            $dephp_9 = json_decode($dephp_8, true);
            $dephp_10 = $dephp_9['data'];
            $dephp_11 = $dephp_10['itemInfoModel'];
            $dephp_12 = array();
            $dephp_12['id'] = $dephp_5['id'];
            $dephp_12['pcate'] = $dephp_2;
            $dephp_12['ccate'] = $dephp_3;
            $dephp_12['tcate'] = $dephp_4;
            $dephp_12['itemId'] = $dephp_11['itemId'];
            $dephp_12['title'] = $dephp_11['title'];
            $dephp_12['pics'] = $dephp_11['picsPath'];
            $dephp_13 = array();
            if (isset($dephp_10['props'])){
                $dephp_14 = $dephp_10['props'];
                foreach ($dephp_14 as $dephp_15){
                    $dephp_13[] = array('title' => $dephp_15['name'], 'value' => $dephp_15['value']);
                }
            }
            $dephp_12['params'] = $dephp_13;
            $dephp_16 = array();
            $dephp_17 = array();
            if (isset($dephp_10['skuModel'])){
                $dephp_18 = $dephp_10['skuModel'];
                if (isset($dephp_18['skuProps'])){
                    $dephp_19 = $dephp_18['skuProps'];
                    foreach ($dephp_19 as $dephp_20){
                        $dephp_21 = array();
                        foreach ($dephp_20['values'] as $dephp_22){
                            $dephp_21[] = array('valueId' => $dephp_22['valueId'], 'title' => $dephp_22['name'], 'thumb' => !empty($dephp_22['imgUrl']) ? $dephp_22['imgUrl'] : '');
                        }
                        $dephp_23 = array('propId' => $dephp_20['propId'], 'title' => $dephp_20['propName'], 'items' => $dephp_21);
                        $dephp_16[] = $dephp_23;
                    }
                }
                if (isset($dephp_18['ppathIdmap'])){
                    $dephp_24 = $dephp_18['ppathIdmap'];
                    foreach ($dephp_24 as $dephp_25 => $dephp_26){
                        $dephp_27 = array();
                        $dephp_28 = explode(';', $dephp_25);
                        foreach ($dephp_28 as $dephp_29){
                            $dephp_30 = explode(':', $dephp_29);
                            $dephp_27[] = array('propId' => $dephp_30[0], 'valueId' => $dephp_30[1]);
                        }
                        $dephp_17[] = array('option_specs' => $dephp_27, 'skuId' => $dephp_26, 'stock' => 0, 'marketprice' => 0, 'specs' => "");
                    }
                }
            }



            $dephp_12['specs'] = $dephp_16;
            $dephp_31 = $dephp_10['apiStack'][0]['value'];
            $dephp_32 = json_decode($dephp_31, true);
            $dephp_33 = array();
            $dephp_34 = $dephp_32['data'];
            $dephp_35 = $dephp_34['itemInfoModel'];
            $dephp_12['total'] = $dephp_35['quantity'];
            $dephp_12['sales'] = $dephp_35['totalSoldQuantity'];
            if (isset($dephp_34['skuModel'])){
                $dephp_36 = $dephp_34['skuModel'];
                if (isset($dephp_36['skus'])){
                    $dephp_37 = $dephp_36['skus'];
                    foreach ($dephp_37 as $dephp_25 => $dephp_38){
                        $dephp_39 = $dephp_25;
                        foreach ($dephp_17 as & $dephp_40){
                            if ($dephp_40['skuId'] == $dephp_39){
                                $dephp_40['stock'] = $dephp_38['quantity'];
                                foreach ($dephp_38['priceUnits'] as $dephp_41){
                                    $dephp_40['marketprice'] = $dephp_41['price'];
                                }
                                $dephp_42 = array();
                                foreach ($dephp_40['option_specs'] as $dephp_43){
                                    foreach ($dephp_16 as $dephp_44){
                                        if ($dephp_44['propId'] == $dephp_43['propId']){
                                            foreach ($dephp_44['items'] as $dephp_45){
                                                if ($dephp_45['valueId'] == $dephp_43['valueId']){
                                                    $dephp_42[] = $dephp_45['title'];
                                                }
                                            }
                                        }
                                    }
                                }
                                $dephp_40['title'] = $dephp_42;
                            }
                        }
                        unset($dephp_40);
                    }
                }
            }else{
                $dephp_46 = 0;
                foreach ($dephp_35['priceUnits'] as $dephp_41){
                    $dephp_46 = $dephp_41['price'];
                }
                $dephp_12['marketprice'] = $dephp_46;
            }


            $dephp_12['options'] = $dephp_17;
            $dephp_12['content'] = array();
            $dephp_6 = $this -> get_detail_url($dephp_0);
            load() -> func('communication');
            $dephp_7 = ihttp_get($dephp_6);
            $dephp_12['content'] = $dephp_7;


            return $this -> save_goods($dephp_12, $dephp_1);
        }
        function save_goods($dephp_12 = array(), $dephp_1 = ''){
            global $_W;
            $dephp_47 = p('qiniu');
            $dephp_48 = $dephp_47 ? $dephp_47 -> getConfig() : false;
            $dephp_10 = array('uniacid' => $_W['uniacid'], 'taobaoid' => $dephp_12['itemId'], 'taobaourl' => $dephp_1, 'title' => $dephp_12['title'], 'total' => $dephp_12['total'], 'marketprice' => $dephp_12['marketprice'], 'pcate' => $dephp_12['pcate'], 'ccate' => $dephp_12['ccate'], 'tcate' => $dephp_12['tcate'], 'sales' => $dephp_12['sales'], 'createtime' => time(), 'updatetime' => time(), 'hasoption' => count($dephp_12['options']) > 0 ? 1 : 0, 'status' => 0, 'deleted' => 0, 'buylevels' => '', 'showlevels' => '', 'buygroups' => '', 'showgroups' => '', 'noticeopenid' => '', 'storeids' => '');
 
            if (p('supplier')){
            $roleid = pdo_fetch  ("select id from " . tablename('sz_yi_perm_role') . " where status1=1 and uniacid = {$_W['uniacid']} limit 1 ");  
             $dephp_49 = pdo_fetch('select * from ' . tablename('sz_yi_perm_user') . " where uniacid={$_W['uniacid']} and uid={$_W['uid']} and roleid= '{$roleid['id']}' limit 1 ");
 

                if (empty($dephp_49)){
                    $dephp_10['supplier_uid'] = 0;
                }else{
                    $dephp_10['supplier_uid'] = $_W['uid'];
                }
            }

            $dephp_50 = array();
            $dephp_51 = $dephp_12['pics'];
            $dephp_52 = count($dephp_51);
            if ($dephp_52 > 0){
                $dephp_10['thumb'] = $this -> save_image($dephp_51[0], $dephp_48);
                if ($dephp_52 > 1){
                    for ($dephp_53 = 1; $dephp_53 < $dephp_52; $dephp_53++){
                        $dephp_54 = $this -> save_image($dephp_51[$dephp_53], $dephp_48);
                        $dephp_50[] = $dephp_54;
                    }
                }
            }
            $dephp_10['thumb_url'] = serialize($dephp_50);
            $dephp_55 = pdo_fetch('select * from ' . tablename('sz_yi_goods') . ' where  taobaoid=:taobaoid and uniacid=:uniacid', array(':taobaoid' => $dephp_12['itemId'], ':uniacid' => $_W['uniacid']));
            if (empty($dephp_55)){
                pdo_insert('sz_yi_goods', $dephp_10);
                $dephp_56 = pdo_insertid();
            }else{
                $dephp_56 = $dephp_55['id'];
                unset($dephp_10['createtime']);
                pdo_update('sz_yi_goods', $dephp_10, array('id' => $dephp_56));
            }
            $dephp_57 = pdo_fetchall('select * from ' . tablename('sz_yi_goods_param') . ' where goodsid=:goodsid ', array(':goodsid' => $dephp_56));
            $dephp_13 = $dephp_12['params'];
            $dephp_58 = array();
            $dephp_59 = 0;
            foreach ($dephp_13 as $dephp_41){
                $dephp_60 = pdo_fetch('select * from ' . tablename('sz_yi_goods_param') . ' where goodsid=:goodsid and title=:title limit 1', array(':goodsid' => $dephp_56, ':title' => $dephp_41['title']));
                $dephp_61 = 0;
                $dephp_62 = array('uniacid' => $_W['uniacid'], 'goodsid' => $dephp_56, 'title' => $dephp_41['title'], 'value' => $dephp_41['value'], 'displayorder' => $dephp_59);
                if (empty($dephp_60)){
                    pdo_insert('sz_yi_goods_param', $dephp_62);
                    $dephp_61 = pdo_insertid();
                }else{
                    pdo_update('sz_yi_goods_param', $dephp_62, array('id' => $dephp_60['id']));
                    $dephp_61 = $dephp_60['id'];
                }
                $dephp_58[] = $dephp_61;
                $dephp_59++;
            }
            if (count($dephp_58) > 0){
                pdo_query('delete from ' . tablename('sz_yi_goods_param') . ' where goodsid=:goodsid and id not in (' . implode(',', $dephp_58) . ')', array(':goodsid' => $dephp_56));
            }else{
                pdo_query('delete from ' . tablename('sz_yi_goods_param') . ' where goodsid=:goodsid ', array(':goodsid' => $dephp_56));
            }
            $dephp_16 = $dephp_12['specs'];
            $dephp_63 = array();
            $dephp_59 = 0;
            $dephp_64 = array();
            foreach ($dephp_16 as $dephp_23){
                $dephp_65 = pdo_fetch('select * from ' . tablename('sz_yi_goods_spec') . ' where goodsid=:goodsid and propId=:propId limit 1', array(':goodsid' => $dephp_56, ':propId' => $dephp_23['propId']));
                $dephp_66 = 0;
                $dephp_67 = array('uniacid' => $_W['uniacid'], 'goodsid' => $dephp_56, 'title' => $dephp_23['title'], 'displayorder' => $dephp_59, 'propId' => $dephp_23['propId']);
                if (empty($dephp_65)){
                    pdo_insert('sz_yi_goods_spec', $dephp_67);
                    $dephp_66 = pdo_insertid();
                }else{
                    pdo_update('sz_yi_goods_spec', $dephp_67, array('id' => $dephp_65['id']));
                    $dephp_66 = $dephp_65['id'];
                }
                $dephp_67['id'] = $dephp_66;
                $dephp_63[] = $dephp_66;
                $dephp_59++;
                $dephp_21 = $dephp_23['items'];
                $dephp_68 = array();
                $dephp_69 = 0;
                $dephp_70 = array();
                foreach ($dephp_21 as $dephp_22){
                    $dephp_62 = array('uniacid' => $_W['uniacid'], 'specid' => $dephp_66, 'title' => $dephp_22['title'], 'thumb' => $this -> save_image($dephp_22['thumb'], $dephp_48), 'valueId' => $dephp_22['valueId'], 'show' => 1, 'displayorder' => $dephp_69);
                    $dephp_71 = pdo_fetch('select * from ' . tablename('sz_yi_goods_spec_item') . ' where specid=:specid and valueId=:valueId limit 1', array(':specid' => $dephp_66, ':valueId' => $dephp_22['valueId']));
                    $dephp_72 = 0;
                    if (empty($dephp_71)){
                        pdo_insert('sz_yi_goods_spec_item', $dephp_62);
                        $dephp_72 = pdo_insertid();
                    }else{
                        pdo_update('sz_yi_goods_spec_item', $dephp_62, array('id' => $dephp_71['id']));
                        $dephp_72 = $dephp_71['id'];
                    }
                    $dephp_69++;
                    $dephp_68[] = $dephp_72;
                    $dephp_62['id'] = $dephp_72;
                    $dephp_70[] = $dephp_62;
                }
                $dephp_67['items'] = $dephp_70;
                $dephp_64[] = $dephp_67;
                if (count($dephp_68) > 0){
                    pdo_query('delete from ' . tablename('sz_yi_goods_spec_item') . ' where specid=:specid and id not in (' . implode(',', $dephp_68) . ')', array(':specid' => $dephp_66));
                }else{
                    pdo_query('delete from ' . tablename('sz_yi_goods_spec_item') . ' where specid=:specid ', array(':specid' => $dephp_66));
                }
                pdo_update('sz_yi_goods_spec', array('content' => serialize($dephp_68)), array('id' => $dephp_65['id']));
            }
            if (count($dephp_63) > 0){
                pdo_query('delete from ' . tablename('sz_yi_goods_spec') . ' where goodsid=:goodsid and id not in (' . implode(',', $dephp_63) . ')', array(':goodsid' => $dephp_56));
            }else{
                pdo_query('delete from ' . tablename('sz_yi_goods_spec') . ' where goodsid=:goodsid ', array(':goodsid' => $dephp_56));
            }
            $dephp_73 = 0;
            $dephp_17 = $dephp_12['options'];
            if (count($dephp_17) > 0){
                $dephp_73 = $dephp_17[0]['marketprice'];
            }
            $dephp_74 = array();
            $dephp_59 = 0;
            foreach ($dephp_17 as $dephp_40){
                $dephp_27 = $dephp_40['option_specs'];
                $dephp_75 = array();
                $dephp_76 = array();
                foreach ($dephp_27 as $dephp_77){
                    foreach ($dephp_64 as $dephp_78){
                        foreach ($dephp_78['items'] as $dephp_79){
                            if ($dephp_79['valueId'] == $dephp_77['valueId']){
                                $dephp_75[] = $dephp_79['id'];
                                $dephp_76[] = $dephp_79['valueId'];
                            }
                        }
                    }
                }
                $dephp_75 = implode('_', $dephp_75);
                $dephp_76 = implode('_', $dephp_76);
                $dephp_80 = array('uniacid' => $_W['uniacid'], 'displayorder' => $dephp_59, 'goodsid' => $dephp_56, 'title' => implode('+', $dephp_40['title']), 'specs' => $dephp_75, 'stock' => $dephp_40['stock'], 'marketprice' => $dephp_40['marketprice'], 'skuId' => $dephp_40['skuId']);
                if ($dephp_73 > $dephp_40['marketprice']){
                    $dephp_73 = $dephp_40['marketprice'];
                }
                $dephp_81 = pdo_fetch('select * from ' . tablename('sz_yi_goods_option') . ' where goodsid=:goodsid and skuId=:skuId limit 1', array(':goodsid' => $dephp_56, ':skuId' => $dephp_40['skuId']));
                $dephp_82 = 0;
                if (empty($dephp_81)){
                    pdo_insert('sz_yi_goods_option', $dephp_80);
                    $dephp_82 = pdo_insertid();
                }else{
                    pdo_update('sz_yi_goods_option', $dephp_80, array('id' => $dephp_81['id']));
                    $dephp_82 = $dephp_81['id'];
                }
                $dephp_59++;
                $dephp_74[] = $dephp_82;
            }
            if (count($dephp_74) > 0){
                pdo_query('delete from ' . tablename('sz_yi_goods_option') . ' where goodsid=:goodsid and id not in (' . implode(',', $dephp_74) . ')', array(':goodsid' => $dephp_56));
            }else{
                pdo_query('delete from ' . tablename('sz_yi_goods_option') . ' where goodsid=:goodsid ', array(':goodsid' => $dephp_56));
            }
            $dephp_7 = $dephp_12['content'];
            $dephp_8 = $dephp_7['content'];
            preg_match_all('/<img.*?src=[\\\'| "](.*?(?:[\\.gif|\\.jpg]?))[\\\'|"].*?[\\/]?>/', $dephp_8, $dephp_83);
            if (isset($dephp_83[1])){
                foreach ($dephp_83[1] as $dephp_54){
                    $dephp_84 = $dephp_54;
                    if (substr($dephp_84, 0, 2) == '//'){
                        $dephp_54 = 'http://' . substr($dephp_54, 2);
                    }
                    $dephp_85 = array('taobao' => $dephp_84, 'system' => $this -> save_image($dephp_54, $dephp_48));
                    if (!strexists($dephp_85['system'], 'http://') && !strexists($dephp_85['system'], 'https://')){
                        $dephp_85['system'] = $_W['attachurl'] . $dephp_85['system'];
                    }
                    $dephp_86[] = $dephp_85;
                }
            }
            preg_match('/tfsContent : \'(.*)\'/', $dephp_8, $dephp_87);
            $dephp_87 = iconv('GBK', 'UTF-8', $dephp_87[1]);
            if (isset($dephp_86)){
                foreach ($dephp_86 as $dephp_54){
                    $dephp_87 = str_replace($dephp_54['taobao'], $dephp_54['system'], $dephp_87);
                }
            }
            $dephp_88 = 0;
            if (count($dephp_17) > 0){
                $dephp_88 = 1;
            }
            $dephp_62 = array('content' => $dephp_87, 'hasoption' => $dephp_88);
            if ($dephp_73 > 0){
                $dephp_62['marketprice'] = $dephp_73;
            }
            pdo_update('sz_yi_goods', $dephp_62, array('id' => $dephp_56));
            return array('result' => '1', 'goodsid' => $dephp_56);
        }
        function save_image($dephp_6 = '', $dephp_48){
            global $_W;
            if ($dephp_48){
                return p('qiniu') -> save($dephp_6, $dephp_48);
            }
            return $this -> saveToLocal($dephp_6);
        }
        function get_info_url($dephp_0){
            return 'http://hws.m.taobao.com/cache/wdetail/5.0/?id=' . $dephp_0;
        }
        function get_detail_url($dephp_0){
            return 'http://hws.m.taobao.com/cache/wdesc/5.0/?id=' . $dephp_0;
        }
        function check_remote_file_exists($dephp_6){
            $dephp_89 = curl_init($dephp_6);
            curl_setopt($dephp_89, CURLOPT_NOBODY, true);
            $dephp_90 = curl_exec($dephp_89);
            $dephp_91 = false;
            if ($dephp_90 !== false){
                $dephp_92 = curl_getinfo($dephp_89, CURLINFO_HTTP_CODE);
                if ($dephp_92 == 200){
                    $dephp_91 = true;
                }
            }
            curl_close($dephp_89);
            return $dephp_91;
        }
        function saveToLocal($dephp_6){
            global $_W;
            if (empty($dephp_6)){
                return '';
            }
            if (!$this -> check_remote_file_exists($dephp_6)){
                return "";
            }
            $dephp_93 = strrchr($dephp_6, '.');
            if ($dephp_93 != '.jpeg' && $dephp_93 != '.gif' && $dephp_93 != '.jpg' && $dephp_93 != '.png'){
                return '';
            }
            $dephp_94 = $_W['config']['upload']['attachdir'] . '/';
            $dephp_95 = 'images/sz_yi/' . $_W['uniacid'] . '/' . date('Y') . '/' . date('m') . '/';
            load() -> func('file');
            mkdirs(IA_ROOT . '/' . $dephp_94 . $dephp_95);
            do{
                $dephp_96 = random(30) . $dephp_93;
            } while (file_exists(IA_ROOT . '/' . $dephp_94 . $dephp_95 . '/' . $dephp_96));
            $dephp_95 .= $dephp_96;
            $dephp_97 = array('http' => array('method' => 'GET', 'timeout' => 7200));
            $dephp_10 = file_get_contents($dephp_6, false, stream_context_create($dephp_97));
            $dephp_98 = @fopen(IA_ROOT . '/' . $dephp_94 . $dephp_95, 'w');
            fwrite($dephp_98, $dephp_10);
            fclose($dephp_98);
            load() -> func('file');
            $dephp_99 = file_remote_upload($dephp_95);
            if ($dephp_99 == true){
                file_delete($dephp_95);
            }
            return $dephp_95;
        }
        function get_pageno_url($dephp_6 = '', $dephp_100 = 1){
            $dephp_6 .= '/search.htm?pageNo=' . $dephp_100;
            return $dephp_6;
        }
        function get_total_page($dephp_6 = '', $dephp_101 = false){
            if (empty($dephp_6)){
                return array('totalpage' => 0);
            }
            $dephp_8 = $this -> get_page_content($dephp_6);
            die($dephp_8);
            $dephp_102 = "";
            if ($dephp_101){
                $dephp_102 = '/<span class="page-info">(.*)<\\/span>/';
            }else{
                $dephp_102 = '/<b class="ui-page-s-len">(.*)<\\/b>/';
            }
            preg_match($dephp_102, $dephp_8, $dephp_41);
            if (is_array($dephp_41)){
                $dephp_103 = explode('/', $dephp_41[1]);
                return array('totalpage' => $dephp_103[1]);
            }
            return array('totalpage' => 0);
        }
        function httpGet($dephp_6){
            $dephp_89 = curl_init();
            curl_setopt($dephp_89, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($dephp_89, CURLOPT_TIMEOUT, 500);
            curl_setopt($dephp_89, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($dephp_89, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($dephp_89, CURLOPT_URL, $dephp_6);
            $dephp_104 = curl_exec($dephp_89);
            curl_close($dephp_89);
            return $dephp_104;
        }
        function get_page_content($dephp_6 = '', $dephp_100 = 1){
            if (empty($dephp_6)){
                return array('totalpage' => 0);
            }
            $dephp_6 = $this -> get_pageno_url($dephp_6, $dephp_100);
            load() -> func('communication');
            $dephp_7 = ihttp_get($dephp_6);
            if (!isset($dephp_7['content'])){
                return array('result' => 0);
            }
            return $dephp_7['content'];
        }
        function getRealURL($dephp_6){
            if (function_exists('stream_context_set_default')){
                stream_context_set_default(array('http' => array('method' => 'HEAD')));
            }
            $dephp_105 = get_headers($dephp_6, 1);
            if (strpos($dephp_105[0], '301') || strpos($dephp_105[0], '302')){
                if (is_array($dephp_105['Location'])){
                    return $dephp_105['Location'][count($dephp_105['Location']) - 1];
                }else{
                    return $dephp_105['Location'];
                }
            }else{
                return $dephp_6;
            }
        }
        function get_pag_items($dephp_106 = ''){
            $dephp_102 = '/data-id="(.*)"/U';
            preg_match_all($dephp_102, $dephp_106, $dephp_107);
            if (isset($dephp_107[1])){
                return $dephp_107[1];
            }
            return array();
        }
        function perms(){
            return array('taobao' => array('text' => $this -> getName(), 'isplugin' => true, 'fetch' => '抓取宝贝-log'));
        }
    }
}
