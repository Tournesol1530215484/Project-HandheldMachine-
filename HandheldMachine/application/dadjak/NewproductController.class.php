<?php
namespace App\Controller;
use Think\Controller;
class NewproductController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
    }

#个人反馈处理
public function feed_back(){
        $this->checkNecessaryParams('mobile,start_time,end_time');
        $mobile = $this->_post['mobile'];
        $start_time=$this->_post['start_time'];
        $end_time  =$this->_post['end_time'];
       // $pageinfo  =$this->_post['page'];

        $data=M('app_regist')
            ->table('app_regist R')
            ->join('app_feedback F on R.mobile=F.mobile')
            ->where('R.mobile="'.$mobile.'" and F.mobile="'.$mobile.'" and createtime >= "'.$start_time.'" and createtime <="'.$end_time.'"')
         //   ->limit((($pageinfo-1)*10)-($pageinfo-1),10)
            ->field('F.id,name,content,createtime,reply,reply_date,sign')
            ->order('id desc')
            ->select();
        if(empty($data)){
            $this->_resp['code'] = '-99';
            $this->_resp['result'] ='无数据，建议扩大查询时间';
            $this->output();
        }
        $data_info=array();
        foreach($data as $key=>$val){
            $data_info[$key]['name']      =$val['name'];//用户姓名
            $data_info[$key]['content']   =$val['content'];//留言内容
            $data_info[$key]['createtime']=$val['createtime'];//留言时间
            $data_info[$key]['reply']     =$val['reply'];//回复内容
            $data_info[$key]['reply_date']=$val['reply_date'];//回复时间
            $data_info[$key]['sign']      =$val['sign'];//是否回复标记
        }
        $this->_resp['result'] = $data_info;
        $this->output();
    }


  #反馈处理--全部反馈处理
  public function feedBack(){
         $this->checkNecessaryParams('start_time,end_time,page');
         $start_time=$this->_post['start_time'];
         $end_time  =$this->_post['end_time'];
         $pageinfo  =$this->_post['page'];
         $data=M('app_feedback')
               ->where('createtime >= "'.$start_time.'" and createtime <="'.$end_time.'"')
               ->limit((($pageinfo-1)*10)-($pageinfo-1),10)
               ->field('id,mobile,content,createtime,reply,reply_date,sign')
               ->order('id desc')
               ->select();
         if(empty($data)){
             $this->_resp['code'] = '-99';
             $this->_resp['result'] ='无数据，建议扩大查询时间';
             $this->output();
         }
         $data_info=array();
         foreach($data as $key=>$val){
             $app_name=M('app_regist')->where('mobile="'.$val['mobile'].'"')->getField('name');
             $data_info[$key]['name']      =$app_name;//用户姓名
             $data_info[$key]['content']   =$val['content'];//留言内容
             $data_info[$key]['createtime']=$val['createtime'];//留言时间
             $data_info[$key]['reply']     =$val['reply'];//回复内容
             $data_info[$key]['reply_date']=$val['reply_date'];//回复时间
             $data_info[$key]['sign']      =$val['sign'];//是否回复标记
         }
         $this->_resp['result'] = $data_info;
         $this->output();
     }   

 
    //参数：mobile
    public function pushRecord()
    {
        $mobile =$this->_post['mobile'];
        $this->app_getRecord($mobile);
        $data = M("ios_message")->db(1,C('DB_CONFIG1'))->where("mobile='" . $mobile . "' and DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(time)")
            //->limit((($pageinfo-1)*50)-($pageinfo-1),50)
           ->field('message,time')
            ->select();
        if ($data == '' && $data !== false) {
            $this->_resp['code'] = '-903';
            $this->_resp['result'] = '暂无记录';
            $this->output();
        }
        $this->_resp['result'] = $data;
        $this->output();
    }

    /*
     * 添加用户反馈
     * 参数：mobile,content
     */
    public function addFeedBack(){
        $this->checkNecessaryParams('content,mobile');
        $mobile = $this->_post['mobile'];
        $this->app_getRecord($mobile);
        if (empty($mobile)) {
            $this->_resp['code']   = '-1';
            $this->_resp['result'] = '请登陆';
            $this->output();
        }
        $content    = $this->_post['content'];

        if (!is_mobile($mobile)) {
            $this->_resp['code']   = '0011';
            $this->_resp['result'] = '手机号错误,请检查后确认';
            $this->output();
        }

        $data = array();
        $data['mobile']  = $mobile;
        $data['content'] = $content;
        $data['createtime'] = date("Y-m-d H:i:s",time());
        if (!M('AppFeedback')->add($data)) {
            $this->_resp['code']   = '0021';
            $this->_resp['result'] = '写入失败';
        }
        $this->output();
    }

  /*
   * ios获取推送缓存消息----暂时关闭
   */
    /*
       public function getMessage(){
          $mobile = $this->_post['mobile'];
          $this->checkNecessaryParams('number');
          $id   = $this->_post['number'];
  //	    $message = M("ios_message")->where("mobile = '$mobile'")->order('id desc')->find();
          $message = M("ios_message")->where("mobile = '$mobile'")->order('id desc')->limit($id)->select();
          $this->_resp['result'] = $message;
          $this->output();

      }*/


    /*
     * 个人中心--已优化
     * 参数：mobile
     */
    public function myInfo()
    {
        $mobile = $this->_post['mobile'];
        $this->app_getRecord($mobile);
        $info = M("app_regist")->where("mobile = '$mobile'")->field("name,mobile,id_code")->find();
        $this->_resp['result'] = $info;
        $this->output();
    }

    //根据车辆类型选择保险条款-----无session---已优化
    //参数：mobile
    public function InsClauses()
    {
        $mobile =$this->_post['mobile'];
        $this->app_getRecord($mobile);
        $id_code = M("app_regist")->where("mobile=$mobile")->find();
        $label = M("dpc_regist")->where("owner_phone1=$mobile and owner_id='" . $id_code['id_code'] . "' and cur_state=1")
            ->field('rfid_area')
            ->select();
         //如果用户账号下没有资产
        if($label=='' || $label==false){
            $this->_resp['result'] = 'false';
            $this->output();
        }
        foreach ($label as $key => $v) {
               if ($v['rfid_area'] == CITY_AREA_ZS) {//广东中山市
                $pro_nature = 'true';
                break;
            } else if (
                $v['rfid_area']==CITY_AREA_XN01 || $v['rfid_area']==CITY_AREA_XN21 || $v['rfid_area']==CITY_AREA_XN22 ||
                $v['rfid_area']==CITY_AREA_XN23 || $v['rfid_area']==CITY_AREA_XN24 || $v['rfid_area']==CITY_AREA_XN25
            ) {//湖北咸宁市
                $pro_nature = 'true';
                break;
            } else if (
                $v['rfid_area']==CITY_AREA_QY01 || $v['rfid_area']==CITY_AREA_QY02 || $v['rfid_area']==CITY_AREA_QY21 ||
                $v['rfid_area']==CITY_AREA_QY22 || $v['rfid_area']==CITY_AREA_QY23 || $v['rfid_area']==CITY_AREA_QY24 ||
                $v['rfid_area']==CITY_AREA_QY25 || $v['rfid_area']==CITY_AREA_QY26
            ) {//广东清远市
                $pro_nature = 'true';
                break;
            }else if(
                $v['rfid_area']==CITY_AREA_YF01 || $v['rfid_area']==CITY_AREA_YF02 || $v['rfid_area']==CITY_AREA_YF03 ||
                $v['rfid_area']==CITY_AREA_YF04 || $v['rfid_area']==CITY_AREA_YF05
            ){//广东云浮市
                $pro_nature = 'true';
                break;
            }else if(
                $v['rfid_area']==CITY_AREA_ZQ01 || $v['rfid_area']==CITY_AREA_ZQ02 || $v['rfid_area']==CITY_AREA_ZQ23 ||
                $v['rfid_area']==CITY_AREA_ZQ24 || $v['rfid_area']==CITY_AREA_ZQ25 || $v['rfid_area']==CITY_AREA_ZQ26 ||
                $v['rfid_area']==CITY_AREA_ZQ27 || $v['rfid_area']==CITY_AREA_ZQ28
            ){//广东肇庆市
                $pro_nature = 'true';
                break;
            }else if( //黔西南州 只能是摩托车类型可以充值
                ($v['rfid_area']==CITY_AREA_QXN01||$v['rfid_area']==CITY_AREA_QXN22||$v['rfid_area']==CITY_AREA_QXN23 ||
                $v['rfid_area']==CITY_AREA_QXN24||$v['rfid_area']==CITY_AREA_QXN25||$v['rfid_area']==CITY_AREA_QXN26 ||
                $v['rfid_area']==CITY_AREA_QXN27||$v['rfid_area']==CITY_AREA_QXN28||$v['rfid_area']==CITY_AREA_QXN29 )
            ){
                $list[$k]['region'] = 'true';
            } else {
                $pro_nature = 'false';
            }   
        }

        $this->_resp['result'] = $pro_nature;
        $this->output();

    }

    //保险充值记录查询--已优化
    //参数：mobile
     public  function insRecord(){
		$mobile =$this->_post['mobile'];
		$this->app_getRecord($mobile);
		if(empty($mobile)){
			$this->_resp['code'] = '-1';
			$this->_resp['result'] = "请登录!";
			$this->output();
		}
		$id_code = M("app_regist")->where("mobile=$mobile")->find();
		$label   = M("dpc_regist")->where("owner_phone1=$mobile and owner_id='".$id_code['id_code']."' and cur_state=1 and attr11 !=0 and attr12 !=0 ")
			->field('id,rfid,rfid_area,name,owner_name,attr12')
			->select();

		$data_weipay=array();
		$data_pay=array();
                $int = 0;
		foreach($label as $keys=>$v){
			//支付宝充值记录
			$alipay = M("alipay_records")->where('rfid="'.$v['rfid'].'" and rfid_area="'.$v['rfid_area'].'" and (trade_status="TRADE_SUCCESS" || trade_status="TRADE_FINISHED")')
				->field('gmt_payment,type,total_amount,reserve_1,term')
				->select();
				foreach($alipay as $keys=>$val){
			        	$data_pay[$int]['timeStamp']    =$val['gmt_payment']; //保险购买时间；
					$data_pay[$int]['dpcNickName']  =$v['name'] ;         //被保险资产昵称；
					$data_pay[$int]['policyHolders']=$v['owner_name'] ;   //投保人姓名；
					$data_pay[$int]['insType']      =$val['type']."盗抢险";//保险类型----盗抢险
					$data_pay[$int]['insPay']       =$val['total_amount']; //保险费用
					$data_pay[$int]['reserve_1']    =$val['reserve_1'];    //保单号
					$get_payment=substr($val['term'],0,10);
					$data_pay[$int]['insStartTS']   =$get_payment;         //保险起始时间
					$aa=strstr($val['term'],'--');//截取逗号后的字符串
					$get_end=substr($aa,2,10);
					$data_pay[$int]['insEndTS']      =$get_end;      //保险结束时间
                                        $data_pay[$int]['id']         =$v['id'];
					$data_pay[$int]['rfid']          =$v['rfid'];
					$data_pay[$int]['rfid_area']     =$v['rfid_area'];
					$int++;

			}


                        //微信充值记录
			$weipay = M("weipay_records")->where('rfid="'.$v['rfid'].'" and rfid_area="'.$v['rfid_area'].'" and  result_code="SUCCESS"')
				->field('time_end,type,total_fee,reserve_1,term')
				->select();

			foreach($weipay as $keys=>$val){
			        $date=strtotime($val['time_end']) ;
				$date_time=date('Y-m-d H:i:s',$date);
				$data_weipay[$int]['timeStamp']    =$date_time;           //保险购买时间；
				$data_weipay[$int]['dpcNickName']  =$v['name'] ;          //被保险资产昵称；
				$data_weipay[$int]['policyHolders']=$v['owner_name'] ;    //投保人姓名；
				$data_weipay[$int]['insType']      =$val['type']."盗抢险"; //保险类型----盗抢险
				$data_weipay[$int]['insPay']       =($val['total_fee']/100)."";    //保险费用
				$data_weipay[$int]['reserve_1']    =$val['reserve_1'];    //保单号
				$date_TS=substr($val['term'],0,10);
				$data_weipay[$int]['insStartTS']   = $date_TS;            //保险起始时间
				$aa=strstr($val['term'],'--');//截取逗号后的字符串
				$get_end=substr($aa,2,10);
				$data_weipay[$int]['insEndTS']     =$get_end ;       //保险结束时间
                                $data_weipay[$int]['id']         =$v['id'];
				$data_weipay[$int]['rfid']         =$v['rfid'];
				$data_weipay[$int]['rfid_area']    =$v['rfid_area'];
				$int++;
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
                  
 

    //保单凭证---已优化
    //参数：mobile,reserve_1,id
    public function detailsPolicy()
    {
        $mobile = $this->_post['mobile'];
        $this->app_getRecord($mobile);
        $reserve_1 =$this->_post['reserve_1'];//保单号
        $id       = $this->_post['id'];

        $label = M("dpc_regist")->where(" id=$id  and cur_state=1 ")
            ->field('owner_name,owner_phone1,owner_id,rfid,attr1,type,attr6,attr5,attr12,rfid_area')
            ->find();
        $data = array();
        $data['owner_name'] = $label['owner_name'];   //投保人
        $data['mobile'] = $label['owner_phone1']; //手机号
        $data['owner_id'] = $label['owner_id'];     //身份证
        $data['rfid'] = $label['rfid'];
        $data['plateNumber'] = $label['attr1']; //车牌号
        if ($label['type'] == '1') {
            $data['frameNumber'] = $label['attr6']; //电瓶车车牌号
        } else if ($label['type'] == '10') {
            $data['frameNumber'] = $label['attr5']; //摩托车车架号
        }

        $this->_resp['result'] = $data;
        $this->output();
    }


    /*
     * 主页列表
     * 参数：mobile
     */
    public function getList(){
//	$mobile ='18040584095';
    $mobile =$this->_post['mobile'];
        $this->app_getRecord($mobile);
        if(empty($mobile)){
            $this->_resp['code'] = '-1';
            $this->_resp['result'] = "请登录!";
            $this->output();
        }

        $id_code = M("AppRegist")->where("mobile = '$mobile'")->getField("id_code");
	
        $Dpc = M("DpcRegist")->where("owner_id = '$id_code' and owner_phone1='$mobile'  and cur_state=1")->field("id,rfid,rfid_area,name,photo1,type,alarm_setting,reader,reader_area,location_update_datetime,attr11,fk_date,attr12")->select();
        $this->log("Dpc:".M("DpcRegist")->getLastSQL());
        //共享资产的信息
        $applink = M("dpc_regist")
            ->db(1,C('DB_CONFIG1'))
            ->table("dpc_regist dr,app_link al")
            ->where("al.rfid_mobile = '$mobile' and dr.rfid = al.rfid and dr.rfid_area = al.rfid_area and al.cur_state = 1 and dr.cur_state=1")
            ->field("dr.id,dr.rfid,dr.rfid_area,dr.name,dr.photo1,dr.type,dr.alarm_setting,dr.reader,dr.reader_area,dr.location_update_datetime,dr.attr11,dr.fk_date,dr.attr12")
            ->select();
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
                 $fk_time_ss=$v['attr12'];
                //当前时间
                $date = date("Y-m-d", time());
                $list[$k]['attr11']=$v['attr11'];
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
                    if(strtotime($fk_time_ss)>strtotime($date)){//到期时间<当前时间=过期
                        //剩余时间
                        $cycle_date = strtotime($fk_time_ss) - strtotime($date);
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
          //  if($v['rfid_area']==CITY_AREA_ZS){//广东省中山市
	 if(($v['rfid_area']==CITY_AREA_ZS)&&($v['type']==10)){//广东中山市
                $list[$k]['region'] = 'true';
            }else if(
                $v['rfid_area']==CITY_AREA_QY01 || $v['rfid_area']==CITY_AREA_QY02 || $v['rfid_area']==CITY_AREA_QY21 ||
                $v['rfid_area']==CITY_AREA_QY22 || $v['rfid_area']==CITY_AREA_QY23 || $v['rfid_area']==CITY_AREA_QY24 ||
                $v['rfid_area']==CITY_AREA_QY25 || $v['rfid_area']==CITY_AREA_QY26
            ){//广东省清远市
                $list[$k]['region'] = 'true';
            }else if(
                $v['rfid_area']==CITY_AREA_XN01 || $v['rfid_area']==CITY_AREA_XN21 || $v['rfid_area']==CITY_AREA_XN22 ||
                $v['rfid_area']==CITY_AREA_XN23 || $v['rfid_area']==CITY_AREA_XN24 || $v['rfid_area']==CITY_AREA_XN25
            ){//湖北咸宁市
                $list[$k]['region'] = 'true';
            }else if(
                $v['rfid_area']==CITY_AREA_YF01 || $v['rfid_area']==CITY_AREA_YF02 || $v['rfid_area']==CITY_AREA_YF03 ||
                $v['rfid_area']==CITY_AREA_YF04 || $v['rfid_area']==CITY_AREA_YF05
            ){//广东省云浮市
                $list[$k]['region'] = 'true';
            }else if(
                $v['rfid_area']==CITY_AREA_ZQ01 || $v['rfid_area']==CITY_AREA_ZQ02 || $v['rfid_area']==CITY_AREA_ZQ23 ||
                $v['rfid_area']==CITY_AREA_ZQ24 || $v['rfid_area']==CITY_AREA_ZQ25 || $v['rfid_area']==CITY_AREA_ZQ26 ||
                $v['rfid_area']==CITY_AREA_ZQ27 || $v['rfid_area']==CITY_AREA_ZQ28
            ){//广东省肇庆市
                $list[$k]['region'] = 'true';
            }else if( //黔西南州 只能是摩托车类型可以充值
                ($v['rfid_area']==CITY_AREA_QXN01||$v['rfid_area']==CITY_AREA_QXN29||$v['rfid_area']==CITY_AREA_QXN22 ||
                $v['rfid_area']==CITY_AREA_QXN24||$v['rfid_area']==CITY_AREA_QXN25||$v['rfid_area']==CITY_AREA_QXN23||
                $v['rfid_area']==CITY_AREA_QXN27||$v['rfid_area']==CITY_AREA_QXN28||$v['rfid_area']==CITY_AREA_QXN26)&&($v['type']==10)
            ){
                $list[$k]['region'] = 'true';
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

    public function getList2(){
        //$mobile =$this->_post['mobile'];
        $mobile='18337613925';
        $this->app_getRecord($mobile);
        if(empty($mobile)){
            $this->_resp['code'] = '-1';
            $this->_resp['result'] = "请登录!";
            $this->output();
        }

        $id_code = M("AppRegist")->where("mobile = '$mobile'")->getField("id_code");
        $Dpc = M("DpcRegist")->where("owner_id = '$id_code' and owner_phone1='$mobile'  and cur_state=1")->field("id,rfid,rfid_area,name,photo1,type,alarm_setting,reader,reader_area,location_update_datetime,attr11,fk_date,attr12")->select();
        $this->log("Dpc:".M("DpcRegist")->getLastSQL());
        //共享资产的信息
        $applink = M("dpc_regist")

            ->table("dpc_regist dr,app_link al")
            ->where("al.rfid_mobile = '$mobile' and dr.rfid = al.rfid and dr.rfid_area = al.rfid_area and al.cur_state = 1 and dr.cur_state=1")
            ->field("dr.id,dr.rfid,dr.rfid_area,dr.name,dr.photo1,dr.type,dr.alarm_setting,dr.reader,dr.reader_area,dr.location_update_datetime,dr.attr11,dr.fk_date,dr.attr12")
            ->select();

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
            $a= "+" . $v['attr11'];//使用月数  11
            $create_date = $v['fk_date'];//创建时间
            //计算标签的到期时间
            // $fk_time = date("Y-m-d", strtotime("$a month", strtotime($create_date)));
            if(empty($v['attr12'])){
                $fk_time = date("Y-m-d", strtotime("$a month", strtotime($create_date)));
            }else{
                $fk_time=date("Y-m-d", strtotime($v['attr12']));
            }
            //  $fk_time_ss=$v['attr12'];
            //当前时间
            $date = date("Y-m-d", time());
            $list[$k]['attr11']=$v['attr11'];
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
            //    if($v['rfid_area']==CITY_AREA_ZS){//广东省中山市
            if(($v['rfid_area']==CITY_AREA_ZS)&&($v['type']==10)){//广东省中山市
                $list[$k]['region'] = 'true';
            }else if(
                $v['rfid_area']==CITY_AREA_QY01 || $v['rfid_area']==CITY_AREA_QY02 || $v['rfid_area']==CITY_AREA_QY21 ||
                $v['rfid_area']==CITY_AREA_QY22 || $v['rfid_area']==CITY_AREA_QY23 || $v['rfid_area']==CITY_AREA_QY24 ||
                $v['rfid_area']==CITY_AREA_QY25 || $v['rfid_area']==CITY_AREA_QY26
            ){//广东省清远市
                $list[$k]['region'] = 'true';
            }else if(
                $v['rfid_area']==CITY_AREA_XN01 || $v['rfid_area']==CITY_AREA_XN21 || $v['rfid_area']==CITY_AREA_XN22 ||
                $v['rfid_area']==CITY_AREA_XN23 || $v['rfid_area']==CITY_AREA_XN24 || $v['rfid_area']==CITY_AREA_XN25
            ){//湖北咸宁市
                $list[$k]['region'] = 'true';
            }else if(
                $v['rfid_area']==CITY_AREA_YF01 || $v['rfid_area']==CITY_AREA_YF02 || $v['rfid_area']==CITY_AREA_YF03 ||
                $v['rfid_area']==CITY_AREA_YF04 || $v['rfid_area']==CITY_AREA_YF05
            ){//广东省云浮市
                $list[$k]['region'] = 'true';
            }else if(
                $v['rfid_area']==CITY_AREA_ZQ01 || $v['rfid_area']==CITY_AREA_ZQ02 || $v['rfid_area']==CITY_AREA_ZQ23 ||
                $v['rfid_area']==CITY_AREA_ZQ24 || $v['rfid_area']==CITY_AREA_ZQ25 || $v['rfid_area']==CITY_AREA_ZQ26 ||
                $v['rfid_area']==CITY_AREA_ZQ27 || $v['rfid_area']==CITY_AREA_ZQ28
            ){//广东省肇庆市
                $list[$k]['region'] = 'true';
            }else if( //黔西南州 只能是摩托车类型可以充值
                ($v['rfid_area']==CITY_AREA_QXN01||$v['rfid_area']==CITY_AREA_QXN29||$v['rfid_area']==CITY_AREA_QXN22 ||
                    $v['rfid_area']==CITY_AREA_QXN24||$v['rfid_area']==CITY_AREA_QXN25||$v['rfid_area']==CITY_AREA_QXN23||
                    $v['rfid_area']==CITY_AREA_QXN27||$v['rfid_area']==CITY_AREA_QXN28||$v['rfid_area']==CITY_AREA_QXN26)&&($v['type']==10)
            ){
                $list[$k]['region'] = 'true';
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


    /*
     * 设防/取消---加参数id---已优化
     * 参数：mobile,id,rfid,rfid_area,type,link
     */
    public function elecWall(){
        $mobile =$this->_post['mobile'];
        $id     =$this->_post['id'];
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

        $regist_reader=M('DpcRegist')->where('id="'.$id.'" and cur_state =1')->field('reader,reader_area,id,location_update_datetime')->find();
        $reader=$regist_reader['reader'];
        $reader_area=$regist_reader['reader_area'];

//end
        //操作记录
        if($type == 0){
            //添加代码
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
                M("DpcRegist")->where("id=$id and cur_state =1")->save($alarm_loc);
            }elseif ($update_datatime <=15){
                $save['alarm_location']=$reader.'_'.$reader_area;
                $save['update_date']   =date('Y-m-d H:i:s',time());
                M("DpcRegist")->where("id=$id and cur_state =1")->save($save);
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
            $id = M("DpcRegist")->where("id='$id' and owner_id = '$old_id_code'  and cur_state=1")->save($save);

            if($type== 0){
                M('DpcRegist')->where('id="'.$id.'" and owner_id="'.$old_id_code.'"  and cur_state=1')->save($alarm_loc);//撤防时，将alarm_loction清空
            }

            if(empty($id)){
                $this->_resp['code'] = '-2';
                $this->_resp['result'] = "操作失败!";
                $this->output();
            }
            $reg = M("DpcRegist")->where("id='$id' and owner_id = '$old_id_code' and cur_state=1")->field("rfid,rfid_area,alarm_setting as type")->find();
            $this->_resp['result'] = $reg;

        }else{

            $save['alarm_setting'] = $type;
            $save['update_date']   =date('Y-m-d H:i:s',time());
            $id = M("DpcRegist")->where("id='$id' and owner_id = '$id_code' and cur_state=1")->save($save);
            if(empty($id)){
                $this->_resp['code'] = '-3';
                $this->_resp['result'] = "操作失败!（此物品不属于自己）";
                $this->output();
            }
            $reg = M("DpcRegist")->where("id='$id' and owner_id = '$id_code' and cur_state=1")->field("rfid,rfid_area,alarm_setting as type")->find();
            $this->_resp['result'] = $reg;

        }

        $this->output();
    }


    /*
     * 接受/拒绝共享---已优化
     * 参数：mobile,status,rfid_list
     */
    public function acc_link(){
        $mobile =$this->_post['mobile'];
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
            if($status == 1){
                $this->addRecord($mobile, $rfid,$rfid_area,RECORD5);
            }elseif ($status == 2){
                $this->addRecord($mobile, $rfid,$rfid_area,RECORD6);
            }elseif ($status == 3){
                $this->addRecord($mobile, $rfid,$rfid_area,RECORD7);
            }
            $where = "rfid = '$rfid' and rfid_area = '$rfid_area'";
            if($status == 3){
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
                $this->log($id);
                $this->log(M("AppLink")->getLastSQL());
               /* if($id>0){
                    $this->log(M("AppLink")->getLastSQL());
                    $this->_resp['code'] = '-3';
                    $this->_resp['result'] = "操作失败!";
                    $this->output();
                }*/
            }

        }

        $this->output();
    }

    /*
     * 我的报警记录---已优化
     * 参数：mobile
     */
    public function lost_bike(){
        $mobile =$this->_post['mobile'];
        $this->app_getRecord($mobile);
        if(empty($mobile)){
            $this->_resp['code'] = '-1';
            $this->_resp['result'] = "请登录!";
            $this->output();
        }
        $reg = M("AppRegist")->where("mobile = '$mobile'")->field('name,mobile,id_code')->find();
        $name = $reg['name'];
        $mobiles = $reg['mobile'];
        $id_code = $reg['id_code'];

        $lost = M("dpc_lostbike")->where("owner_id = '$id_code' and phone='$mobile'")->field('rfid,rfid_area,report_date')->order('id desc')->select();
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
     * 我的资产列表---已优化
     * 参数：mobile
     */
    public function myList(){
        $mobile =$this->_post['mobile'];
        $this->app_getRecord($mobile);
        if(empty($mobile)){
            $this->_resp['code'] = '-1';
            $this->_resp['result'] = "请登录!";
            $this->output();
        }
        $id_code = M("AppRegist")->where("mobile = '$mobile'")->getField("id_code");
        $Dpc = M("DpcRegist")->where("owner_id = '$id_code' and owner_phone1='$mobile' and cur_state=1")
            ->field('id,rfid,rfid_area,attr3,attr4,name,photo1,alarm_setting,type,reader,reader_area,location_update_datetime')
            ->select();
        $list = array();
        foreach ($Dpc as $k => $v){
            $list[$k]['id'] = $v['id'];
            $list[$k]['rfid'] = $v['rfid'];
            $list[$k]['rfid_area'] = $v['rfid_area'];
            $list[$k]['attr3'] = $v['attr3'];
            $list[$k]['attr4'] = $v['attr4'];
            $list[$k]['name'] = $v['name'];
            $list[$k]['photo'] = getPicPath($v['id'], "car").$v['photo1'];
            $list[$k]['alarm_setting'] = $v['alarm_setting'];
            $list[$k]['type'] = $v['type'];

            $reader = $v['reader'];
            $reader_area = $v['reader_area'];
            $location_id = M("dpc_reader")->where("reader = $reader and reader_area = $reader_area and cur_state=1")->getField("location_id");
          //  if($location_id){
                $location = M("dpc_location")->where("id = $location_id and cur_state=1")->field('province,city,district,street')->find();
                
          // };
             
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
     * 物品详情---已优化
     * 参数：mobile,id,type
     */
    public function getDetial(){
        $mobile =$this->_post['mobile'];
        $this->app_getRecord($mobile);

        if(empty($mobile)){
            $this->_resp['code'] = '-1';
            $this->_resp['result'] = "请登录!";
            $this->output();
        }
        $this->checkNecessaryParams("id,type");
        $id     =$this->_post['id'];
        $type       = isset($this->_post['type'])? $this->_post['type']:1;
        $reg        = M("DpcRegist")->where("id='$id' and type='$type' and cur_state=1")->find();

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
        $location = M("dpc_location")->where("id ='$location_id' and cur_state=1")
            ->field('province,city,district,street')
            ->find();

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
         //if($reg['rfid_area']==CITY_AREA_ZS){//广东中山市
	 if(($reg['rfid_area']==CITY_AREA_ZS)&&($reg['type']==10)){//广东中山市
                $region = 'true';
            }else if(
                     $reg['rfid_area']==CITY_AREA_QY01 || $reg['rfid_area']==CITY_AREA_QY02 || $reg['rfid_area']==CITY_AREA_QY21 ||
                     $reg['rfid_area']==CITY_AREA_QY22 || $reg['rfid_area']==CITY_AREA_QY23 || $reg['rfid_area']==CITY_AREA_QY24 ||
                     $reg['rfid_area']==CITY_AREA_QY25 || $reg['rfid_area']==CITY_AREA_QY26
            ){//广东清远市
                $region = 'true';
            }else if(
                     $reg['rfid_area']==CITY_AREA_XN01 || $reg['rfid_area']==CITY_AREA_XN21 || $reg['rfid_area']==CITY_AREA_XN22 ||
                     $reg['rfid_area']==CITY_AREA_XN23 || $reg['rfid_area']==CITY_AREA_XN24 || $reg['rfid_area']==CITY_AREA_XN25
            ){//湖北咸宁市
                $region = 'true';
            }else if(
                     $reg['rfid_area']==CITY_AREA_YF01 || $reg['rfid_area']==CITY_AREA_YF02 || $reg['rfid_area']==CITY_AREA_YF03 ||
                     $reg['rfid_area']==CITY_AREA_YF04 || $reg['rfid_area']==CITY_AREA_YF05
            ){//广东云浮市
                $region = 'true';
            }else if(
                     $reg['rfid_area']==CITY_AREA_ZQ01 || $reg['rfid_area']==CITY_AREA_ZQ02 || $reg['rfid_area']==CITY_AREA_ZQ23 ||
                     $reg['rfid_area']==CITY_AREA_ZQ24 || $reg['rfid_area']==CITY_AREA_ZQ25 || $reg['rfid_area']==CITY_AREA_ZQ26 ||
                     $reg['rfid_area']==CITY_AREA_ZQ27 || $reg['rfid_area']==CITY_AREA_ZQ28
            ){//广东肇庆市
                $region = 'true';
            }else if( //黔西南州 只能是摩托车类型可以充值
                ($reg['rfid_area']==CITY_AREA_QXN01||$reg['rfid_area']==CITY_AREA_QXN29||$reg['rfid_area']==CITY_AREA_QXN22 ||
                    $reg['rfid_area']==CITY_AREA_QXN24||$reg['rfid_area']==CITY_AREA_QXN25||$reg['rfid_area']==CITY_AREA_QXN23||
                    $reg['rfid_area']==CITY_AREA_QXN27||$reg['rfid_area']==CITY_AREA_QXN28||$reg['rfid_area']==CITY_AREA_QXN26)&&($reg['type']==10)
            ){
                $region= 'true';
            }
	     else{
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
            'attr11'    => $reg['attr11'],
            'last_time' => $reg['location_update_datetime']
        );
        $this->output();
    }



    /*优化
     * 发布共享
     * 参数：user_moible(主人手机号)，name,mobile,list
     */
    public function addLink(){
        $mobile = $this->_post['user_mobile'];
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
       // $del = M("ios_message")->where("mobile = '$rfid_mobile'")->delete();
        $mes_id = M("ios_message")->data($add)->add();
// 		$this->log("缓存消息添加语句：".M("ios_message")->getLastSQL());
        $ios_sound = 'default';
        $Android_title = "共享";
        $Android_value = "您收到一个共享消息";
        $this->log("安卓共享推送内容".$Message);
        $this->addpush($RegistrationId, $Message,$Android_title,$Android_value,$ios_alert,$ios_value,$ios_sound);
        $this->output();
    }



//新版发布共享--优化
//参数：user_mobile,list,mobile,name
    public function new_addLink(){
        $mobile = $this->_post['user_mobile'];
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
            $this->log("list".$v['rfid_area']);
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
//        $del = M("ios_message")->where("mobile = '$rfid_mobile'")->delete();
        $mes_id = M("ios_message")->data($add)->add();
// 		$this->log("缓存消息添加语句：".M("ios_message")->getLastSQL());
        $ios_sound = 'default';
        $Android_title = "共享";
        $Android_value = "您收到一个共享消息";
        $this->addPush_tag($tag,$Android_info, $Android_title, $Android_value, $ios_alert, $ios_value, $ios_sound);
        $this->output();
    }



    /*
     * 共享校验---已优化
     * 参数：mobile,name
     */
    public function checkShared(){
        $mobile = $this->_post['mobile'];
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
        $this->output();
    }

    //参数：mobile
    public  function addProduct(){
        $mobile = $this->_post['mobile'];
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
     * 正式版名称：addProduct_ss（）
     * 注册电动车--优化
     * 参数：mobile,rfid,rfid_area,fk_date,name,owner_name,attr3,attr4,type,attr1
     */
    public function addProduct_ss(){
        $mobile = $this->_post['mobile'];
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
     *添加人物--优化
     * 参数：mobile,rfid,rfid_area,fk_date,name,owner_name,attr8,attr6,type
     *
     */
    public function addPeople(){
        $mobile =$this->_post['mobile'];;
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
    //参数：mobile,rfid,rfid_area,fk_date,name,owner_name,attr3,attr6,attr4,type

    public function addAnimal(){
        $mobile = $this->_post['mobile'];
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


    /*
     * 编辑物品---优化
     * 参数：mobile,rfid,name,rfid_area
     */
    public function edit(){
        $mobile = $this->_post['mobile'];
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
     * 操作记录列表--优化
     * 参数：mobile,rfid,rfid_area
     */
    public function getRecord(){
        $mobile = $this->_post['mobile'];
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
     * 确认/取消报警--优化
     * 参数：mobile,rfid,rfid_area,type
     */
    public function addLostbike(){
        $mobile = $this->_post['mobile'];
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




    //用户的行驶记录查询--优化
    //参数：mobile,rfid,rfid_area,time,page,start_time,end_time
    public function DrivingRecord(){
        $mobile = $this->_post['mobile'];//记录操作用
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
                $location=M('dpc_location')->where('id="'.$location_id.'" and cur_state=1')
                          ->field('lng,lat,province,city,district,street')->select();
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




    //手持机地图轨迹--优化
    //参数：mobile,rfid,rfid_area,page,time,start_time,end_time
    public function MapTrackHandset(){
        $mobile = $this->_post['mobile'];//记录操作用
        $this->app_getRecord($mobile);
        $this->checkNecessaryParams("rfid,rfid_area,page,time,start_time,end_time");
        $rfid          =$this->_post['rfid'];
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
            $location   =M('dpc_location')->where('id="'.$location_id.'" and cur_state=1')
                ->field('lng,lat,province,city,district,street')
                ->select();
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


//地图轨迹--优化
//参数：mobile,rfid,rfid_area,page,time,start_time,end_time
    public function MapTrack(){
        $mobile = $this->_post['mobile'];//记录操作用
        $this->app_getRecord($mobile);
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
                $location   =M('dpc_location')->where('id="'.$location_id.'" and cur_state=1')
                    ->field('lng,lat,province,city,district,street')
                    ->select();
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
//参数：mobile,rfid,rfid_area,type,name,lng,lati,radius,cur_state
    public function addElectronicFence(){
        $mobile = $this->_post['mobile'];
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

//查看电子围栏--优化
 //参数：mobile,rfid,rfid_area
    public function getElectric(){
        $mobile = $this->_post['mobile'];
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

//编辑电子围栏--优化
//参数：mobile,id,name,lng,lati,radius
    public function  editElectric(){
        $mobile =$this->_post['mobile'];
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
        $radiuss =M("electric_fence")->where("serial='$dpc_serial'")->field('name,id')->select();

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


}
