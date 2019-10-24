<?php

/*=============================================================================

#     FileName: goods.php

#         Desc: ��Ʒ��

#       Author: Yunzhong - http://www.yunzshop.com

#        Email: 1084070868@qq.com

#     HomePage: http://www.yunzshop.com

#      Version: 0.0.1

#   LastChange: 2016-02-05 02:32:56

#      History:

=============================================================================*/

if (!defined('IN_IA')) {

    exit('Access Denied');

}

class Sz_DYi_Goods

{

    public function getOption($goodsid = 0, $optionid = 0)

    {

        global $_W;

        return pdo_fetch('select * from ' . tablename('sz_yi_goods_option') . ' where id=:id and goodsid=:goodsid and uniacid=:uniacid', array(

            ':id' => $optionid,

            ':uniacid' => $_W['uniacid'],

            ':goodsid' => $goodsid

        ));

    }



    public function getOptions($goodsid = 0)

    {

        global $_W;

        return pdo_fetchall('select * from ' . tablename('sz_yi_goods_option') . ' where goodsid=:goodsid and uniacid=:uniacid', array(

            ':uniacid' => $_W['uniacid'],

            ':goodsid' => $goodsid

        ));

    }


    public function getList($args = array(),$group = true)

    {

        global $_W;
        // var_dump($args);
        // die;
        $page      = !empty($args['page']) ? intval($args['page']) : 1;

        $pagesize  = !empty($args['pagesize']) ? intval($args['pagesize']) : 10;

        $random    = !empty($args['random']) ? $args['random'] : false;

        $order     = !empty($args['order']) ? $args['order'] : ' displayorder desc,createtime desc';

        $orderby   = !empty($args['by']) ? $args['by'] : '';

        $ids       = !empty($args['ids']) ? trim($args['ids']) : '';

        $sup_uid   = !empty($args['supplier_uid']) ? trim($args['supplier_uid']) : '';

        $condition = ' and `uniacid` = :uniacid AND `deleted` = 0 and status=1';

        $params    = array(

            ':uniacid' => $_W['uniacid']

        );

        if (!empty($args['extension'])){
            $condition.=" ".$args['extension']." ";
        }

        if (!empty($ids)) {

            $condition .= " and id in ( " . $ids . ")";

        }

        if (!empty($sup_uid)) {

            $condition .= " and supplier_uid = :supplier_uid ";

            $params[':supplier_uid'] = intval($sup_uid);

        }

        $isnew = !empty($args['isnew']) ? 1 : 0;

        if (!empty($isnew)) {

            $condition .= " and isnew=1";

        }

        $ishot = !empty($args['ishot']) ? 1 : 0;

        if (!empty($ishot)) {

            $condition .= " and ishot=1";

        }

        $isrecommand = !empty($args['isrecommand']) ? 1 : 0;

        if (!empty($isrecommand)) {

            $condition .= " and isrecommand=1";

        }

        $isdiscount = !empty($args['isdiscount']) ? 1 : 0;

        if (!empty($isdiscount)) {

            $condition .= " and isdiscount=1";

        }

        $istime = !empty($args['istime']) ? 1 : 0;

        if (!empty($istime)) {

            $condition .= " and istime=1 and " . time() . ">=timestart and " . time() . "<=timeend";

        }

        $keywords = !empty($args['keywords']) ? $args['keywords'] : '';

        if (!empty($keywords)) {

            $condition .= ' AND `title` LIKE :title';

            $params[':title'] = '%' . trim($keywords) . '%';

        }

        $tcate = intval($args['tcate']);

        if (!empty($tcate)) {

            $condition .= " AND ( `tcate` = :tcate or  FIND_IN_SET({$tcate},tcates)<>0 )";

            $params[':tcate'] = intval($tcate);

        } else {

            $ccate = intval($args['ccate']);

            if (!empty($ccate)) {

                $condition .= " AND ( `ccate` = :ccate or  FIND_IN_SET({$ccate},ccates)<>0 )";

                $params[':ccate'] = intval($ccate);

            } else {

                $pcate = intval($args['pcate']);

                if (!empty($pcate)) {

                    $condition .= " AND ( `pcate` = :pcate or  FIND_IN_SET({$pcate},pcates)<>0 )";

                    $params[':pcate'] = intval($pcate);

                }

            }

        }

        // �۸�ɸѡ

        $price1 = !empty($args['price1']) ? intval($args['price1']) : 0;

        $price2 = !empty($args['price2']) ? intval($args['price2']) : 0;

        if (!empty($price1) && !empty($price2)){

            $condition .= ' AND `marketprice` >= :price1 AND `marketprice` <= :price2';

            $params[':price1'] = intval($price1);

            $params[':price2'] = intval($price2);

        }

        // �۸�ɸѡ

        // ����ɸѡ

        $city = !empty($args['city']) ? $args['city'] : '';

        if (!empty($city)) {

            $condition .= ' AND `city` LIKE :city';

            $params[':city'] = '%' . trim($city) . '%';

        }

        // ����ɸѡ

        /*if($group){

                $openid  = m('user')->getOpenid();

                $member  = m('member')->getMember($openid);

                $levelid = intval($member['level']);

                $groupid = intval($member['groupid']);

                $condition .= " and ( ifnull(showlevels,'')='' or FIND_IN_SET( {$levelid},showlevels)<>0 ) ";

                $condition .= " and ( ifnull(showgroups,'')='' or FIND_IN_SET( {$groupid},showgroups)<>0 ) ";

        }*/



        if (!$random) {

            $sql = "SELECT showgroups,PostFlag,LocalFlag,showlevels,id,title,thumb,marketprice,productprice,sales,total,description,unit,type,city,province,district FROM " . tablename('sz_yi_goods') . " where 1 {$condition} ORDER BY {$order} {$orderby} LIMIT " . ($page - 1) * $pagesize . ',' . $pagesize;

        } else {

            $sql = "SELECT showgroups,showlevels,PostFlag,LocalFlag,id,title,thumb,marketprice,productprice,sales,total,description,unit,type,city,province,district FROM " . tablename('sz_yi_goods') . " where 1 {$condition} ORDER BY rand() LIMIT " . $pagesize;
            // $sql = "SELECT showgroups,showlevels,id,title,thumb,marketprice,productprice,sales,total,description,unit,type FROM " . tablename('sz_yi_goods') . " where 1 {$condition} ORDER BY rand() LIMIT " . ($page - 1) * $pagesize . ',' . $pagesize;

        }
        // var_dump($sql);
        // die;
        // print_r($group);

        $list = pdo_fetchall($sql, $params);

        $list = set_medias($list, 'thumb');

        return $list;

    }


