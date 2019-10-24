<?php defined('IN_IA') or exit('Access Denied');?><?php  if(empty($item['statusvalue'])) { ?>
    <!--未付款-->
          
    <?php if(cv('order.op.pay')) { ?>
        <?php  if($item['paytypevalue']==3) { ?>
        <div>
        	   <input class='addressdata' type='hidden' value='<?php  echo json_encode($item['addressdata'])?>'  />
                                <input class='itemid'  type='hidden' value="<?php  echo $item['id'];?>"  />
                                 <a class="btn btn-primary btn-sm disbut" href="javascript:;" onclick="send(this)" data-toggle="modal" data-target="#modal-confirmsend">确认发货</a>
        						 </div>
    <?php  } else { ?>
        <a class="btn btn-primary btn-sm disbut" href="<?php  echo $this->createWebUrl('order', array('op' => 'deal','to'=>'confirmpay','id' => $item['id']))?>" onclick="return confirm('确认此订单已付款吗？');return false;">确认付款</a>
    <?php  } ?>
        			   
    <?php  } ?>
                    
    <?php  } else if($item['statusvalue'] == 1) { ?>
       <!--已付款-->
        <?php  if(!empty($item['addressid']) ) { ?>
              <!--快递 发货-->
                <?php if(cv('order.op.send')) { ?>
                <div>
                <input class='addressdata' type='hidden' value='<?php  echo json_encode($item['addressdata'])?>'  />
                <input class='itemid'  type='hidden' value="<?php  echo $item['id'];?>"  />
                <a class="btn btn-primary btn-sm disbut" href="javascript:;" onclick="send(this)" data-toggle="modal" data-target="#modal-confirmsend">确认发货</a>
                </div>
                <?php  } ?>
        <?php  } else { ?>
                <?php  if($item['isverify']==1) { ?>
                    <!--核销 确认核销-->
                     <?php if(cv('order.op.verify')) { ?>
                         <a class="btn btn-primary btn-sm disbut" href="<?php  echo $this->createWebUrl('order', array('op' => 'deal','to'=>'confirmsend1','id' => $item['id']))?>" onclick="return confirm('确认核销吗？');return false;">确认核销</a>
                     <?php  } ?>
                <?php  } else { ?>
                    <!--自提 确认取货-->
                      <?php if(cv('order.op.fetch')) { ?>
                         <a class="btn btn-primary btn-sm disbut" href="<?php  echo $this->createWebUrl('order', array('op' => 'deal','to'=>'confirmsend1','id' => $item['id']))?>" onclick="return confirm('确认取货吗？');return false;">确认取货</a>
                    <?php  } ?>
                <?php  } ?>
        
        <?php  } ?>
        

    <?php  } else if($item['statusvalue'] == 2) { ?>
        <?php  if($item['special'] == 1) { ?>
        <!-- 特殊商品处理 -->
            <!--已发货-->
            <?php  if(!empty($item['addressid'])) { ?>
                <!--快递 取消发货-->
                <?php if(cv('order.op.sendcancel')) { ?>
                    <a class="btn btn-danger btn-sm disbut" href="javascript:;" onclick="$('#modal-cancelsend').find(':input[name=id]').val('<?php  echo $item['id'];?>')" data-toggle="modal" data-target="#modal-cancelsend">取消发货</a>
                <?php  } ?>
                <?php if(cv('order.op.finish')) { ?>
                    <?php  if($item['special_status'] == 'w1') { ?>
                        <a class="btn btn-primary btn-sm disbut" href="<?php  echo $this->createWebUrl('order', array('op' => 'deal','to'=>'take1','id' => $item['id']))?>" onclick="return confirm('帮助用户第一次收货');return false;">帮助用户第一次收货</a>
                    <?php  } else if($item['special_status'] == 'm1') { ?>
                        <a class="btn btn-primary btn-sm disbut" href="<?php  echo $this->createWebUrl('order', array('op' => 'deal','to'=>'confirmsend2','id' => $item['id']))?>" onclick="return confirm('您确定要第二次发货？');return false;">第二次发货</a>
                    <?php  } else if($item['special_status'] == 'w2') { ?>
                        <a class="btn btn-primary btn-sm disbut" href="<?php  echo $this->createWebUrl('order', array('op' => 'deal','to'=>'take2','id' => $item['id']))?>" onclick="return confirm('帮助用户第二次收货');return false;">帮助用户第二次收货</a>
                    <?php  } else if($item['special_status'] == 'm2') { ?>
                        <a class="btn btn-primary btn-sm disbut" href="<?php  echo $this->createWebUrl('order', array('op' => 'deal','to'=>'confirmsend3','id' => $item['id']))?>" onclick="return confirm('您确定要第3次发货？');return false;">第3次发货(最后一次发货)</a>
                    <?php  } else if($item['special_status'] == 'w3') { ?>
                        <a class="btn btn-primary btn-sm disbut" href="<?php  echo $this->createWebUrl('order', array('op' => 'deal','to'=>'finish','id' => $item['id']))?>" onclick="return confirm('确认订单收货吗？');return false;">确认订单收货吗？</a>
                    <?php  } ?>
                <?php  } ?>
            <?php  } ?>
        <?php  } else { ?>
        <!-- 普通商品处理 -->
            <!--已发货-->
            <?php  if(!empty($item['addressid'])) { ?>
                <!--快递 取消发货-->
               <?php if(cv('order.op.sendcancel')) { ?>
                  <a class="btn btn-danger btn-sm disbut" href="javascript:;" onclick="$('#modal-cancelsend').find(':input[name=id]').val('<?php  echo $item['id'];?>')" data-toggle="modal" data-target="#modal-cancelsend">取消发货</a>
               <?php  } ?>
               <?php if(cv('order.op.finish')) { ?>
                   <a class="btn btn-primary btn-sm disbut" href="<?php  echo $this->createWebUrl('order', array('op' => 'deal','to'=>'finish','id' => $item['id']))?>" onclick="return confirm('确认订单收货吗？');return false;">确认收货</a>
          
               <?php  } ?>
            <?php  } ?>
        <?php  } ?>
                        
<?php  } else if($item['statusvalue'] == 3) { ?>

<?php  } ?>
