<?php
if (!(defined('IN_IA')))
{
    exit('Access Denied');
}
class Kuaidi_EweiShopV2Model
{

    function get_wuliu_info($wuliu,$wuliu_num){
        $res = file_get_contents('http://wap.kuaidi100.com/wap_result.jsp?rand=20120517&id='.$wuliu.'&fromWeb=null&&postid='.$wuliu_num);

//        $url = "http://wap.kuaidi100.com/wap_result.jsp?rand=20120517&id='.$wuliu.'&fromWeb=null&&postid='.$wuliu_num";
//        $res = curl_init();
        $res = preg_replace("/(\r | \n)*/m",'',$res);//去掉换行符
        if (preg_match("/单号不正确/m",$res)) {
            $return['code'] = 400;
            $return['res'] = '单号不正确';
        }else if (preg_match("/暂无物流信息/m",$res)) {
            $return['code'] = 410;
            $return['res'] = '此单号暂无物流信息，请稍后再查';
        }else{
            preg_match("/querybutton\".*class=\"clear\"\>\<\/div\>(.*)\<\/form\>/is",$res,$r);
            $w = $r[1];
            $y = array("/\<p\>/m","/\<\/p\>/m","/\<br \/\>/m","/·/m","/\<span\>\<strong\>查询结果如下所示：\<\/strong\>\<\/span\>/m");
            $r = array("<span>","</span>",'','');
            $w = preg_replace($y,$r,$w);//p标签换成span，去掉br标签
            $return = '<div class=\'Tip-courier\'>'.$w.'</div>';
            $return = str_replace("</span>","</span>|",$return);
            $return = str_replace(" ","",str_replace("\r\n","",$return));
            $return = explode('|',$return);
            array_pop($return);
            $wuliu = array();
            $wuliu['place']=$return;
            $yundan = array_shift($return);
            $wuliu['yundan'] = $yundan;
        }
        print_r($wuliu);exit;
        return $wuliu;
    }


}
?>