    public function getDealList($args = array(),$group = true)

    {

        global $_W,$_GPC;
        $page      = !empty($args['page']) ? intval($args['page']) : 1;

        $pagesize  = !empty($args['pagesize']) ? intval($args['pagesize']) : 10;

        $random    = !empty($args['random']) ? $args['random'] : false;

        if ($args['order'] == 'marketprice') {
            $order = ' op.marketprice ';
        }else{
            $order     = !empty($args['order']) ?'g.'.$args['order'] : ' g.displayorder desc,g.createtime desc';
        }                                               

        $orderby   = !empty($args['by']) ? $args['by'] : '';                 

        $ids       = !empty($args['ids']) ? trim($args['ids']) : '';

        $sup_uid   = !empty($args['supplier_uid']) ? trim($args['supplier_uid']) : '';

        $condition = ' and g.uniacid = :uniacid AND g.deleted = 0 and g.status=1';

        $params    = array(     

            ':uniacid' => $_W['uniacid']

        );

        if (!empty($args['extension'])){
            $condition.=" ".$args['extension']." ";
        }

        if (!empty($ids)) {

            $condition .= " and g.id in ( " . $ids . ")";

        }

        if (!empty($sup_uid)) {

            $condition .= " and g.supplier_uid = :supplier_uid ";

            $params[':supplier_uid'] = intval($sup_uid);

        }

        $isnew = !empty($args['isnew']) ? 1 : 0;

        if (!empty($isnew)) {

            $condition .= " and g.isnew=1";

        }

        $ishot = !empty($args['ishot']) ? 1 : 0;

        if (!empty($ishot)) {

            $condition .= " and g.ishot=1";

        }

        $isrecommand = !empty($args['isrecommand']) ? 1 : 0;

        if (!empty($isrecommand)) {

            $condition .= " and g.isrecommand=1";

        }

        $isdiscount = !empty($args['isdiscount']) ? 1 : 0;

        if (!empty($isdiscount)) {

            $condition .= " and g.isdiscount=1";

        }

        $istime = !empty($args['g.istime']) ? 1 : 0;

        if (!empty($istime)) {

            $condition .= " and g.istime=1 and " . time() . ">=g.timestart and " . time() . "<=g.timeend";

        }

        $keywords = !empty($args['keywords']) ? $args['keywords'] : '';

        if (!empty($keywords)) {        

            $condition .= ' AND g.title LIKE :title';

            $params[':title'] = '%' . trim($keywords) . '%';

        }

        $tcate = intval($args['tcate']);

        if (!empty($tcate)) {

            $condition .= " AND ( g.tcate = :tcate or  FIND_IN_SET({$tcate},g.tcates)<>0 )";

            $params[':tcate'] = intval($tcate);

        } else {

            $ccate = intval($args['ccate']);

            if (!empty($ccate)) {

                $condition .= " AND ( g.ccate = :ccate or  FIND_IN_SET({$ccate},g.ccates)<>0 )";

                $params[':ccate'] = intval($ccate);

            } else {                 

                $pcate = intval($args['pcate']);

                if (!empty($pcate)) {

                    $condition .= " AND ( g.pcate = :pcate or  FIND_IN_SET({$pcate},g.pcates)<>0 )";

                    $params[':pcate'] = intval($pcate);

                }

            }

        }

      

        $price1 = !empty($args['price1']) ? intval($args['price1']) : 0;

        $price2 = !empty($args['price2']) ? intval($args['price2']) : 0;

        if (!empty($price1) && !empty($price2)){

            $condition .= ' AND op.marketprice >= :price1 AND `op.marketprice` <= :price2';

            $params[':price1'] = intval($price1);

            $params[':price2'] = intval($price2);

        }

   

        $city = !empty($args['city']) ? $args['city'] : '';

        if (!empty($city)) {

            $condition .= ' AND g.city LIKE :city';

            $params[':city'] = '%' . trim($city) . '%';

        }
                                
        if (!$random) {                  
            // $sql = "SELECT g.showgroups,g.showlevels,g.PostFlag,g.LocalFlag,g.id,g.title,g.thumb,g.productprice,g.sales,g.total,g.description,g.unit,g.type,max(op.marketprice) marketprice FROM " . tablename('sz_yi_goods') . " g left join ".tablename('sz_yi_goods_option')." op on g.id = op.goodsid where 1 {$condition} group by op.goodsid ORDER BY {$order} {$orderby} LIMIT " . ($page - 1) * $pagesize . ',' . $pagesize;
//            print_r($order);
//            print_r($orderby);exit;
            $sql = "SELECT g.showgroups,g.showlevels,g.PostFlag,g.LocalFlag,g.id,g.title,g.thumb,g.sales,g.total,g.description,g.unit,g.type,g.city,g.province,g.district,op.marketprice,op.productprice,op.costprice FROM " . tablename('sz_yi_goods') . " g left join (select goodsid,productprice,max(marketprice) marketprice,costprice from ".tablename('sz_yi_goods_option')." where uniacid = 8 group by goodsid) op on g.id = op.goodsid where 1 {$condition} ORDER BY {$order} {$orderby} LIMIT " . ($page - 1) * $pagesize . ',' . $pagesize;         
//            print_r($sql);exit;
        } else {                                                                                                                                                                                                                                          
            $sql = "SELECT g.showgroups,g.showlevels,g.PostFlag,g.LocalFlag,g.id,g.title,g.thumb,g.marketprice,g.productprice,g.sales,g.total,g.city,g.province,g.district,g.description,g.unit,g.type,max(op.marketprice) FROM " . tablename('sz_yi_goods') ." g left join ".tablename('sz_yi_goods_option')." op on op.goodsid=g.id where 1 {$condition} ORDER BY rand() LIMIT " . $pagesize;

        }

        $list = pdo_fetchall($sql, $params);
//        print_r($list);exit;
        $list = set_medias($list, 'thumb');

        return $list;

    }


