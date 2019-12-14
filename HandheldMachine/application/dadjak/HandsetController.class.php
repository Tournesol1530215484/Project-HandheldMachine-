<?php
namespace App\Controller;
use Think\Controller;
class HandsetController extends BaseController
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 打开APP可以显示出被盗车辆最新出现的地点，同时点击可以知道被盗车辆基本属性和对应车主身份信息
     */
    public function get_TraceLocation(){
        $rfid_area=$this->_post['rfid_area'];
         $location= M('dpc_regist')
            ->where('location_update_datetime >=  NOW() - interval 24 hour and rfid_area="'.$rfid_area.'" and alarm_setting=2 and cur_state=1')
            ->field('rfid,attr4,attr1,owner_name,id,photo1,rfid_area,reader,reader_area,location_update_datetime')
            ->select();
        if(empty($location)){
            $this->_resp['code']   = '-99';
            $this->_resp['result'] = '没有被盗车辆信息';
            $this->output();
        }
        $data=array();
          foreach($location as $key=>$val){
              $location_id=M('DpcReader')
                  ->where('reader="'.$val['reader'].'" and reader_area="'.$val['reader_area'].'" and cur_state=1')
                  ->getField('location_id');
              $location_info=M('dpc_location')->where('id="'.$location_id.'" and cur_state=1')->find();

              if(strtotime(date('Y-m-d H:i:s'),time())-strtotime($val['location_update_datetime'])<3600){
                  $data[$key]['colour']  =1;# "1"表示1小时内出现的车子;
              }else{
                  $data[$key]['colour']  =0;# "0"表示超出1小时出现的车子;
              }
              $data[$key]['lng']=$location_info['lng'];
              $data[$key]['lat']=$location_info['lat'];
              $data[$key]['rfid']=$val['rfid'];
              $data[$key]['rfid_area']=$val['rfid_area'];
              $data[$key]['photo1']=getPicPath($val['id'], "car").$val['photo1'];
              $data[$key]['owner_name']=$val['owner_name'];
              $data[$key]['bike_code']=$val['attr1'];
              $data[$key]['bike_type']=$val['attr4'];

          }

        $this->_resp['result'] = $data;
        $this->output();
    }

    #点击页面按钮，可以计算出来周围1km以内的基站分布情况（带上基站工作状态）；
    public function getBaseStation(){
        $data=M('dpc_reader')
             ->table('dpc_reader R')
            ->join('dpc_location L on R.location_id=L.id')
            ->where('R.cur_state=1 and L.cur_state=1 and R.reader_area=5207')
            ->field('end_time,lng,lat')
            ->select();
           $data_info=array();
        foreach ($data as $key=>$item) {
               if(time()-strtotime($item['end_time'])>90){
                  $data_info[$key]['is_Normal']=0;#is_normal=0表示基站异常
                   $data_info[$key]['lng']=$item['lng'];
                   $data_info[$key]['lat']=$item['lat'];
               }elseif(time()-strtotime($item['end_time'])<90){
                   $data_info[$key]['is_Normal']=1;#is_normal=1表示基站正常
                   $data_info[$key]['lng']=$item['lng'];
                   $data_info[$key]['lat']=$item['lat'];
               }
           }
        $this->_resp['result'] = $data_info;
        $this->output();

    }

    #APP显示24小时以内出现的车辆；1小时以内的车子用红色标识，超出1小时的，用黄颜色显示出来；
     public function getUseOfCar(){
         $location= M('dpc_regist')->where('location_update_datetime >=  NOW() - interval 24 hour and rfid_area=441801  and cur_state=1')
             ->field('reader,reader_area,location_update_datetime')
             ->select();
         if(empty($location)){
             $this->_resp['code']   = '-99';
             $this->_resp['result'] = '24小时内没有车辆出现';
             $this->output();
         }

         $data=array();
         foreach($location as $key=>$val){
             $location_info=M('dpc_reader')
                 ->table('dpc_reader R')
                 ->join('dpc_location L on R.location_id=L.id')
                 ->where('R.cur_state=1 and L.cur_state=1 and R.reader_area="'.$val['reader_area'].'" and R.reader="'.$val['reader'].'"')
                 ->field('lng,lat')
                 ->find();
             $data[$key]['lng']=$location_info['lng'];
             $data[$key]['lat']=$location_info['lat'];
             if(strtotime(date('Y-m-d H:i:s'),time())-strtotime($val['location_update_datetime'])<3600){
                 $data[$key]['colour']  =1;# "1"表示1小时内出现的车子;
             }else{
                 $data[$key]['colour']  =0;# "0"表示超出1小时出现的车子;
             }
         }
         $this->_resp['result'] = $data;
         $this->output();
     }
}
