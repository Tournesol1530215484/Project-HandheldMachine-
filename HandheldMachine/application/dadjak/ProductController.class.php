<?php
namespace App\Controller;
use Think\Controller;
class ProductController extends BaseController {
    
	public function _initialize(){
		parent::_initialize();
}

 
    /*
     * 主页列表
     */
    public function getList(){
    	$mobile = $_SESSION['appUser'];
		$this->app_getRecord($mobile);
	//	  $mobile = 13662578579;
		    	if(empty($mobile)){
     		$this->_resp['code'] = '-1';
    		$this->_resp['result'] = "请登录!";
     		$this->output();
		  	}

    	$id_code = M("AppRegist")->where("mobile = '$mobile'")->getField("id_code");
    	$Dpc = M("DpcRegist")->where("owner_id = '$id_code' and owner_phone1='$mobile'  and cur_state=1")->field("id,rfid,rfid_area,name,photo1,type,alarm_setting,reader,reader_area,location_update_datetime,attr11,fk_date")->select();
    	$this->log("Dpc:".M("DpcRegist")->getLastSQL());
    	$applink = M("dpc_regist")
                                ->table("dpc_regist dr,app_link al")
    		       	//	->join("app_link al")
    				->where("al.rfid_mobile = '$mobile' and dr.rfid = al.rfid and dr.rfid_area = al.rfid_area and al.cur_state = 1 and dr.cur_state=1")
    				->field("dr.id,dr.rfid,dr.rfid_area,dr.name,dr.photo1,dr.type,dr.alarm_setting,dr.reader,dr.reader_area,dr.location_update_datetime")
    				->select();
//     	$this->log(M("dpc_regist dr")->getLastSQL());
    	
    	if(empty($Dpc) && empty($applink)){
    	$arr = array();
    	}else if(!empty($Dpc) && !empty($applink)){
    		$arr = $Dpc;    		
	    	$a1 = count($Dpc);
	    	$b1 = count($applink);	
	    	for ($i=0;$i<$b1;$i++){
	    		$arr[$a1+$i] = $applink[$i];   		
	    	}
    		
    	}else if(!empty($Dpc) && empty($applink)){
    		$arr = $Dpc;
    	}else if(empty($Dpc) && !empty($applink)){
    		$arr = $applink;
    	}
    	
    	    	
    	$list = array();
    	foreach ($arr as $k => $v){
    		$list[$k]['id'] = $v['id'];
    		$list[$k]['rfid'] = $v['rfid'];
    		$list[$k]['rfid_area'] = $v['rfid_area'];
    		$list[$k]['name'] = $v['name'];
//     		$list[$k]['photo'] = "http://test.zwtweb.win:82/uploadfile/photo/".$v['photo1'];
    		$list[$k]['photo'] = getPicPath($v['id'], "car").$v['photo1'];
    		$list[$k]['type'] = $v['type'];
    		$lb = M("AppLink")->where("rfid = '".$v['rfid']."' and rfid_area = '".$v['rfid_area']."' and mobile = '$mobile' and cur_state in(0,1)")->find();
    		$rb = M("AppLink")->where("rfid = '".$v['rfid']."' and rfid_area = '".$v['rfid_area']."' and rfid_mobile = '$mobile' and cur_state in(0,1)")->find();
    		$link = 0;
    		if(!empty($lb)){
    			$link = 1;
    		}
    		if(!empty($rb)){
    			$link = 2;
    		}
    		$list[$k]['link'] = $link;
    		$list[$k]['alarm_setting'] = $v['alarm_setting'];
    		$reader = $v['reader'];
    		$reader_area = $v['reader_area'];
		$location_id = M("dpc_reader")->where("reader = $reader and reader_area = $reader_area and cur_state=1")->getField("location_id");
		$location = M("dpc_location")->where("id ='$location_id' and cur_state=1 ")->find();
                 
                  if($v['rfid_area'] == 532503){
  				$list[$k]['last_area'] = $location['province'].$location['city'].$location['district'].$location['street'];//上次出现地址
			}
                  if($v['rfid_area'] != 532503 && $v['type']==1 && $location !=null){
				 $list[$k]['last_area'] = $location['province'].$location['city'].$location['district'].$location['street'].'_'.$v['rfid_area'];
			 }elseif($v['rfid_area'] != 532503 && $v['type']==1 && $location == null){
				 $list[$k]['last_area']="";
			 }
			if($v['rfid_area'] != 532503 && $v['type']!=1 ){
				 $list[$k]['last_area'] = $location['province'].$location['city'].$location['district'].$location['street'];
			 }

    		$list[$k]['last_time'] = $v['location_update_datetime'];
			$now = date("Y-m-d",time());
    		//是否可以报警，and owner_id = '$id_code' 
    		$count = M("DpcLostbike")->where("rfid = '".$v['rfid']."' and rfid_area = '".$v['rfid_area']."' ")->order("id desc")->find();
    		if(empty($count)){
    			$list[$k]['can_lostbike'] = 0;//0，无数据
    		}else{ 
    			if($count['cur_state'] == 0){
    				$list[$k]['can_lostbike'] = 1;//1，可以报警
    			}else if($count['cur_state'] == 1){
    				$list[$k]['can_lostbike'] = 2;//2，已报警
    			}   			
    					   			
    		}
//-------------------------------------------开始---------------------------------------------------------------------------
                	$a= "+" . $v['attr11'];//使用月数
			$create_date = $v['fk_date'];//创建时间
			//计算标签的到期时间
			$fk_time = date("Y-m-d", strtotime("$a month", strtotime($create_date)));

			//当前时间
			$date = date("Y-m-d", time());
			//体验用户
			if($v['attr11']>0 && $v['attr11']<=11){
				if(strtotime($fk_time)>strtotime($date)){//到期时间<当前时间=过期
					//剩余时间
					$cycle_date = strtotime($fk_time) - strtotime($date);
					$list[$k]['days']=$this->run_time($cycle_date);
					$list[$k]['dpc_type']=0;
				}else{
					$list[$k]['days']=0;
					$list[$k]['dpc_type']=0;
				}
			}
			//正式用户
			if($v['attr11']>=12){
				if(strtotime($fk_time)>strtotime($date)){//到期时间<当前时间=过期
					//剩余时间
					$cycle_date = strtotime($fk_time) - strtotime($date);
					$list[$k]['days']=$this->run_time($cycle_date);
					$list[$k]['dpc_type']=1;
				}else{
					$list[$k]['days']=0;
					$list[$k]['dpc_type']=1;
				}
			}
			//长期有效用户
			if($v['attr11']<=0){
				$list[$k]['days']=-1;
				$list[$k]['dpc_type']=1;
			}
                       
                        //指定充值地区
			$area_role=M('user_area_role')->where("rfid_start='".$v['rfid_area']."'")->find();//查询区域码所在的县，市，区。
			if($area_role){
				$area_rol=M('user_area_role')->where("district='".$area_role['city']."'")->find();//查询区域码所对应地级市的district,
				if($area_rol['city']==CITY_QY){
					$list[$k]['region'] = 'true';
				}else if($area_rol['city']==CITY_XN){
					$list[$k]['region'] = 'true';
				}else if($area_rol['city']==CITY_ZS){
					$list[$k]['region'] = 'true';
				}else if($v['rfid_area'==CITY_QXN]){//黔西南地区
					$list[$k]['region']='true';
				}	
				else{
					$list[$k]['region'] = 'false';
				}
			}else{
				$list[$k]['region'] = 'false';
			}
//------------------------------------------结束------------------------------------------------------------------------- 		
    	}
    	
    	$list = $this->array_sort($list, "can_lostbike","desc");
    	foreach ($list as $m => $n){
    		if($list[$m]['can_lostbike'] == 4){
    			$list[$m]['can_lostbike'] = 1;
    		}
    	}
    	
    	$this->_resp['result'] = array(
    			'car_list' => array_values($list)
    	);
    	$this->output();
    }


	//保险充值记录查询
	public  function insRecord(){
		$mobile =session('appUser');
		$this->app_getRecord($mobile);
		if(empty($mobile)){
			$this->_resp['code'] = '-1';
			$this->_resp['result'] = "请登录!";
			$this->output();
		}
		$id_code = M("app_regist")->where("mobile=$mobile")->find();
		$label   = M("dpc_regist")->where("owner_phone1=$mobile and owner_id='".$id_code['id_code']."' and cur_state=1 and attr11 !=0 and attr12 !=0 ")->select();

		$data_weipay=array();
		$data_pay=array();
		foreach($label as $keys=>$v){
			//支付宝充值记录
			$alipay = M("alipay_records")->where('rfid="'.$v['rfid'].'" and rfid_area="'.$v['rfid_area'].'" and  trade_status="TRADE_SUCCESS"')->select();
			foreach($alipay as $keys=>$val){
				$date_gmt=strtotime($val['gmt_payment']);
				$get_payment=date('Y-m-d',$date_gmt);
				$data_pay[$keys]['timeStamp']    =$val['gmt_payment']; //保险购买时间；
				$data_pay[$keys]['dpcNickName']  =$v['name'] ;         //被保险资产昵称；
				$data_pay[$keys]['policyHolders']=$v['owner_name'] ;   //投保人姓名；
				$data_pay[$keys]['insType']      =$val['type']."盗抢险";//保险类型----盗抢险
				$data_pay[$keys]['insPay']       =$val['total_amount']; //保险费用
				$data_pay[$keys]['reserve_1']    =$val['reserve_1'];    //保单号
				$data_pay[$keys]['insStartTS']   =$get_payment;         //保险起始时间
				$data_pay[$keys]['insEndTS']      =$v['attr12'] ;      //保险结束时间
				$data_pay[$keys]['rfid']          =$v['rfid'];
				$data_pay[$keys]['rfid_area']     =$v['rfid_area'];

			}

                        //微信充值记录
			$weipay = M("weipay_records")->where('rfid="'.$v['rfid'].'" and rfid_area="'.$v['rfid_area'].'" and  result_code="SUCCESS"')->select();
			foreach($weipay as $keys=>$val){
				$date=strtotime($val['time_end']) ;
				$date_time=date('Y-m-d H:i:s',$date);
				$date_TS  =date('Y-m-d',$date);
				$data_weipay[$keys]['timeStamp']    =$date_time;           //保险购买时间；
				$data_weipay[$keys]['dpcNickName']  =$v['name'] ;          //被保险资产昵称；
				$data_weipay[$keys]['policyHolders']=$v['owner_name'] ;    //投保人姓名；
				$data_weipay[$keys]['insType']      =$val['type']."盗抢险"; //保险类型----盗抢险
				$data_weipay[$keys]['insPay']       =($val['total_fee']/100)."";    //保险费用
                                $data_weipay[$keys]['reserve_1']    =$val['reserve_1'];    //保单号
				$data_weipay[$keys]['insStartTS']   = $date_TS;            //保险起始时间
				$data_weipay[$keys]['insEndTS']      =$v['attr12'] ;       //保险结束时间
				$data_weipay[$keys]['rfid']          =$v['rfid'];
				$data_weipay[$keys]['rfid_area']     =$v['rfid_area'];
			}

		}

		$result=array_merge($data_pay,$data_weipay);

		$sort = array(
			'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
			'field'     => 'timeStamp', //排序字段
		);
		$arrSort = array();
		foreach($result AS $keys => $row){
			foreach($row AS $key=>$value){
				$arrSort[$key][$keys] = $value;
			}
		}
		if($sort['direction']){
			array_multisort($arrSort[$sort['field']], constant($sort['direction']), $result);
		}
		$this->_resp['result'] =$result;
		$this->output();
	}