    public function getComments($goodsid = '0', $args = array())

    {

        global $_W;

        $page      = !empty($args['page']) ? intval($args['page']) : 1;

        $pagesize  = !empty($args['pagesize']) ? intval($args['pagesize']) : 10;

        $condition = ' and `uniacid` = :uniacid AND `goodsid` = :goodsid and deleted=0';

        $params    = array(

            ':uniacid' => $_W['uniacid'],

            ':goodsid' => $goodsid

        );

        $sql       = "SELECT id,nickname,headimgurl,content,images FROM " . tablename('sz_yi_goods_comment') . " where 1 {$condition} ORDER BY createtime desc LIMIT " . ($page - 1) * $pagesize . ',' . $pagesize;

        $list      = pdo_fetchall($sql, $params);

        foreach ($list as &$row) {

            $row['images'] = set_medias(unserialize($row['images']));

        }

        unset($row);

        return $list;

    }



    public function tatol($args = array(),$group = true){



        global $_W;

        $page      = !empty($args['page']) ? intval($args['page']) : 1;

        $pagesize  = !empty($args['pagesize']) ? intval($args['pagesize']) : 10;

        $random    = !empty($args['random']) ? $args['random'] : false;

        $order     = !empty($args['order']) ? $args['order'] : ' displayorder desc,createtime desc';

        $orderby   = !empty($args['by']) ? $args['by'] : '';

        $ids       = !empty($args['ids']) ? trim($args['ids']) : '';

        $sup_uid   = !empty($args['supplier_uid']) ? trim($args['supplier_uid']) : '';

        $condition = ' and `uniacid` = :uniacid AND `deleted` = 0 and status=1';

        $params    = array(

            ':uniacid' => $_W['uniacid']

        );

        if (!empty($ids)) {

            $condition .= " and id in ( " . $ids . ")";

        }

        if (!empty($sup_uid)) {

            $condition .= " and supplier_uid = :supplier_uid ";

            $params[':supplier_uid'] = intval($sup_uid);

        }

        $isnew = !empty($args['isnew']) ? 1 : 0;

        if (!empty($isnew)) {

            $condition .= " and isnew=1";

        }

        $ishot = !empty($args['ishot']) ? 1 : 0;

        if (!empty($ishot)) {

            $condition .= " and ishot=1";

        }

        $isrecommand = !empty($args['isrecommand']) ? 1 : 0;

        if (!empty($isrecommand)) {

            $condition .= " and isrecommand=1";

        }

        $isdiscount = !empty($args['isdiscount']) ? 1 : 0;

        if (!empty($isdiscount)) {

            $condition .= " and isdiscount=1";

        }

        $istime = !empty($args['istime']) ? 1 : 0;

        if (!empty($istime)) {

            $condition .= " and istime=1 and " . time() . ">=timestart and " . time() . "<=timeend";

        }

        $keywords = !empty($args['keywords']) ? $args['keywords'] : '';

        if (!empty($keywords)) {

            $condition .= ' AND `title` LIKE :title';

            $params[':title'] = '%' . trim($keywords) . '%';

        }

        $tcate = intval($args['tcate']);

        if (!empty($tcate)) {

            $condition .= " AND ( `tcate` = :tcate or  FIND_IN_SET({$tcate},tcates)<>0 )";

            $params[':tcate'] = intval($tcate);

        } else {

            $ccate = intval($args['ccate']);

            if (!empty($ccate)) {

                $condition .= " AND ( `ccate` = :ccate or  FIND_IN_SET({$ccate},ccates)<>0 )";

                $params[':ccate'] = intval($ccate);

            } else {

                $pcate = intval($args['pcate']);

                if (!empty($pcate)) {

                    $condition .= " AND ( `pcate` = :pcate or  FIND_IN_SET({$pcate},pcates)<>0 )";

                    $params[':pcate'] = intval($pcate);

                }

            }

        }



        if($group){

                $openid  = m('user')->getOpenid();

                $member  = m('member')->getMember($openid);

                $levelid = intval($member['level']);

                $groupid = intval($member['groupid']);

                $condition .= " and ( ifnull(showlevels,'')='' or FIND_IN_SET( {$levelid},showlevels)<>0 ) ";

                $condition .= " and ( ifnull(showgroups,'')='' or FIND_IN_SET( {$groupid},showgroups)<>0 ) ";

        }



        if (!$random) {

            $sql = "SELECT count(1) FROM " . tablename('sz_yi_goods') . " where 1 {$condition}  " ;

        } else {

            $sql = "SELECT count(1) FROM " . tablename('sz_yi_goods') . " where 1 {$condition}  " ;

        }



        $tatol = pdo_fetchcolumn($sql, $params);

        return $tatol;



    }

}

