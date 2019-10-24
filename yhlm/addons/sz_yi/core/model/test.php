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

class Sz_DYi_Test{

	
	public function calcReward($openid , $type, $actid, $money, $logid)
		{			//打赏 支付方式只有微信
                    //type 1 活动 2 文章

			global $_W;
			$set=m('tools')->getSet(); 			 	 
            $account=m('member')->getMember($set['bart']['account']);
            $bonus_set=$set['bartact'];
			$residue=1;
			$consumersR=floatval(floatval($bonus_set['reward_author'])/100);        //发布人
			$agentR=floatval(floatval($bonus_set['reward_agent1'])/100);             //上级
			$agent2R=floatval(floatval($bonus_set['reward_agent2'])/100);            //上上级
			$provinceR=floatval(floatval($bonus_set['reward_commission1'])/100);          //省级代理 	
			$cityR=floatval(floatval($bonus_set['reward_commission2'])/100);              //市级代理
			$areaR=floatval(floatval($bonus_set['reward_commission3'])/100);              //区域代理
			$boosR=floatval(floatval($bonus_set['reward_boos'])/100);             //平台	 			 	
			$rdpeople=m('member')->getMember($openid);
			$act=m('activity')->getact($actid);
			$muser=m('activity')->getMuser($act['openid']);	 	 	
			$member=m('member')->getMember($act['openid']);	 			 	
			$tmoney=floatval($consumersR * $money);	 	 	
			$residue-=$consumersR; 	
			m('member')->setCredit($act['openid'],'credit2',$tmoney);	 	 	//给作者分成
			m('log')->putActivityBonusLog($member['openid'],$openid,$actid,$tmoney,2,$type,0,$logid,2);
				
			$addr=array(        //按照供应商的地区进行区域代理的分红 		 
            	'province'=>$muser['province'],	 
                'city'=>$muser['city'],	 	 
                'area'=>$muser['area']	 	 	 
            );

			// 1级	 	 
			$agent=m('member')->getMember($rdpeople['agentid'])?:array();
			if (!empty($agent)) {
				$tmoney=floatval($agentR * $money);
				$residue-=$agentR;
				m('member')->setCredit($agent['openid'],'credit2',$tmoney);
				m('log')->putActivityBonusLog($agent['openid'],$openid,$actid,$tmoney,2,$type,1,$logid,2);
			}

			// 2级
			$agent2=m('member')->getMember($agent['agentid'])?:array();
			if (!empty($agent2)) {
				$tmoney=floatval($agent2R * $money);
				$residue-=$agent2R;
				m('member')->setCredit($agent2['openid'],'credit2',$tmoney);
				m('log')->putActivityBonusLog($agent2['openid'],$openid,$actid,$tmoney,2,$type,2,$logid,2);
			}





			if (true) {     //区域代理商分红 

                if(!empty($areaR)){          // 区域代理分红不为空 
                    $areainfo = pdo_fetch('select id,openid,bonus_area_commission from ' . tablename('sz_yi_member') . ' where bonus_province=\'' . $addr['province'] . '\' and bonus_city=\'' . $addr['city'] . '\' and bonus_district=\'' . $addr['area'] . '\' and bonus_area=3 and uniacid=' . $_W['uniacid']);   
                        //查找一个区域代理 
                        //默认比例 或 区域代理 
                    if(!empty($areainfo)){
                        $tmoney = sprintf('%.3f',$areaR) * $money; 
                        $residue-=$areaR;
                        m('member')->setCredit($areainfo['openid'],'credit2',$tmoney);
						m('log')->putActivityBonusLog($areainfo['openid'],$openid,$actid,$tmoney,2,$type,3,$logid,2);
                        // write log
                    }
                }
                //市级代理
                if(!empty($cityR)){
                    //一个区域只有一个代理 ? 
                    $cityinfo = pdo_fetch('select id,openid,bonus_area_commission from ' . tablename('sz_yi_member') . ' where bonus_province=\'' . $addr['province'] . '\' and bonus_city=\'' . $addr['city'] . '\' and bonus_area=2 and uniacid=' . $_W['uniacid']);
                    if(!empty($cityinfo)){
                        //具体金额
                        $tmoney = sprintf('%.3f',$cityR) * $money;
                        $residue-=$cityR;
                        m('member')->setCredit($cityinfo['openid'],'credit2',$tmoney);
						m('log')->putActivityBonusLog($cityinfo['openid'],$openid,$actid,$tmoney,2,$type,4,$logid,2);
                        //记录日志
                    }
                } 

                //省级代理       
                if(!empty($provinceR)){
                    $provinceinfo = pdo_fetch('select id,openid,bonus_area_commission from ' . tablename('sz_yi_member') . ' where bonus_province=\'' . $addr['province'] . '\' and bonus_area=1 and uniacid=' . $_W['uniacid']);
                    if(!empty($provinceinfo)){
                        $tmoney = sprintf('%.3f',$provinceR) * $money;
                        $residue-=$provinceR;
                        m('member')->setCredit($provinceinfo['openid'],'credit2',$tmoney);
						m('log')->putActivityBonusLog($provinceinfo['openid'],$openid,$actid,$tmoney,2,$type,5,$logid,2);
                    } 		
                }

                if ($account) {
                        //平台  
                        if ($residue > $boosR) {
                            $boosR=$residue;
                        }                      
                        $tmoney = sprintf('%.3f',$boosR) * $money;
                        m('member')->setCredit($account['openid'],'credit2',$tmoney);             //打入余额
						m('log')->putActivityBonusLog($account['openid'],$openid,$actid,$tmoney,2,$type,6,$logid,2);
                        //write log(arg)	 	 	 		 
                }

            }

		}


}