       //保单凭证
	public function  detailsPolicy(){
		$mobile =session('appUser');
                $reserve_1=$this->_post['reserve_1'];//保单号
		$rfid     =$this->_post['rfid'];
		$rfid_area=$this->_post['rfid_area'];

		$id_code = M("app_regist")->where("mobile=$mobile")->find();
		$label   = M("dpc_regist")->where("owner_phone1=$mobile  and  owner_id='".$id_code['id_code']."' and rfid='$rfid' and rfid_area='$rfid_area' and cur_state=1 ")->find();

		$data=array();
		$data['owner_name']=$label['owner_name'];   //投保人
		$data['mobile']  =$label['owner_phone1']; //手机号
		$data['owner_id']  =$label['owner_id'];     //身份证
		$data['rfid']      =$label['rfid'];
		$data['plateNumber']=$label['attr1']; //车牌号
                if($label['type']=='1'){
				  $data['frameNumber'] =$label['attr6']; //电瓶车车架号
			  }else if($label['type']  =='10'){
				  $data['frameNumber'] =$label['attr5']; //摩托车车架号
			  }
                  $data['term']            =$label['attr12'];    //保险到期时间

			$alipay = M("alipay_records")->where('rfid="'.$label['rfid'].'" and rfid_area="'.$label['rfid_area'].'" and reserve_1="'.$reserve_1.'" and  trade_status="TRADE_SUCCESS"')->find();
		    if(!empty($alipay)){
				    $date_gmt=strtotime($alipay['gmt_payment']);
				    $data['gmt_payment']=date('Y-m-d',$date_gmt);//保险购买时间
			}

			$weipay = M("weipay_records")->where('rfid="'.$label['rfid'].'" and rfid_area="'.$label['rfid'].'" and reserve_1="'.$reserve_1.'" and  result_code="SUCCESS"')->find();
			if(!empty($weipay)){

				$date=strtotime($weipay['time_end']) ;
				$data['gmt_payment']  =date('Y-m-d',$date);//保险购买时间

			}

		$this->_resp['result'] =$data;
		$this->output();
	}


         //根据车辆类型选择保险条款
	 public function InsClauses(){
		 $mobile =session('appUser');
		 $this->app_getRecord($mobile);
		 $id_code = M("app_regist")->where("mobile=$mobile")->find();
		 $label   = M("dpc_regist")->where("owner_phone1=$mobile and owner_id='".$id_code['id_code']."' and cur_state=1")->select();

                 foreach($label as $key=>$v){
			 if($v['rfid_area'] == CITY_AREA_ZS){
				 $pro_nature='true';
				 break;
			 }else if($v['rfid_area'] == CITY_AREA_XN){
				 $pro_nature='true';
				 break;
			 }else if($v['rfid_area'] == CITY_AREA_QY){
				 $pro_nature='true';
				 break;
			 }else{
				 $pro_nature='false';
			 }

		 }

		 $this->_resp['result'] =$pro_nature;
		 $this->output();
	 }


    /*
     * 设防/取消
     */
    public function elecWall(){
		$mobile = $_SESSION['appUser'];
		$this->app_getRecord($mobile);
    	if(empty($mobile)){
    		$this->_resp['code'] = '-1';
    		$this->_resp['result'] = "请登录!";
    		$this->output();
    	}
    	$id_code = M("AppRegist")->where("mobile = '$mobile'")->getField("id_code");
    	$this->checkNecessaryParams("rfid,rfid_area");
    	$rfid  = $this->_post['rfid'];
    	$rfid_area  = $this->_post['rfid_area'];
    	$type = $this->_post['type'];
		$link = $this->_post['link'];
//添加代码处
    
		$regist_reader=M('DpcRegist')->where('rfid="'.$rfid.'" and rfid_area="'.$rfid_area.'" and cur_state =1')->find();
		$reader=$regist_reader['reader'];
		$reader_area=$regist_reader['reader_area'];
	
//end		
         //操作记录
		if($type == 0){
			//添加代码
		        //$alarm_location=M('DpcRegist')->where('rfid="'.$rfid.'" and rfid_area="'.$rfid_area.'" and owner_id="'.$id_code.'" and cur_state =1')->getField("alarm_location");//撤防时，将alarm_loction清空
			$alarm_loc['alarm_location']=0;
			$alarm_loc['update_date']   =date('Y-m-d H:i:s',time());
			M('DpcRegist')->where('id="'.$regist_reader['id'].'" and cur_state =1')->save($alarm_loc);
		
		$this->addRecord($mobile,$rfid,$rfid_area, RECORD1);
//end
    	}elseif ($type == 1){
			//添加代码处	
			$date=date('Y-m-d H:i:s');//当前时间
			$location_update_datetime=$regist_reader['location_update_datetime'];
			$update_datatime=strtotime($date )-strtotime($location_update_datetime);//比较之后的时间
					if($reader==0 && $reader_area==0){
						$alarm_loc['alarm_location']=0;
                        $alarm_loc['update_date']   =date('Y-m-d H:i:s',time());
					M("DpcRegist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and owner_id = '$id_code' and cur_state =1")->save($alarm_loc);
					}elseif ($update_datatime <=15){
						$save['alarm_location']=$reader.'_'.$reader_area;
						$save['update_date']   =date('Y-m-d H:i:s',time());
					M("DpcRegist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and owner_id = '$id_code' and cur_state =1")->save($save);
					}

	//end
	$this->addRecord($mobile,$rfid,$rfid_area, RECORD2);
		}
    	
    	if($link == 2){
    		//被共享的车
			$save['alarm_setting'] = $type;
            $save['update_date']   =date('Y-m-d H:i:s',time());
			$alarm_loc['alarm_location']=0;
			$alarm_loc['update_date']   =date('Y-m-d H:i:s',time());
    		$old_mobile = M("AppLink")->where("rfid = '$rfid' and rfid_area = '$rfid_area'")->getField("mobile");
    		$old_id_code = M("AppRegist")->where("mobile = '$old_mobile'")->getField("id_code");
			$id = M("DpcRegist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and owner_id = '$old_id_code'  and cur_state=1")->save($save);
            
			if($type== 0){
				M('DpcRegist')->where('rfid="'.$rfid.'" and rfid_area="'.$rfid_area.'" and owner_id="'.$old_id_code.'"  and cur_state=1')->save($alarm_loc);//撤防时，将alarm_loction清空
			}

    		if(empty($id)){
    			$this->_resp['code'] = '-2';
    			$this->_resp['result'] = "操作失败!";
    			$this->output();
    		}
    		$reg = M("DpcRegist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and owner_id = '$old_id_code' and cur_state=1")->field("rfid,rfid_area,alarm_setting as type")->find();
    		$this->_resp['result'] = $reg;
    		
    	}else{
    		
			$save['alarm_setting'] = $type;    		
			$save['update_date']   =date('Y-m-d H:i:s',time());
    		$id = M("DpcRegist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and owner_id = '$id_code' and cur_state=1")->save($save);
    		if(empty($id)){
    			$this->_resp['code'] = '-3';
    			$this->_resp['result'] = "操作失败!（此物品不属于自己）";
    			$this->output();
    		}
    		$reg = M("DpcRegist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and owner_id = '$id_code' and cur_state=1")->field("rfid,rfid_area,alarm_setting as type")->find();
    		$this->_resp['result'] = $reg;
    		
    	}
    	
    	$this->output();
    }


    /*
     * 接受/拒绝共享
     */
    public function acc_link(){    	
		$mobile = $_SESSION['appUser'];
		$this->app_getRecord($mobile);
    	if(empty($mobile)){
    		$this->_resp['code'] = '-1';
    		$this->_resp['result'] = "请登录!";
    		$this->output();
    	}    	
    	$this->checkNecessaryParams("rfid_list,status");
    	$rfid_list  = json_decode($this->_post['rfid_list'],true);
    	
		$status = $this->_post['status'];//1，接受。2.拒绝，3.解除
		$this->log($status);
    	foreach ($rfid_list as $k => $v){
    		$rfid  = $v['rfid'];
    		$rfid_area  = $v['rfid_area'];
    		//操作记录
    		if($status =='1'){
    			$this->addRecord($mobile, $rfid,$rfid_area,RECORD5);
    		}elseif ($status == '2'){
    			$this->addRecord($mobile, $rfid,$rfid_area,RECORD6);
    		}elseif ($status == '3'){
    			$this->addRecord($mobile, $rfid,$rfid_area,RECORD7);
    		}
    		$where = "rfid = '$rfid' and rfid_area = '$rfid_area'";
    		if($status == '3'){
    			$where .= " and mobile = '$mobile'";
    			$delid = M("AppLink")->where( $where )->delete();
//     			$save['cur_state'] = $status;
//     			$delid = M("AppLink")->where( $where )->save($save);
    			$this->log("解除共享".M("AppLink")->getLastSQL());
    			if(empty($delid)){
    				$this->log(M("AppLink")->getLastSQL());
    				$this->_resp['code'] = '-2';
    				$this->_resp['result'] = "解除共享失败!";
    				$this->output();
    			}
    		}else{
    			$where .= " and rfid_mobile = '$mobile'";
    			$save['cur_state'] = $status;
    			$id = M("AppLink")->where( $where )->save($save);
    			if(empty($id)){
    				$this->log(M("AppLink")->getLastSQL());
    				$this->_resp['code'] = '-3';
    				$this->_resp['result'] = "操作失败!";
    				$this->output();
    			}
    		}
    		   		
    	}    	

    	$this->output();
    }

    /*
     * 我的报警记录
     */
    public function lost_bike(){
		$mobile = $_SESSION['appUser'];
		$this->app_getRecord($mobile);
    	if(empty($mobile)){
    		$this->_resp['code'] = '-1';
    		$this->_resp['result'] = "请登录!";
    		$this->output();
    	}
    	$reg = M("AppRegist")->where("mobile = '$mobile'")->find();
    	$name = $reg['name'];
    	$mobiles = $reg['mobile'];
    	$id_code = $reg['id_code'];
    	
    	$lost = M("dpc_lostbike")->where("owner_id = '$id_code' and phone='$mobile'")->order('id desc')->select();
    	
    	$arr = array();
    	foreach ($lost as $k => $v){
    		$rfid  = $v['rfid'];
    		$rfid_area  = $v['rfid_area'];
                $dpc_name= M("DpcRegist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state=1")->getField("name");
    		if($dpc_name==""){
		$arr[$k]['name']="aaa";
                $arr[$k]['create_date'] = $v['report_date'];
	}else{
		$arr[$k]['name'] =$dpc_name;
		$arr[$k]['create_date'] = $v['report_date'];
			}
              
    	}
    	
		   $list['name'] = $name;
		   $list['mobile'] = $mobiles;
		   $list['lost_list'] = $arr;
		   $this->_resp['result'] = $list;
		   $this->output();
	  
    }


    /*
     * 我的资产列表
     */
    public function myList(){
    	$mobile = $_SESSION['appUser'];
		$this->app_getRecord($mobile);
    	if(empty($mobile)){
    		$this->_resp['code'] = '-1';
    		$this->_resp['result'] = "请登录!";
    		$this->output();
    	}
    	$id_code = M("AppRegist")->where("mobile = '$mobile'")->getField("id_code");
    	$Dpc = M("DpcRegist")->where("owner_id = '$id_code' and owner_phone1='$mobile' and cur_state=1")->select();
             //  ->getField('id,rfid,rfid_area,attr3,attr4,name,photo1,alarm_setting,type,reader,reader_area,location_update_datetime',true);
    	$list = array();
    	foreach ($Dpc as $k => $v){
    		$list[$k]['id'] = $v['id'];
    		$list[$k]['rfid'] = $v['rfid'];
    		$list[$k]['rfid_area'] = $v['rfid_area'];
    		$list[$k]['attr3'] = $v['attr3'];
    		$list[$k]['attr4'] = $v['attr4'];
    		$list[$k]['name'] = $v['name'];
//     		$list[$k]['photo'] = "http://test.zwtweb.win:82/uploadfile/photo/".$v['photo1'];
    		$list[$k]['photo'] = getPicPath($v['id'], "car").$v['photo1'];
    		$list[$k]['alarm_setting'] = $v['alarm_setting'];
    		$list[$k]['type'] = $v['type'];
    		
    		$reader = $v['reader'];
    		$reader_area = $v['reader_area'];
    		$location_id = M("dpc_reader")->where("reader = $reader and reader_area = $reader_area and cur_state=1")->getField("location_id");
                if(!empty($location_id)){
			$location = M("dpc_location")->where("id = $location_id and cur_state=1")->find();
		}
    		$list[$k]['last_area'] = $location['province'].$location['city'].$location['district'].$location['street'];
			
			$list[$k]['last_time'] = $v['location_update_datetime'];
    		$lb = M("AppLink")->where("rfid = '".$v['rfid']."' and rfid_area = '".$v['rfid_area']."' and mobile = '$mobile' and cur_state in(0,1)")->find();
    		$rb = M("AppLink")->where("rfid = '".$v['rfid']."' and rfid_area = '".$v['rfid_area']."' and rfid_mobile = '$mobile' and cur_state in(0,1)")->find();
    		$link = 0;
    		if(!empty($lb)){
    			$link = 1;
    		}
    		if(!empty($rb)){
    			$link = 2;
    		}
    		$list[$k]['link'] = $link;
    		
    		$now = date("Y-m-d",time());
    		//是否可以报警，查询今日已报警的次数<10
    		$count = M("DpcLostbike")->where("rfid = '".$v['rfid']."' and rfid_area = '".$v['rfid_area']."' and YEAR(create_date) = YEAR('$now') and MONTH(create_date) = MONTH('$now') and DAY(create_date) = DAY('$now') and cur_state = 0")->order("id desc")->find();
//     		if(!empty($count)){
//     			$list[$k]['can_lostbike'] = 0;//0，能报警
//     		}else{
//     			$list[$k]['can_lostbike'] = 1;//1，今日报警次数已达最大值
//     		}
    		if(empty($count)){
    			$list[$k]['can_lostbike'] = 0;//0，无数据
    		}else{
    			if($count['cur_state'] == 0){
    				$list[$k]['can_lostbike'] = 1;//1，可以报警
    			}else if($count['cur_state'] == 1){
    				$list[$k]['can_lostbike'] = 2;//2，已报警
    			}
    				
    		}
    	
    	}
    	$this->_resp['result'] = array(
    			'car_list' => $list
    	);
    	$this->output();
    }


    /*
     * 物品详情
     */
        public function getDetial(){
        	$mobile = $_SESSION['appUser'];
		$this->app_getRecord($mobile);
        	if(empty($mobile)){
    		$this->_resp['code'] = '-1';
    		$this->_resp['result'] = "请登录!";
    		$this->output();
    	}
    //	$this->checkNecessaryParams("rfid,rfid_area,type");
      	$rfid       = $this->_post['rfid'];
	$rfid_area  = $this->_post['rfid_area'];    	
	$type       = isset($this->_post['type'])? $this->_post['type']:1;
    	$reg        = M("DpcRegist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and type='$type' and cur_state=1")->find();
    	
    	$link = 0;
    	$lb = M("AppLink")->where("rfid = '".$reg['rfid']."' and rfid_area = '".$reg['rfid_area']."' and mobile = '$mobile'  and cur_state in(0,1)")->find();
    	$rb = M("AppLink")->where("rfid = '".$reg['rfid']."' and rfid_area = '".$reg['rfid_area']."' and rfid_mobile = '$mobile'  and cur_state in(0,1)")->find();
    	$link = 0;
    	if(!empty($lb)){
    		$link = 1;
    	}
    	if(!empty($rb)){
    		$link = 2;
    	}
         
    	$location_id = M("dpc_reader")->where("reader = '".$reg['reader']."' and reader_area = '".$reg['reader_area']."' and cur_state=1")
	       	   ->getField("location_id");
	$location = M("dpc_location")->where("id ='$location_id' and cur_state=1")->find();

           if($reg['rfid_area'] == 532503){
			 $last_area = $location['province'].$location['city'].$location['district'].$location['street'];
		 };

		 if($reg['rfid_area'] != 532503 && $location !=null && $reg['type']==1){
			 $last_area = $location['province'].$location['city'].$location['district'].$location['street'].'_'.$reg['rfid_area'];
		 }elseif($reg['rfid_area'] != 532503 && $reg['type']==1 && $location == null){
			 $last_area="";
		 };
		 if($reg['rfid_area'] != 532503  && $reg['type']!=1){
			$last_area = $location['province'].$location['city'].$location['district'].$location['street'];
			}

		if($type==1 || $type==2 || $type==10){
			$attr1=$reg['attr1'];
			$attr3=$reg['attr3'];
			$attr4=$reg['attr4'];		
		}elseif($type==3 || $type==4){
			$attr1=$reg['attr6'];
			$attr3=$reg['attr8'];
			$attr4="-1";
		}elseif($type==5 || $type==6 ||$type==7 || $type==8){
			$attr1=$reg['attr3'];
			$attr3=$reg['attr6'];
			$attr4=$reg['attr4'];
		}
		
//-------------------------------------------开始---------------------------------------------------------------------------

				if(empty($reg['attr12'])){
					$a= "+" . $reg['attr11'];//使用月数
					$create_date = $reg['fk_date'];//创建时间
					//体验期的到期时间
					$fk_time = date("Y-m-d", strtotime("$a month", strtotime($create_date)));
				}else if(!empty($reg['attr12'])){
					 $fk_time = $reg['attr12'];
				}
				//体验用户
				if($reg['attr11']>0 && $reg['attr11']<=11){
						$dpc_type=0;
				}
				//正式用户
				if($reg['attr11']>=12){
						$dpc_type=1;
				}
				//长期有效用户
				if($reg['attr11']<=0){
					$fk_time="无保险";
					$dpc_type=1;
				}
                        
                         //指定充值地区
			$area_role=M('user_area_role')->where("rfid_start='$rfid_area'")->find();//查询区域码所在的县，市，区。
			if($area_role){
				$area_rol=M('user_area_role')->where("district='".$area_role['city']."'")->find();//查询区域码所对应地级市的district,			
				if($area_rol['city']==CITY_QY){
					$region = 'true';
				}else if($area_rol['city']==CITY_XN){
					$region = 'true';
				}else if($area_rol['city']==CITY_ZS){
					$region = 'true';
				}else{
					$region = 'false';
				}
			}else{
				$region = 'false';
			}
//------------------------------------------结束----------------------------------------------------------------------------     
                 	$this->_resp['result'] = array(
    			'rfid' => $reg['rfid'],
    			'rfid_area' => $reg['rfid_area'],
    			'name' => $reg['name'],
//     			'photo' => "http://test.zwtweb.win:82/uploadfile/photo/".$reg['photo1'],
    			'photo'     => getPicPath($reg['id'], "car").$reg['photo1'],
			'type'      => $reg['type'],
			'attr1'     => $attr1,
    			'attr3'     => $attr3,
    			'attr4'     => $attr4,
    			'remark'    => $reg['remark'],
    			'owner_name'=> $reg['owner_name'],
    			'link'      => $link,
    			'alarm_setting' => $reg['alarm_setting'],
    			'last_area' => $last_area,
                        'fk_time'   =>$fk_time,
		        'dpc_type'  =>$dpc_type,
                        'region'    => $region,
    			'last_time' => $reg['location_update_datetime']    			
    	);
    	$this->output();
    }


    /*
     * 物品类型
     */
    public function getProductType(){
		$mobile = session('appUser');//记录操作
		$this->app_getRecord($mobile);
    	$type = $this->_post['type'];
    	if (empty($type)) {
    		$this->_resp['code']   = '1001';
			$this->_resp['result'] = 'type不能为空';
			$this->output();
    	}

    	$typeList = R("Base/Public/getDictData",array(1));

    	$data = array();
    	$data['id']   = $type;
    	$data['name'] = $typeList[$type];
    	$this->_resp['result'] = $data;
    	$this->output();
    }

    /*
     * 发布共享
     */
    public function addLink(){	
		$mobile = session('appUser');
		$this->app_getRecord($mobile);
	
    	if(empty($mobile)) {
    		$this->_resp['code']   = '-1';
			$this->_resp['result'] = '请登陆';
			$this->output();
}

    	$this->checkNecessaryParams('list,mobile,name');
    	
    	$name        = $this->_post['name'];
    	$rfid_mobile = $this->_post['mobile'];//被共享人手机号
    	if($mobile == $rfid_mobile){
    		$this->_resp['code']   = '1101';
    		$this->_resp['result'] = '不能共享给自己！';
    	$this->output();
    	}
    	$list = json_decode($this->_post['list'],true);
    	$rfid_list = $this->_post['list'];
    	foreach ($list as $k => $v){
    		$data = array();
    		$data['mobile']      = $mobile;
    		$data['rfid']        = $v['rfid'];
    		$data['rfid_mobile'] = $rfid_mobile;
    		$data['rfid_area']   = $v['rfid_area'];
    		$data['name']        = empty($v['rfid_name']) ? '' : $v['rfid_name'];
			$data['share_date']  = date('Y-m-d H:i:s',time());
	
	    $mobile_link=M('AppLink')->where("rfid='".$data['rfid']."' and rfid_mobile='".$data['rfid_mobile']."' and rfid_area='".$data['rfid_area']."'")->select();
	     if(empty($mobile_link)){
		    if (!M('AppLink')->add($data)) {
			$this->_resp['code']   = '1104';
			$this->_resp['result'] = '写入失败';
		    $this->log(M('AppLink')->getLastSQL());
			}
		 }else{
			 $sve['share_date']=date('Y-m-d H:i:s',time());
	       $log_app=M("AppLink")->where( "rfid='".$data['rfid']."' and rfid_mobile='".$data['rfid_mobile']."' and rfid_area='".$data['rfid_area']."'")->save($sve);
		   $this->log($log_app);
		}
    		
    	}
    	//推送共享消息给被共享人
    	$old_name = M("app_regist")->where("mobile = '$mobile'")->getField("name");
    	$RegistrationId = M("app_regist")->where("mobile = '$rfid_mobile'")->getField("reserve_1");
         if($RegistrationId==0){
		$this->_resp['code']   = '-2';
		$this->_resp['result'] = '请将安驹App升级到最新版本！';
		$this->output();
		}
    	$rand = rand(10000, 99999);
    	$Message = '{"type": "1","title": "共享","content": "您收到新的共享请求","id": "'.$rand.'","rfid_list": '.$rfid_list.'}';
    	
    	$ios_alert = "您收到一个共享消息";
    	$ios_value = '{"type":"1","name":"'.$old_name.'","rfid_list": '.$rfid_list.'}';
		//缓存推送消息
		$add['mobile'] = $rfid_mobile;
		$add['message'] = $ios_value;
//		$del = M("ios_message")->where("mobile = '$rfid_mobile'")->delete();
		$mes_id = M("ios_message")->data($add)->add();
// 		$this->log("缓存消息添加语句：".M("ios_message")->getLastSQL());
		$ios_sound = 'default';
    	$Android_title = "共享";
    	$Android_value = "您收到一个共享消息";
     	$this->log("安卓共享推送内容".$Message);
    	$this->addpush($RegistrationId, $Message,$Android_title,$Android_value,$ios_alert,$ios_value,$ios_sound);
    	$this->output();
    }



//新版发布共享
      public function new_addLink(){
		$mobile = session('appUser');
		$this->app_getRecord($mobile);
		if(empty($mobile)){
			$this->_resp['code']   = '-1';
			$this->_resp['result'] = '请登陆';
			$this->output();
		}
		$this->checkNecessaryParams('list,mobile,name');
		$name        = $this->_post['name'];//被共享人的姓名
		$rfid_mobile = $this->_post['mobile'];//被共享人手机号
		if($mobile == $rfid_mobile){
			$this->_resp['code']   = '1101';
			$this->_resp['result'] = '不能共享给自己！';
			$this->output();
		}
		$list = json_decode($this->_post['list'],true);
		$rfid_list = $this->_post['list'];
		foreach ($list as $k => $v){
			$data = array();
			$data['mobile']      = $mobile;
			$data['rfid']        = $v['rfid'];
			$data['rfid_mobile'] = $rfid_mobile;
			$data['rfid_area']   = $v['rfid_area'];
			$data['name']        = empty($v['rfid_name']) ? '' : $v['rfid_name'];
			$data['share_date']  = date('Y-m-d H:i:s',time());

			$mobile_link=M('AppLink')->where("rfid='".$data['rfid']."' and rfid_mobile='".$data['rfid_mobile']."' and rfid_area='".$data['rfid_area']."'")->select();
			if(empty($mobile_link)){
				if (!M('AppLink')->add($data)) {
					$this->_resp['code']   = '1104';
					$this->_resp['result'] = '写入失败';
					$this->log(M('AppLink')->getLastSQL());
				}
			}else{
				$sve['share_date']=date('Y-m-d H:i:s',time());
				$log_app=M("AppLink")->where( "rfid='".$data['rfid']."' and rfid_mobile='".$data['rfid_mobile']."' and rfid_area='".$data['rfid_area']."'")->save($sve);
				$this->log($log_app);
			}
		}
		//推送共享消息给被共享人
		$old_name = M("app_regist")->where("mobile = '$mobile'")->getField("name");

		$RegistrationId = M("app_regist")->where("mobile = '$rfid_mobile'")->getField("reserve_1");
                if($RegistrationId != 0){
			$this->_resp['code']   = '-2';
			$this->_resp['result'] = '对方安驹版本过低，无法完成共享，请先升级!';
			$this->output();
		}

		$tag = $rfid_mobile.'SHzhaowei2017';
		$rand = rand(10000, 99999);
		$Android_info = '{"type": "1","title": "共享","content": "您收到新的共享请求","id": "' . $rand . '","rfid_list": ' . $rfid_list . '}';
		$ios_alert = "您收到一个共享消息";
		$ios_value = '{"type":"1","name":"' . $old_name . '","rfid_list": ' . $rfid_list . '}';
		//缓存推送消息
		$add['mobile'] = $rfid_mobile;
		$add['message'] = $ios_value;
                $add['time'] = date('Y-d-m H:i:s',time());
//		$del = M("ios_message")->where("mobile = '$rfid_mobile'")->delete();
		$mes_id = M("ios_message")->data($add)->add();
// 		$this->log("缓存消息添加语句：".M("ios_message")->getLastSQL());
		$ios_sound = 'default';
                $Android_title = "共享";
		$Android_value = "您收到一个共享消息";
		$this->addPush_tag($tag,$Android_info, $Android_title, $Android_value, $ios_alert, $ios_value, $ios_sound);
		$this->output();
	}



    /*
     * 共享校验
     */
    public function checkShared(){
    	$mobile = session('appUser');
		$this->app_getRecord($mobile);
    	if (empty($mobile)) {
    		$this->_resp['code']   = '-1';
    		$this->_resp['result'] = '请登陆';
    		$this->output();
    	}
    
    	$this->checkNecessaryParams('mobile,name');
    	 
    	$name   = $this->_post['name'];
    	$rfid_mobile = $this->_post['mobile'];
    
    	//判断是否存在要关联的用户
    	$where = array();
    	$where['name']   = $name;
    	$where['mobile'] = $rfid_mobile;
    	if (!M('AppRegist')->where($where)->getField('id')) {
    		$this->_resp['code']   = '1102';
    		$this->_resp['result'] = '不存在的用户,请检查姓名或者手机号是否正确';
    		$this->output();
    	}
    
//     	//判断是否已关联过此用户
//     	$where = array();
//     	$where['mobile']      = $mobile;
//     	$where['rfid_mobile'] = $rfid_mobile;
//     	if (!M('AppLink')->where($where)->getField('id')) {
//     		$this->_resp['code']   = 1102;
//     		$this->_resp['result'] = '已关联过此用户,请不要重复添加';
//     		$this->output();
//     	}

    	$this->output();
    }


    public  function addProduct(){
	$mobile = session('appUser');
	$this->app_getRecord($mobile);
	if (empty($mobile)) {
		$this->_resp['code']   = '-1';
		$this->_resp['result'] = '请登陆';
		$this->output();
	}
	
	$this->_resp['code'] = '3';
	$this->_resp['result'] = "禁止该操作!";
	$this->output();
    } 


    /*
     * 注册电动车
     */
    public function addProduct_ss(){
		$mobile = session('appUser');
		$this->app_getRecord($mobile);
	if (empty($mobile)) {
    		$this->_resp['code']   = '-1';
    		$this->_resp['result'] = '请登陆';
    		$this->output();
	}
    	$this->checkNecessaryParams("rfid,rfid_area,fk_date,name,owner_name,attr3,attr4,type,attr1");
    	$rfid  = $this->_post['rfid'];
    	$rfid_area  = $this->_post['rfid_area'];
    	$name    = $this->_post['name'];
    	$fk_date    = $this->_post['fk_date'];
    	$owner_name    = $this->_post['owner_name'];
		$type = $this->_post['type'];
		$attr1 = $this->_post['attr1'];

    	$owner_id    = M("app_regist")->where("mobile = $mobile")->getField("id_code");
    	$attr3    = $this->_post['attr3'];
    	$attr4    = $this->_post['attr4'];
    	$remark    = $this->_post['remark'];
    	
    	$old = M("DpcRegist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state=1")->find();
    	if($old){
    		$this->_resp['code'] = '1101';
    		$this->_resp['result'] = "该物品已存在!";
    		$this->output();
    	}
    	
    	$add['name'] = $name;
    	$add['type'] = 1;
    	$add['owner_name'] = $owner_name;
    	$add['owner_id'] = $owner_id;
    	$add['owner_phone1'] = $mobile;
    	$add['rfid'] = $rfid;
    	$add['rfid_area'] = $rfid_area;
		$add['attr3'] = $attr3;
		$add['attr4'] = $attr4;
		$add['fk_date'] = $fk_date;
		$add['type']= $type;
		$add['attr1'] = $attr1;
        if ($remark) {
            $add['remark'] = $remark;
        }
    	
    	 
    	$id = M("DpcRegist")->data($add)->add();
		if (empty($id)) 
		{
			$this->log(M("DpcRegist")->getLastSQL());
			$this->_resp['code'] = '-2';
			$this->_resp['result'] = "注册电瓶车失败!";
			$this->output();
		}

    	
    	/**
    	 * 保存图片
    	 */   
		$filename = $_FILES[0]['name'];
	$this->log("filename:".$filename);
    	$extension = explode(".", $filename);
    	$new_pic = $rfid."_".$rfid_area.$extension[1];
   $this->log("图片名称：".$new_pic);	
		$images = $this->uploadPhotos($id,'car',$new_pic);
   $this->log("图片路径：".$images);		
    	if ($images) {
    		$photo = $images[0]['savename'];
    		
    		$this->log("savename:".$photo);
    		
    	}else{
    		$this->_resp['code'] = '-4';
    		$this->_resp['result'] = "上传图片失败!";
    		$this->output();
    	}
		$save['photo1'] = $photo;
		$save['update_date']   =date('Y-m-d H:i:s',time());
    	$ss = M("DpcRegist")->where("id = $id and cur_state=1")->save($save);
    	if (empty($ss)) {
    		$this->_resp['code'] = '-3';
    		$this->_resp['result'] = "电瓶车图片保存失败!";
    		$this->output();
		}
    
    	$this->output();
	}




	/**
	 * 
     *添加人物
     **/
	public function addPeople(){
	$mobile = session('appUser');
	$this->app_getRecord($mobile);
	if (empty($mobile)) {
		$this->_resp['code']   = '-1';
		$this->_resp['result'] = '请登陆';
		$this->output();
	}
	$this->checkNecessaryParams("rfid,rfid_area,fk_date,name,owner_name,attr8,attr6,type");
	$rfid     =$this->_post['rfid'];
	$rfid_area=$this->_post['rfid_area'];
	$fk_date  =$this->_post['fk_date'];
	$name  =$this->_post['name'];
	$owner_name=$this->_post['owner_name'];
	$attr8    =$this->_post['attr8'];
	$attr6    =$this->_post['attr6'];
	$type     =$this->_post['type'];
	$remark   =$this->_post['remark'];
    $owner_id = M("app_regist")->where("mobile = $mobile")->getField("id_code");

	$id=M('DpcRegist')->where('rfid="'.$rfid.'" and rfid_area="'.$rfid_area.'" and cur_state=1')->find();
	if($id){
		$this->_resp['code']   = '1101';
		$this->_resp['result'] = '该人物已存在';
		$this->output();
	}
    $data['owner_id']=$owner_id;
	$data['owner_phone1']=$mobile;
	$data['rfid']=$rfid;
	$data['rfid_area']=$rfid_area;
	$data['fk_date']=$fk_date;
	$data['name']=$name;
	$data['owner_name']=$owner_name;
	$data['attr8']=$attr8;
	$data['attr6']=$attr6;
	$data['type']=$type;
	if($remark){
		$data['remark']=$remark;
	}
	$ins=M('DpcRegist')->data($data)->add();
	if(empty($ins)){
		$this->log(M("DpcRegist")->getLastSQL());
		$this->_resp['code'] = '-2';
		$this->_resp['result'] = "该人物添加失败!";
		$this->output();
	}


	/**
	 * 保存图片
	  */
	$filename = $_FILES[0]['name'];
	$this->log("filename:".$filename);

	$extension = explode(".", $filename);
	$this->log("extension:".$extension);

	$new_pic = $rfid."_".$rfid_area.$extension[1];
	$this->log("new_pic:".$new_pic);

	$images = $this->uploadPhotos($ins,'car' ,$new_pic);
	$this->log("inages:".$images);
	if ($images) {
		$photo = $images[0]['savename'];
		$this->log("图片:".$photo);
	}else{
		$this->_resp['code'] = '-4';
		$this->_resp['result'] = "上传图片失败!";
		$this->output();
	}
	$save['photo1'] = $photo;
	$save['update_date']   =date('Y-m-d H:i:s',time());
	$ss = M("DpcRegist")->where("id = $ins and cur_state=1")->save($save);
	if (empty($ss)) {
		$this->_resp['code'] = '-3';
		$this->_resp['result'] = "人物图片保存失败!";
		$this->output();
	}
	$this->output();
	}


	//添加动物

	public function addAnimal(){
		$mobile = session('appUser');
		$this->app_getRecord($mobile);
		$this->checkNecessaryParams("rfid,rfid_area,fk_date,name,owner_name,attr3,attr6,attr4,type");
		$rfid     =$this->_post['rfid'];
		$rfid_area=$this->_post['rfid_area'];
		$fk_date  =$this->_post['fk_date'];
		$name     =$this->_post['name'];
		$owner_name=$this->_post['owner_name'];
		$attr3    =$this->_post['attr3'];
		$attr6    =$this->_post['attr6'];
		$attr4    =$this->_post['attr4'];
		$type     =$this->_post['type'];
		$remark   =$this->_post['remark'];
		$owner_id =M('app_regist')->where("mobile='$mobile'")->getField('id_code');
		$n_n=M('DpcRegist')->where("rfid='$rfid' and rfid_area='$rfid_area' and cur_state=1")->find();
		if($n_n){
			$this->_resp['code'] = '1101';
			$this->_resp['result'] = "该动物已存在!";
			$this->output();
		}

		$data['rfid']     =$rfid;
		$data['rfid_area']=$rfid_area;
		$data['owner_id'] =$owner_id;
		$data['owner_phone1']=$mobile;
		$data['fk_date']  =$fk_date;
		$data['name']     =$name;
		$data['owner_name']=$owner_name;
		$data['attr3']    =$attr3;
		$data['attr6']    =$attr6;
		$data['attr4']    =$attr4;
		$data['type']     =$type;
		if($remark){
			$data['remark']=$remark;
		}
		$id=M('DpcRegist')->data($data)->add();
		if(empty($id)){
			$this->log(M("DpcRegist")->getLastSQL());
			$this->_resp['code'] = '-2';
			$this->_resp['result'] = "该动物添加失败!";
			$this->output();
		}

		//保存图片

		$filename = $_FILES[0]['name'];
		$this->log("filename:",$filename);
		$extension = explode(".", $filename);
		$this->log("extension:",$extension);
		$new_pic = $rfid."_".$rfid_area.$extension[1];
		$this->log("new_pic:",$new_pic);
		$images = $this->uploadPhotos($id,'car',$new_pic);
		$this->log("inages:",$images);
		if ($images) {
			$photo = $images[0]['savename'];
		}else{
			$this->_resp['code'] = '-4';
			$this->_resp['result'] = "上传图片失败!";
			$this->output();
		}
			$save['photo1'] = $photo;
            $save['update_date']   =date('Y-m-d H:i:s',time());
			$ss = M("DpcRegist")->where("id = $id and cur_state=1")->save($save);
			if (empty($ss)) {
				$this->_resp['code'] = '-3';
				$this->_resp['result'] = "人物图片保存失败!";
				$this->output();
			}
			$this->output();
	}





	//验证rfid是否存在
	public function rfid_Verification(){
		$rfid  = $this->_post['rfid'];
		$rfid_area  = $this->_post['rfid_area'];
                $android =$this->_post['android'];
		$old = M("DpcRegist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state=1")->find();
		if($old){
			$this->_resp['code'] = '1101';
			$this->_resp['result'] = "该物品已存在!";
			$this->output();
		}
                
                if($android=='1'){
			$this->output();
		};  
	}





    /*
     * 编辑物品
     */
    public function edit(){
		$mobile = session('appUser');
		$this->app_getRecord($mobile);
    	$this->checkNecessaryParams("rfid,name,rfid_area");
    	$rfid  = $this->_post['rfid'];
    	$rfid_area  = $this->_post['rfid_area'];
    	$name     = $this->_post['name'];
    	$remark    = $this->_post['remark'];
    	$link = M("app_link")->where("rfid_mobile = '$mobile' and rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state in(0,2)")->find();
    	$link_no = M("app_link")->where("rfid_mobile = '$mobile' and rfid = '$rfid' and rfid_area = '$rfid_area' ")->find();

		//查询车主
		$dpc_regist = M("dpc_regist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state=1")->find();
		if(empty($dpc_regist)){
			$this->_resp['code']   = '0011';
			$this->_resp['result'] = '物品不存在，操作失败';
			$this->output();
		}
		if($dpc_regist['owner_phone1'] != $mobile){
			//登录用户非车主
			if(empty($link_no)){
				//没有被共享
				$this->_resp['code']   = '0014';
				$this->_resp['result'] = '您无此物品授权，操作失败';
				$this->output();
			}else{
				//有被共享数据
				if(!empty($link)){
					$this->_resp['code']   = '0015';
					$this->_resp['result'] = '物品当前状态您无此操作权限';
					$this->output();
				}
			}
		}
		
		
    	$data['name'] = $name;
		$data['remark'] = $remark;
		$data['update_date']   =date('Y-m-d H:i:s',time());
    	if (false === M('dpc_regist')->where("rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state=1")->save($data)) {
    		$this->_resp['code']   = '0013';
    		$this->_resp['result'] = '编辑物品失败';
    		$this->output();
    	}else {
    		$this->_resp['code']   = '0';
    		$this->_resp['result'] = '修改成功';
    		$this->output();
    	}

    	$this->output();
    }

    
    /*
     * 操作记录列表
     */
    public function getRecord(){
    	$mobile = session('appUser');
		$this->app_getRecord($mobile);
    	$this->checkNecessaryParams("rfid,rfid_area");
    	if (empty($mobile)) {
    		$this->_resp['code']   = '-1';
    		$this->_resp['result'] = '请登陆';
    		$this->output();
    	}
    	$rfid  = $this->_post['rfid'];
    	$rfid_area  = $this->_post['rfid_area'];
    	$rid = M("AppRegist")->where("mobile = '$mobile'")->getField("id");
    	
    	$where = "app_rid = $rid and rfid = '$rfid' and rfid_area = '$rfid_area' ";
    	
    	$time = $_POST['time'];
    	if(!empty($time)){
    		$where .= " and YEAR(createtime) = YEAR('$time') and MONTH(createtime) = MONTH('$time') and DAY(createtime) = DAY('$time') ";
    	}
    	$list = M("AppRecord")->where($where)->field("createtime,type")->order('id desc')->select();
    	foreach ($list as $k => $v){
    		$ct = explode(" ", $v['createtime']);
    		$list[$k]['createtime'] = $ct[1];
    		$list[$k]['type'] = $v['type'];
    	}
    	$this->_resp['result'] = array(
    			'list' => $list
    	);
    	$this->output();
    }
    
    
    /*
     * 确认/取消报警
     */
    public function addLostbike(){
    	$mobile = session('appUser');
        $this->log('手机号'.$mobile);
	$this->app_getRecord($mobile);
    	$this->checkNecessaryParams("rfid,rfid_area,type");
    	if (empty($mobile)) {
    		$this->_resp['code']   = '-1';
    		$this->_resp['result'] = '请登陆';
    		$this->output();
    	}
    	$rfid  = $this->_post['rfid'];
    	$rfid_area  = $this->_post['rfid_area'];
    	$type  = $this->_post['type'];

		if($type == 0){
			$attr17=M("DpcRegist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state=1")->getField('attr17');
			if($attr17==2){
				$this->_resp['code']   = '-11';
				$this->_resp['result'] = '拒绝修改请求';
				$this->output();

			
			};

				$save1['alarm_setting'] = 0;
                $save1['update_date']   =date('Y-m-d H:i:s',time());
				$d1 = M("DpcRegist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state=1")->save($save1);


		$Miss_id=M('dpc_lostbike')->where('rfid="'.$rfid.'" and rfid_area="'.$rfid_area.'" and cur_state=1')->find();
		if(!empty($Miss_id)){
				$v['cur_state'] = 3;
				$v['update_date']=date("Y-m-d H:i:s",time());//报案日期
   				M("dpc_lostbike")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state=1 ")->save($v);
                };		
		$this->addRecord($mobile, $rfid,$rfid_area,RECORD4);
         	}else if($type == 1){
				$save1['alarm_setting'] = 2;
				$save1['update_date']   =date('Y-m-d H:i:s',time());
			$d1 = M("DpcRegist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state=1")->save($save1);

               //判断这辆车有没有重复报警，如果有只更新时间，如果没有就添加一条新的报警记录
		$id=M("dpc_lostbike")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state=1")->find();

                if(!empty($id)){
         		$vr['update_date']=date("Y-m-d H:i:s",time());//报案日期
	        	M("dpc_lostbike")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state=1")->save($vr);
		}else{
			$cur_state=1;//确认报警
			$this->add_Lostbike($mobile,$rfid,$rfid_area,$cur_state);
        	};
			
                        //失踪车辆            
			$Miss_id=M('dpc_addlostbike')->where('rfid="'.$rfid.'" and rfid_area="'.$rfid_area.'"')->find();
			if(!empty($Miss_id)){
			 $v['lost_date']=date("Y-m-d H:i:s",time());//丢失日期
			 $v['report_date']=date("Y-m-d H:i:s",time());//报案日期
			 M("dpc_addlostbike")->where("rfid = '$rfid' and rfid_area = '$rfid_area' ")->save($v);
			}else{
				$this->Missing_vehicle($mobile,$rfid,$rfid_area);
			}
    		$this->addRecord($mobile, $rfid,$rfid_area,RECORD3);    		
		
		}
       if($type==0){
		   $save['push_state']=0;
		   $save['update_date']=date('Y-m-d H:i:s',time());
		 M("DpcRegist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state=1")->save($save);
	   }else{
		   $save['push_state']=2;
		   $save['update_date']=date('Y-m-d H:i:s',time());
		 M("DpcRegist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state=1")->save($save);
	   }

    	$this->output();
    }
    
     
    /*
     * 店铺列表
     */
    public function shopList(){
		$mobile = session('appUser');//记录操作用
		$this->app_getRecord($mobile);
    	$taobao = M("taobao")->select();
    	$list = array();
    	foreach ($taobao as $k => $v){
    		$list[$k]['name'] = $v['name'];
    		$list[$k]['jianjie'] = $v['jianjie'];
    		$list[$k]['link'] = $v['link'];
	//		$list[$k]['logo'] = "139.196.207.192/anju/Web/Uploads/taobao/1/21/".$v['logo'];
    		$list[$k]['logo'] = getPicPath($v['id'], "taobao").$v['logo'];
    	}
    		
    	$this->_resp['result'] = $list;
    	$this->output();
    }
    
    
    /*
     * 报警消息推送
     */
    public function Callpolice(){
    	$this->checkNecessaryParams("rfid,rfid_area");
    	
    	$rfid       = $this->_post['rfid'];
    	$rfid_area  = $this->_post['rfid_area'];
    	$link       = M("app_link")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state=1")->find();
    	$dpc        = M("dpc_regist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state=1")->find();
        $type       = $dpc['type'];
		$mobiles    = $dpc['owner_phone1'];

    	if(empty($mobiles)){
    		$this->_resp['code']   = '-99';
    		$this->_resp['result'] = '该物品不存在';
    		$this->output();
    	}
		$rfid_mobile = $link['rfid_mobile'];
		$n_name=$dpc['name'];

        //被共享人的姓名
		$app_name = M('app_regist')->where('mobile='.$rfid_mobile)->getField('name');

    	//推送报警消息
    	$RegistrationId1 = M("app_regist")->where("mobile = '$mobiles'")->getField("reserve_1");
    	$RegistrationId2 = M("app_regist")->where("mobile = '$rfid_mobile'")->getField("reserve_1");
    	$rand1 = rand(10000, 99999);
    	$rand2 = rand(10000, 99999);
    	$Message1 = '{"type": "0","title": "报警","content": "确认报警","id": "'.$rand1.'","n_name": "'.$n_name.'","asset_type": "'.$type.'","rfid": "'.$rfid.'","rfid_area": "'.$rfid_area.'",';
    	$Message2 = '{"type": "0","title": "报警","content": "确认报警","id": "'.$rand2.'","n_name": "'.$n_name.'","asset_type": "'.$type.'","rfid": "'.$rfid.'","rfid_area": "'.$rfid_area.'",';
    	
    	$ios_alert = "您有一个报警待处理";
		$ios_value = '{"type":"0","rfid":"'.$rfid.'","rfid_area": "'.$rfid_area.'","asset_type": "'.$type.'","ios_name": "'.$n_name.'",';
		$ios_valueInfo = '{"type":"0","rfid":"'.$rfid.'","rfid_area": "'.$rfid_area.'","asset_type": "'.$type.'","ios_name": "'.$n_name.'",';
    	$Android_title = "报警";
    	$Android_value = "您有一个报警待处理";
		$ios_sound = 'waring.caf';
          

			if(empty($link)){
				$Message1 .= '"flag":"0"}';
				$ios_value.='"flag":"0"}';
        		$result1 = $this->addpush($RegistrationId1, $Message1,$Android_title,$Android_value,$ios_alert,$ios_value,$ios_sound);
				$this->log('原车主推送信息：' . json_encode($result1));
    	}
			if(!empty($link)){
				$Message1 .= '"flag":"1","app_name":"'.$app_name.'"}';
				$ios_value .= '"flag":"1","app_name":"'.$app_name.'"}';
      		    	$result1 = $this->addpush($RegistrationId1, $Message1,$Android_title,$Android_value,$ios_alert,$ios_value,$ios_sound);
				$this->log('原车主推送信息：' . json_encode($result1));

			$Message2 .= '"flag":"0"}';
			$ios_valueInfo .= '"flag":"0"}';
    		$result2 = $this->addpush($RegistrationId2, $Message2,$Android_title,$Android_value,$ios_alert,$ios_valueInfo,$ios_sound);
    		$this->log('被关联者推送消息：' . json_encode($result2));
			}

	    	//ios推送报警消息
			$ios_values = '{"type":"0","asset_type": "'.$type.'","ios_name":"'.$dpc['name'].'","rfid":"'.$rfid.'","rfid_area": "'.$rfid_area.'",';
			if(empty($link)){
				$ios_values .='"flag":"0"}';//flag:0,车主做操作；
			};
			if(!empty($link)){
				$ios_values .='"flag":"1","app_name":"'.$app_name.'"}';//flag:1,车主不做操作；
			};
			$add['mobile']=$mobiles;
			$add['message']=$ios_values;
			$add['time']=date('Y-m-d H:i:s',time());
			M("ios_message")->data($add)->add();

    	$this->output();
    }


  //主要针对老人，小孩的主动报警
   	public function   Help(){
	  	$this->checkNecessaryParams('rfid,rfid_area');
		$rfid      = $this->_post['rfid'];
		$rfid_area = $this->_post['rfid_area'];
		$link      = M('app_link')->where("rfid='$rfid' and rfid_area='$rfid_area' and cur_state =1")->find();
		$dpc       = M('dpc_regist')->where("rfid='$rfid' and rfid_area='$rfid_area' and cur_state=1")->find();

		if(empty($dpc)){
			$this->_resp['code']   = '-99';
			$this->_resp['result'] = '该人物不存在';
			$this->output();
		}
		//找到报警用户(共享人)
		$mobile          = $dpc['owner_phone1'];
		$RegistrationId1 = M('app_regist')->where("mobile='$mobile'")->getField('reserve_1');
		$rand1           = rand(10000, 99999);

		//找到被共享人
		$link_mobile     = $link['rfid_mobile'];
		$RegistrationId2 = M('app_regist')->where("rfid_mobile='$link_mobile'")->getField('reserve_1');
		$rand2           = rand(10000, 99999);

		//给用户报警，推送报警消息
		$owner_name=$dpc['name'];
		$Message1='{"type": "2","title": "报警","content": "确认报警","id": "'.$rand1.'","n_name": "'.$owner_name.'","rfid": "'.$rfid.'","rfid_area": "'.$rfid_area.'"}';
		$Message2='{"type": "2","title": "报警","content": "确认报警","id": "'.$rand2.'","n_name": "'.$owner_name.'","rfid": "'.$rfid.'","rfid_area": "'.$rfid_area.'"}';
		$ios_alert = "您有一个报警待处理";
		$ios_value = '{"type":"2","rfid":"'.$rfid.'","rfid_area": "'.$rfid_area.'","ios_name": "'.$owner_name.'",';
		$Android_title = "报警";
		$Android_value = "您有一个报警待处理";
		$ios_sound = 'waring.caf';
		//无共享的情况
		if(empty($link)){
			$ios_value.='"flag":"0"}';
			$this->addpush($RegistrationId1, $Message1,$Android_title,$Android_value,$ios_alert,$ios_value,$ios_sound);
			//修改表中的完成推送状态
			$se_ve['push_state']   =1;
			$sa_ve['update_date']  =date('Y-m-d H:i:s',time());
			M('dpc_regist')->where("rfid='$rfid' and rfid_area='$rfid_area' and cur_state=1")->save($se_ve);
		}
		//有共享的情况
		if(!empty($link)){
			$ios_value .= '"flag":"1"}';
			$this->addpush($RegistrationId1, $Message1,$Android_title,$Android_value,$ios_alert,$ios_value,$ios_sound);
			$ios_value .= '"flag":"0"}';
			$this->addpush($RegistrationId2, $Message2,$Android_title,$Android_value,$ios_alert,$ios_value,$ios_sound);
     		//修改表中的完成推送状态
			$se_ve['push_state']   =1;
			$sa_ve['update_date']   =date('Y-m-d H:i:s',time());
			M('dpc_regist')->where("rfid='$rfid' and rfid_area='$rfid_area' and cur_state=1")->save($se_ve);
		}
		$ios_values='{"type":"2","name":"'.$owner_name.'","rfid":"'.$rfid.'","rfid_area": "'.$rfid_area.'","flag":"0"}';//flag:0,车主做操作；;
		$add['mobile']  = $mobile;
		$add['message'] = $ios_values;
		$add['time']    = date('Y-m-d H:i:s',time());
		M("ios_message")->data($add)->add();
		$this->output();
	
	}


 //取消主动报警（针对老人小孩）
	public function CancelHelp(){
		$this->checkNecessaryParams('rfid,rfid_area');
		$rfid      = $this->_post['rfid'];
		$rfid_area = $this->_post['rfid_area'];
		$link      = M('app_link')->where("rfid='$rfid' and rfid_area='$rfid_area' and cur_state =1")->find();
		$dpc       = M('dpc_regist')->where("rfid='$rfid' and rfid_area='$rfid_area' and cur_state=1")->find();

		if(empty($dpc)){
	    	$this->_resp['code']   = '-99';
			$this->_resp['result'] = '该人物不存在';
			$this->output();
		}
		//找到报警用户(共享人)
		$mobile          = $dpc['owner_phone1'];
		$RegistrationId1 = M('app_regist')->where("mobile='$mobile'")->getField('reserve_1');
		$rand1           = rand(10000, 99999);
		//找到被共享人
		$link_mobile     = $link['rfid_mobile'];
		$RegistrationId2 = M('app_regist')->where("rfid_mobile='$link_mobile'")->getField('reserve_1');
		$rand2           = rand(10000, 99999);
		//给用户报警，推送报警消息
        $owner_name=$dpc['name'];
		$Message1='{"type": "3","title": "报警","content": "取消报警","id": "'.$rand1.'","n_name": "'.$owner_name.'","rfid": "'.$rfid.'","rfid_area": "'.$rfid_area.'"}';
		$Message2='{"type": "3","title": "报警","content": "取消报警","id": "'.$rand2.'","n_name": "'.$owner_name.'","rfid": "'.$rfid.'","rfid_area": "'.$rfid_area.'"}';
		$ios_alert = "您有一个报警待处理";
		$ios_value = '{"type":"3","rfid":"'.$rfid.'","rfid_area": "'.$rfid_area.'","ios_name": "'.$owner_name.'",';
		$Android_title = "报警";
		$Android_value = "您有一个报警待处理";
		$ios_sound = 'waring.caf';
		//无共享的情况
		if(empty($link)){
			$ios_value.='"flag":"0"}';
			$this->addpush($RegistrationId1, $Message1,$Android_title,$Android_value,$ios_alert,$ios_value,$ios_sound);
		   //修改表中的报警状态				
			$se_ve['alarm_setting']=0;
			$se_ve['push_state']   =1;
			$sa_ve['update_date']   =date('Y-m-d H:i:s',time());
			M('dpc_regist')->where("rfid='$rfid' and rfid_area='$rfid_area' and cur_state=1")->save($se_ve);
		}
	    //有共享的情况
		if(!empty($link)){
			$ios_value.='"flag":"1"}';
			$this->addpush($RegistrationId1, $Message1,$Android_title,$Android_value,$ios_alert,$ios_value,$ios_sound);
			$ios_value.='"flag":"0"}';
			$this->addpush($RegistrationId2, $Message2,$Android_title,$Android_value,$ios_alert,$ios_value,$ios_sound);
			//修改表中的报警状态
			$se_ve['alarm_setting']=0;
			$se_ve['push_state']   =1;
			$sa_ve['update_date']   =date('Y-m-d H:i:s',time());
			M('dpc_regist')->where("rfid='$rfid' and rfid_area='$rfid_area' and cur_state=1")->save($se_ve);
		}
		$ios_values='{"type":"3","name":"'.$owner_name.'","rfid":"'.$rfid.'","rfid_area": "'.$rfid_area.'","flag":"0"}';//flag:0,车主做操作；;
		$add['mobile']  = $mobile;
		$add['message'] = $ios_values;
		$add['time']    = date('Y-m-d H:i:s',time());
		M("ios_message")->data($add)->add();
		$this->output();
	}


 //确认按钮
      public function Confirm(){
		 // $this->checkNecessaryParams('rfid,rfid_area,type');
		  $rfid      = $this->_post['rfid'];
		  $rfid_area = $this->_post['rfid_area'];
		  $type      = isset($this->_post['type']) ? $this->_post['type']:0;
		  $dpc =M('dpc_regist')->where("rfid='$rfid' and rfid_area=$rfid_area and cur_state =1")->find();
		  if(empty($dpc)){
			  $this->_resp['code']   = '-99';
			  $this->_resp['result'] = '该资产不存在';
			  $this->output();
		  }
		  if(!empty($dpc) && $type==1){//type==1（主动报警）确认报警
			$dp['push_state']=2;
			$dp['alarm_setting']=2;
			$dp['update_date']   =date('Y-m-d H:i:s',time());
			M('dpc_regist')->where("rfid='$rfid' and rfid_area='$rfid_area' and cur_state=1")->save($dp);
		  }elseif($type==0){//type==0取消报警（主动取消报警）
			  $dp['push_state']=2;
                          $dp['alarm_setting']=0;
			  $dp['update_date']   =date('Y-m-d H:i:s',time());
			  M('dpc_regist')->where("rfid='$rfid' and rfid_area='$rfid_area' and cur_state=1")->save($dp);
		  }
		  $this->output();
	  }	


/**
 * 查询所有被盗车辆信息
 */
	public function getLostBike(){
		$this->checkNecessaryParams("rfid_area");
		$rfid_area=$this->_post['rfid_area'];
		$lists = M('dpc_regist')->where('rfid_area="'.$rfid_area.'" and cur_state=1 and  alarm_setting=2')->select();
		if(empty($lists)){
			$this->_resp['code']   = '-99';
			$this->_resp['result'] = '该物品不存在';
			$this->output();
		}
       
		$list=array();
		foreach($lists as $key=>$v){
			$list[$key]['bike_type']=$v['attr4'];
			$list[$key]['bike_color']=$v['attr3'];
			$list[$key]['owner_name']=$v['owner_name'];
			$list[$key]['bike_code']=$v['attr1'];
			$list[$key]['phone']=$v['owner_phone1'];
			$list[$key]['rfid']=$v['rfid'];
			$list[$key]['rfid_area']=$v['rfid_area'];
			$list[$key]['photo']=getPicPath($v['id'], "car").M('dpc_regist')->where('rfid="'.$v['rfid'].'" and rfid_area="'.$v['rfid_area'].'"')->getField('photo1');
		}

		$this->_resp['result'] = $list;

		$this->output();
	}


/**
 *	  查询指定车辆的信息
 *		
 **/
		public function getSingleBike(){
			$this->checkNecessaryParams("rfid,rfid_area");
			$rfid  =$this->_post['rfid'];
			$rfid_area  =$this->_post['rfid_area'];
			$info = M("dpc_regist")->where("rfid = '$rfid' and rfid_area = '$rfid_area' and cur_state=1")->find();
			if(empty($info)){
				$this->_resp['code']   = '-99';
				$this->_resp['result'] = '该物品不存在';
				$this->output();
			}
//			$v=M("DpcLostbike")->where("rfid = '$rfid' and rfid_area = '$rfid_area'")->find();

			$list=array();
			$list['bike_type']=$info['attr4'];
			$list['bike_color']=$info['attr3'];
			$list['owner_name']=$info['owner_name'];
			$list['bike_code']=$info['attr1'];
			$list['phone']=$info['owner_phone1'];
			$list['rfid']=$info['rfid'];
			$list['rfid_area']=$info['rfid_area'];
            $list['photo']=getPicPath($info['id'], "car").$info['photo1'];
		
			$this->_resp['result'] = $list;
			$this->output();

		}

/**
 *
 * 查询对应车辆最后出现的位置
 **/
	public function getTraceLocation(){
		$this->checkNecessaryParams("rfid,rfid_area");
		$rfid = $this->_post['rfid'];
		$rfid_area=$this->_post['rfid_area'];
		$location=M('dpc_regist')->where('rfid="'.$rfid.'" and rfid_area="'.$rfid_area.'" and cur_state=1')
                ->field('reader,reader_area,rfid,rfid_area,id,photo1,owner_name,attr1,attr4')
                ->find();
		if(empty($location)){
			$this->_resp['code']   = '-99';
			$this->_resp['result'] = '该物品不存在';
			$this->output();
		}
		$location_id=M('DpcReader')
			->where('reader="'.$location['reader'].'" and reader_area="'.$location['reader_area'].'" and cur_state=1')
			->getField('location_id');
		$location_info=M('dpc_location')->where('id="'.$location_id.'" and cur_state=1')->field('lng,lat')->find();
		$data=array();
		$data['lng']=$location_info['lng'];
		$data['lat']=$location_info['lat'];
		$data['rfid']=$location['rfid'];
		$data['rfid_area']=$location['rfid_area'];
		$data['photo1']=getPicPath($location['id'], "car").$location['photo1'];
		$data['owner_name']=$location['owner_name'];
		$data['bike_code']=$location['attr1'];
		$data['bike_type']=$location['attr4'];
                $data['aa']=$rfid;	
		$this->_resp['result'] = $data;
		$this->output();
	}



        //用户的行驶记录查询
	public function DrivingRecord(){
		$mobile = session('appUser');//记录操作用
		$this->app_getRecord($mobile);
			 $this->checkNecessaryParams("rfid,rfid_area,time,page,start_time,end_time");
  			 $rfid     = $this->_post['rfid'];
			 $rfid_area= $this->_post['rfid_area'];
			 $time_str = $this->_post['time'];
			 $pageinfo = $this->_post['page'];
			 $start_time    =$this->_post['start_time'];
	   	     $end_time     =$this->_post['end_time'];
			 $time     = str_replace('-','',$time_str);
			
                        $area_role=M('user_area_role')->where("rfid_start='$rfid_area'")->find();//查询区域码所在的县，市，区。  		
                	 if(is_null($area_role)){
				 $this->_resp['code']   = '-901';
				 $this->_resp['result'] = '此标签区域码不存在，请查证';
				 $this->output();
			 }
                          
                        if(($rfid==80567628 && $rfid_area==532503) || ($rfid==80147271 && $rfid_area==532503)  ){
			$this->_resp['code'] = '-905';
			$this->_resp['result'] = '根据当地公安局要求，停止该功能的使用，请谅解，谢谢!';
			$this->output();
	          	} 
                        
                        $type = M("dpc_regist")->where("rfid=$rfid and rfid_area=$rfid_area and cur_state=1")->getField("type");  
                        if($rfid_area==522301 || $rfid_area==522302 || $rfid_area==522303 || $rfid_area==522304 || $rfid_area==522305 ||
		           $rfid_area==522306 || $rfid_area==522307 || $rfid_area==522308 || $rfid_area==522309 || $rfid_area==310009 || 
                           
                           $rfid_area==522322 || $rfid_area==522323 || $rfid_area==522324 || $rfid_area==522325 || $rfid_area==522326 ||
			   $rfid_area==522327 || $rfid_area==522328 || $rfid_area==522329 || ($type != 1 && $type != 10)){

                       	 $area_rol=M('user_area_role')->where("district='".$area_role['city']."'")->find();//查询区域码所对应地级市的district,
			 $database='data_'.$area_rol['reader_start'];//数据库名的拼接
			 $data = M($database.".dpc_data".$time)
				 ->where("rfid='$rfid' and rfid_area='$rfid_area' and save_time >= '$start_time' and save_time <= '$end_time'")
				 ->order('save_time desc')
				 ->limit(($pageinfo-1)*10,10)
				 ->select();//分页查询

			if($pageinfo==1 && $data==''){
				$this->_resp['code']   = '-903';
				$this->_resp['result'] = '对不起，暂无您的行驶记录';
				$this->output();
			}elseif(empty($data)){
				$this->_resp['code']   = '-902';
				$this->_resp['result'] = '到底啦，暂无新的记录';
				$this->output();
			}

			 $list=array();
			 foreach($data as $key=>$v){
				 $reader=$v['reader'];
				 $reader_area=$v['reader_area'];
				 $location_id=M('dpc_reader')->where('reader="'.$reader.'" and reader_area="'.$reader_area.'" and cur_state=1')->getField('location_id');
				 $location=M('dpc_location')->where('id="'.$location_id.'" and cur_state=1')->select();
				 $list[$key]['lng']=$location[0]['lng'];
				 $list[$key]['lat']=$location[0]['lat'];
				 $list[$key]['address']=$location[0]['province'].$location[0]['city'].$location[0]['district'].$location[0]['street'];
				 $list[$key]['create_date']=$v['save_time'];
			 }
			 if(empty($list)){
				 $this->_resp['code']   = '-903';
				 $this->_resp['result'] = '对不起，暂无您的行驶记录';
				 $this->output();
			 };
                    }else{
                        $this->_resp['code'] = '-905';
		        $this->_resp['result'] = '根据当地公安局要求，停止该功能的使用，请谅解，谢谢!';
        	        $this->output();
                    }
 //			 $this->_resp['result'] = $list;
			 $this->_resp['result'] = array(
					'list'=>$list,
					'page'=>$pageinfo,
					);
			 $this->output();
		 }




    //手持机地图轨迹
    public function MapTrackHandset(){
        $this->checkNecessaryParams("rfid,rfid_area,page,time,start_time,end_time");
        $rfid          = $this->_post['rfid'];
        $rfid_area     =$this->_post['rfid_area'];
        $time_str      =$this->_post['time'];
        $pageinfo      =$this->_post['page'];
        $time          =str_replace('-','',$time_str);
        $start_time    =$this->_post['start_time'];
        $end_time      =$this->_post['end_time'];
        $area_role=M('user_area_role')->where("rfid_start='$rfid_area'")->find();//查询区域码所在的县，市，区。

        if(is_null($area_role)){
            $this->_resp['code']   = '-901';
            $this->_resp['result'] = '此标签区域码不存在，请查证';
            $this->output();
        }
            $area_rol=M('user_area_role')->where("district='".$area_role['city']."'")->find();//查询区域码所对应地级市的district,
            $database='data_'.$area_rol['reader_start'];//数据库名的拼接
            $data=M($database.".dpc_data".$time)
                ->where("rfid='$rfid' and rfid_area='$rfid_area' and save_time >= '$start_time' and save_time <= '$end_time'")
                ->limit((($pageinfo-1)*10)-($pageinfo-1),10)
                ->select();
            //记录总条数
            if($pageinfo==1){
                $size=M($database.".dpc_data".$time)
                    ->where("rfid='$rfid' and rfid_area='$rfid_area' and save_time >= '$start_time' and save_time <= '$end_time'")
                    ->count();
            };
            if($pageinfo==1 && $data==''){
                $this->_resp['code']   = '-903';
                $this->_resp['result'] = '对不起，暂无数据';
                $this->output();
            }elseif(empty($data)){
                $this->_resp['code']   = '-902';
                $this->_resp['result'] = '到底啦，暂无新的记录';
                $this->output();
            }
            $list=array();
            foreach($data as $key=>$v){
                $reader=$v['reader'];
                $reader_area=$v['reader_area'];
                $location_id=M('dpc_reader')->where('reader="'.$reader.'" and reader_area="'.$reader_area.'" and cur_state=1')->getField('location_id');
                $location   =M('dpc_location')->where('id="'.$location_id.'" and cur_state=1')->select();
                $list[$key]['lng']=$location[0]['lng'];
                $list[$key]['lat']=$location[0]['lat'];
                $list[$key]['address']=$location[0]['province'].$location[0]['city'].$location[0]['district'].$location[0]['street'];
                $list[$key]['create_date']=$v['save_time'];
            }
        
        $this->_resp['result'] = array(
            'list'=>$list,
            'page'=>$pageinfo,
            'size'=>$size,
        );
        $this->output();
    }


//地图轨迹
 public function MapTrack(){
	$this->checkNecessaryParams("rfid,rfid_area,page,time,start_time,end_time");
	$rfid          = $this->_post['rfid'];
	$rfid_area     =$this->_post['rfid_area'];
	$time_str      =$this->_post['time'];
	$pageinfo      =$this->_post['page'];
	$time          =str_replace('-','',$time_str);
	$start_time    =$this->_post['start_time'];
	$end_time      =$this->_post['end_time'];
	$area_role=M('user_area_role')->where("rfid_start='$rfid_area'")->find();//查询区域码所在的县，市，区。

	if(is_null($area_role)){
		$this->_resp['code']   = '-901';
		$this->_resp['result'] = '此标签区域码不存在，请查证';
		$this->output();
	}

          if(($rfid==80567628 && $rfid_area==532503) || ($rfid==80147271 && $rfid_area==532503)  ){
			$this->_resp['code'] = '-905';
			$this->_resp['result'] = '根据当地公安局要求，停止该功能的使用，请谅解，谢谢!';
			$this->output();
		}

     $type = M("dpc_regist")->where("rfid=$rfid and rfid_area=$rfid_area and cur_state=1")->getField("type");
     if( $rfid_area==522301 || $rfid_area==522302 || $rfid_area==522303 || $rfid_area==522304 || $rfid_area==522305 ||
         $rfid_area==522306 || $rfid_area==522307 || $rfid_area==522308 || $rfid_area==522309 || $rfid_area==310009 ||
         
         $rfid_area==522322 || $rfid_area==522323 || $rfid_area==522324 || $rfid_area==522325 || $rfid_area==522326 ||
       	 $rfid_area==522327 || $rfid_area==522328 || $rfid_area==522329 || ($type!= 1 && $type != 10) ){

	$area_rol=M('user_area_role')->where("district='".$area_role['city']."'")->find();//查询区域码所对应地级市的district,
	$database='data_'.$area_rol['reader_start'];//数据库名的拼接
	$data=M($database.".dpc_data".$time)
		->where("rfid='$rfid' and rfid_area='$rfid_area' and save_time >= '$start_time' and save_time <= '$end_time'")
		->limit((($pageinfo-1)*10)-($pageinfo-1),10)
		->select();
	//记录总条数
		if($pageinfo==1){
			$size=M($database.".dpc_data".$time)
				->where("rfid='$rfid' and rfid_area='$rfid_area' and save_time >= '$start_time' and save_time <= '$end_time'")
				->count();
		};
		if($pageinfo==1 && $data==''){
			$this->_resp['code']   = '-903';
			$this->_resp['result'] = '对不起，暂无数据';
			$this->output();
		}elseif(empty($data)){
			$this->_resp['code']   = '-902';
			$this->_resp['result'] = '到底啦，暂无新的记录';
			$this->output();
		}
		$list=array();
		foreach($data as $key=>$v){
			$reader=$v['reader'];
			$reader_area=$v['reader_area'];
			$location_id=M('dpc_reader')->where('reader="'.$reader.'" and reader_area="'.$reader_area.'" and cur_state=1')->getField('location_id');
			$location   =M('dpc_location')->where('id="'.$location_id.'" and cur_state=1')->select();
			$list[$key]['lng']=$location[0]['lng'];
			$list[$key]['lat']=$location[0]['lat'];
			$list[$key]['address']=$location[0]['province'].$location[0]['city'].$location[0]['district'].$location[0]['street'];
			$list[$key]['create_date']=$v['save_time'];
		}
               }else{
                      $this->_resp['code'] = '-905';
	              $this->_resp['result'] = '根据当地公安局要求，停止该功能的使用，请谅解，谢谢!';
	              $this->output();
              }
		$this->_resp['result'] = array(
			'list'=>$list,
			'page'=>$pageinfo,
			'size'=>$size,
		);
		$this->output();
 }

//创建电子围栏
public function addElectronicFence(){
		$mobile = session('appUser');
		$this->app_getRecord($mobile);
	    $this->checkNecessaryParams('rfid,rfid_area,type,name,lng,lati,radius,cur_state');
                   $rfid      =$this->_post['rfid'];
		   $rfid_area =$this->_post['rfid_area'];
		   $type      =$this->_post['type'];
		   $name      =$this->_post['name'];
		   $lng       =$this->_post['lng'];
		   $lati      =$this->_post['lati'];
		   $radius    =$this->_post['radius'];
		   $cur_state =$this->_post['cur_state'];

		if (empty($mobile)) {
			$this->_resp['code']   = '-1';
			$this->_resp['result'] = '请登陆';
			$this->output();
		}
		  $id =M('dpc_regist')
			  ->where("rfid=$rfid and rfid_area=$rfid_area and cur_state=1")
			  ->getField('id');
		   if(is_null($id)) {
			   $this->_resp['code'] = '1767';
			   $this->_resp['result'] = '资产不存在，或已废弃！';
			   $this->output();
		   }elseif($id===false){
			   $this->_resp['code'] = '1064';
			   $this->_resp['result'] = '数据异常！';
			   $this->output();
		   }
		  $data['type']   = $type;                    //此type对应的是dpc_regist表中的type；
		  $data['serial'] = $id;                      //序列号
		  $data['name']   = $name;                    //围栏名称
  		  $data['lng']   = $lng;                    //经度
		  $data['lati']   = $lati;                    //纬度
		  $data['radius'] = $radius;                  //围栏半径
		  $data['cur_state'] = $cur_state;            //状态：开启/关闭
		  $data['create_time'] =date('Y-m-d H:i:s',time());
                  $data['update_time'] =date('Y-m-d H:i:s',time());
                  $dpc_name=M("electric_fence")->where("serial=$id and name='$name'")->find();
		  if(!empty($dpc_name)){
			  $this->log(M("electric_fence")->getLastSQL());
			  $this->_resp['code'] = '-3';
			  $this->_resp['result'] = "同一资产下，围栏名称不能相同!";
			  $this->output();
		  }

		$electric_id = M('electric_fence')->data($data)->add();
		if(empty($electric_id)){
			$this->log(M("DpcRegist")->getLastSQL());
			$this->_resp['code'] = '-2';
			$this->_resp['result'] = "添加围栏失败!";
			$this->output();
		}else{
			$this->log(M("electric_fence")->getLastSQL());
			$info=M('electric_fence')->where("serial=$id and name='$name'")->getField('id');
			$this->_resp['result']=$info;//添加成功返回围栏的id
		}
		$this->output();
 	}

//查看电子围栏
public function getElectric(){
	$mobile = session('appUser');
	$this->app_getRecord($mobile);
	$this->checkNecessaryParams('rfid,rfid_area');
	$rfid = $this->_post['rfid'];
	$rfid_area = $this->_post['rfid_area'];

	if (empty($mobile)) {
		$this->_resp['code']   = '-1';
		$this->_resp['result'] = '请登陆';
		$this->output();
	}
	$dpc_assets=M('dpc_regist')->where("rfid=$rfid and rfid_area=$rfid_area and cur_state=1")->getField('id');
	$info= M('electric_fence')->where("serial=$dpc_assets")->field('id,name,lng,lati,radius,cur_state')->select();
	if(empty($info)){
		$this->_resp['code'] = '-2';
		$this->_resp['result'] = "没有围栏信息!";
                $this->output();
	}
           $list=array();
           foreach($info as $key=>$val){
                   $list[$key]['Electric_id']=$val['id'];
		   $list[$key]['name']       =$val['name'];
		   $list[$key]['lng']        =$val['lng'];
		   $list[$key]['lati']       =$val['lati'];
		   $list[$key]['radius']     =$val['radius'];
		   $list[$key]['cur_state']  =$val['cur_state'];
	 }
	
	$this->_resp['result']=$list;
	$this->output();
}

//编辑电子围栏
public function  editElectric(){
	$mobile = session('appUser');
	$this->app_getRecord($mobile);
	$this->checkNecessaryParams('id,name,lng,lati,radius');
	$id = $this->_post['id'];
        $name = $this->_post['name'];
	$lng = $this->_post['lng'];
	$lati =  $this->_post['lati'];
	$radius = $this->_post['radius'];
	if (empty($mobile)) {
		$this->_resp['code']   = '-1';
		$this->_resp['result'] = '请登陆';
		$this->output();
	}

        $se_info['name'] = $name;
	$se_info['lng'] = $lng;
	$se_info['lati'] = $lati;
	$se_info['radius'] = $radius;
	$se_info['update_time'] = date('Y-m-d H:i:s',time());
       
        $dpc_serial=M("electric_fence")->where("id='$id'")->getField('serial');
	$radiuss =M("electric_fence")->where("serial='$dpc_serial'")->select();
	
	foreach($radiuss as $key=>$val){
		 if($name==$val['name'] && $id !=$val['id']){
			 $this->_resp['code'] = '-3';
			 $this->_resp['result'] = "同一资产下，围栏名称不能相同!";
              $this->output();
		 }
	}

	$edit = M('electric_fence')->where("id=$id")->save($se_info);
	if($edit===false){
		$this->_resp['code']   = '-2';
		$this->_resp['result'] = '更新失败！';
		$this->output();
	}
	$this->output();
}

//围栏开关
public  function on(){
	$this->checkNecessaryParams('id,cur_state');
	$id = $this->_post['id'];
	$cur_state = $this->_post['cur_state'];

	$seve['cur_state']=$cur_state;
	$seve['update_time']=date('Y-m-d H:i:s',time());
	$id_info = M('electric_fence')->where("id=$id")->save($seve);
	if($id_info===false){
		$this->_resp['code']   = '-2';
		$this->_resp['result'] = '操作失败！';
		$this->output();
	}
	$this->output();
}



   //手持机-版本更新
	public function appUpdate()
	{
		$response = array(); //定义JSON响应数组
		$this->checkNecessaryParams('version');
		$myFile = file_get_contents("./serialport.txt") or die("Unable to open file!");
		$this->log("版本:" . $myFile);
		//判断是否获取到所需的输入
        $version=$_POST['version'];
		if (isset($version)) {
			$uploadver =$version;
			$this->log("请求参数:" . $uploadver);
			if ($uploadver != $myFile) {
				$response ["success"] = 1;
				$response ["message"] = $msg;
				$response ['data'] = "http://anju.zwtapp.win:81/anju/Web/download/serialport.apk";
				$this->_resp['result'] = $response;
				$this->output();
			} else {
				$response ["success"] = 0;
				$response ["message"] = "您的软件版本已经是最新的了!";
				$response ['data'] = "";
				$this->_resp['result'] = $response;
				$this->output();
			}
		}
	}

}
