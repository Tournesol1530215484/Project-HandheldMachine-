<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title>订单详情</title>
<style type="text/css">
    body {margin:0px; background:#efefef; -moz-appearance:none;}

    .detail_topbar {height:85px; background:#00c1ff url(../addons/sz_yi/template/mobile/style1/static/images/phone.png) right top no-repeat; padding:10px;background-size: auto 100%;}
    .detail_topbar .ico {height:50px; width:30px; line-height:50px; float:left; font-size:26px; text-align:center; color:#fff;}
    .detail_topbar .tips {margin-left:10px; font-size:13px; color:#fff; line-height:17px;}
    
    .detail_user {height:64px;  background:#fff; padding:5px; border-bottom:1px solid #eaeaea;}
    .detail_user .info .ico { float:left;  height:50px; width:30px; line-height:50px; font-size:26px; text-align:center; color:#666}
    .detail_user .info .info1 {height:54px; width:100%; float:left;margin-left:-30px;margin-right:-30px;}
    .detail_user .info .info1 .inner { margin-left:30px;margin-right:30px;overflow:hidden;}
    .detail_user .info .info1 .inner .user {height:30px; width:100%; font-size:16px; color:#333; line-height:35px;overflow:hidden;}
    .detail_user .info .info1 .inner .address { width:100%; font-size:14px; color:#999; line-height:20px;margin:0;border:0;}
    .detail_user .info .ico2 {height:50px; width:30px;padding-top:15px; float:right; font-size:16px; text-align:right; color:#999;}

    .detail_exp {height:42px; width:94%; background:#fff; padding:0px 3%; border-bottom:1px solid #eaeaea; line-height:42px; font-size:16px; color:#333;}
    .detail_exp .t1 {height:42px; width:auto; float:left;}
    .detail_exp .t2 {height:42px; width:auto; float:right;}
    .detail_exp .ico {height:42px; width:10%; float:right;text-align:right;color:#999; font-size:16px;margin-top:5px; }
    
    .detail_good {height:auto;background:#fff;  margin-top:16px; border-top:1px solid #eaeaea;}
    .detail_good .ico {height:6px; width:10%; line-height:36px; float:left; text-align:center;}
    .detail_good .shop {height:36px; padding-left:10%; border-bottom:1px solid #e6e6e6; line-height:36px; font-size:18px; color:#333;}
    .detail_good .good {height:70px; width:100%; padding:10px; border-bottom:1px solid #eaeaea;background: #efefef;}
    .detail_good .img {height:50px; width:50px; float:left;}
    .detail_good .img img {height:100%; width:100%;}
    .detail_good .info {width:100%;float:left; margin-left:-50px;margin-right:-60px;}
    .detail_good .info .inner { margin-left:60px;margin-right:80px; }
    .detail_good .info .inner .name {height:32px; width:100%; float:left; font-size:12px; color:#555;overflow:hidden;}
    .detail_good .info .inner .option {height:16px; width:100%; float:left; font-size:12px; color:#888;overflow:hidden;word-break: break-all}
    .detail_good span { color:#666;}
    .detail_good .price { float:right;width:100px;;height:54px;margin-left:-60px;overflow: hidden;}
    .detail_good .price .pnum { height:20px;width:100%;text-align:right;font-size:14px; }
    .detail_good .price .num { height:20px;width:100%;text-align:right;}
    
    .detail_price {height:auto; padding:10px; padding-bottom:20px;  background:#fff;   }
    .detail_price .price {height:auto; width:100%; }
    .detail_price .price .line {height:26px; width:100%; font-size:13px; color:#666; line-height:26px;}
    .detail_price .price .line span {height:26px; width:auto; float:right;}
    
   
    .detail_pay {height:60px; width:100%; background:#fff; padding:0px 3%; margin-top:30px; border-top:1px solid #eaeaea;position:fixed;bottom:0px}
    .detail_pay span {height:60px; width:auto; margin-right:16px; float:right; line-height:60px; color:#e43a3d; font-size:16px;}
    .detail_pay .paysub {height:38px; width:auto;margin-left:5px; background:#e43a3d; padding:0px 10px; margin-top:10px; border-radius:5px; color:#fff; line-height:38px; float:right;}
    
    .detail_pay .paysub1 {height:38px; width:auto; margin-left:5px;background:#fff; padding:0px 10px; margin-top:10px; border-radius:5px; color:#5f6e8b; line-height:38px; float:right;border:1px solid #5f6e8b;}
       
       
    .chooser {height: 100%; width: 100%; background:#efefef; position: fixed; top: 0px; right: -100%; z-index: 1;}
    .chooser .address {height:50px; width:94%; background:#fff; padding:10px 3%; border-bottom:1px solid #eaeaea;}
    .chooser .address .ico {height:50px; width:10%; line-height:50px; float:left; font-size:20px; text-align:center; color:#999;}
    .chooser .address .info {height:50px; width:77%; margin-right:3%; float:left;}
    .chooser .address .info .name {height:28px; width:100%; font-size:16px; color:#666; line-height:28px;}
    .chooser .address .info .addr {height:22px; width:100%; font-size:14px; color:#999; line-height:22px;}
    .chooser .address .edit {height:50px; width:10%; float:left; }

    .chooser .add_address {height:44px; width:94%; background:#fff; padding:0px 3%; border-bottom:1px solid #eaeaea; line-height:44px; font-size:16px; color:#666;}
    
    .detail_nav { height:30px; width:94%;padding:10px;}
    .detail_nav .nav { padding:2px 5px 2px 5px;; border:1px solid #5f6e8b; color:#5f6e8b; background:#fff; float:left; margin-left:10px;}
    .detail_nav .selected { border:1px solid #ff6600; color:#ff6600; }
    .detail_express {height:auto;padding:10px;background:#fff;  margin-top:16px; border-top:1px solid #eaeaea;}
    .detail_express .ico {height:6px; width:10%; line-height:36px; float:left; text-align:center;}
    .detail_express .title {height:36px; width:90%; padding-left:10%; border-bottom:1px solid #eaeaea; line-height:36px; font-size:18px; color:#333;}
    .detail_express .content {height:auto; width:100%; padding:10px 0px; }
.address_main {height:100%; width:94%; background:#fff; padding:0px 3%;  position: fixed; top: 0px; right: -100%; z-index: 1;}
.address_main .line {height:44px; width:100%; border-bottom:1px solid #f0f0f0; line-height:44px;}

.address_main .line input {float:left; height:44px; width:100%; padding:0px; margin:0px; border:0px; outline:none; font-size:16px; color:#666;padding-left:5px;}
.address_main .line select  { border:none;height:25px;width:100%;color:#666;font-size:16px;}
.address_main .address_sub1 {height:44px; width:94%; margin:14px 3% 0px; background:#ff4f4f; border-radius:4px; text-align:center; font-size:16px; line-height:44px; color:#fff;}
.address_main .address_sub2 {height:44px; width:94%; margin:14px 3% 0px; background:#ddd; border-radius:4px; text-align:center; font-size:18px; line-height:44px; color:#666; border:1px solid #d4d4d4;}
select { width:100px;height:40px;position:absolute;left:0; -webkit-appearance: none;background:#fff; -webkit-tap-highlight-color: transparent;filter:alpha(Opacity=0); opacity: 0;};
 
.stores {overflow:hidden;background:#fff;}
.store {height:65px;  background:#fff; padding:5px; border-bottom:1px solid #eaeaea;}
.store .info .ico { float:left;  height:50px; width:30px; line-height:30px; font-size:16px; text-align:center; color:#666}
.ico i{font-size: 20px}
.store .info .info1 {height:54px; width:100%; float:left;margin-left:-30px;margin-right:-60px;}
.store .info .info1 .inner { margin-left:30px;margin-right:60px;overflow:hidden;}
.store .info .info1 .inner .user {height:25px; width:100%; font-size:14px; color:#333; line-height:25px;overflow:hidden;}
.store .info .info1 .inner .tel {height:20px; width:100%; font-size:13px; color:#999; line-height:20px;overflow:hidden;}
.store .info .info1 .inner .address { width:100%; font-size:13px; color:#999; line-height:20px;}
.store .info .ico2 {height:50px; width:30px;padding-top:15px; float:right; font-size:24px; text-align:center; color:#ccc;}
.store .info .ico3 {height:50px; width:30px;padding-top:15px; float:right; font-size:24px; text-align:center; color:#ccc;} 
.store_title {height:44px; width:94%; overflow: hidden; background:#fff; padding:0px 3%; margin-top:14px; border-bottom:1px solid #eaeaea; border-top:1px solid #eaeaea; line-height:44px; color:#666; font-size:14px;} 
.store_more {height:20px;  background:#fff; font-size:14px; color:#999; line-height:20px; padding:5px; border-bottom:1px solid #eaeaea; text-align: center;}
.page_topbar .nav { position:absolute;right:5px;;color:#333;}

.detail_good .text { padding:10px; color:#666;font-size:13px;}


    .diyform_info {height:auto;  background:#fff; margin-top:14px; border-bottom:1px solid #e8e8e8; border-top:1px solid #e8e8e8;}
    .diyform_info .dline {margin:0 10px; height:40px; border-bottom:1px solid #e8e8e8; line-height:40px; color:#666;}
    .diyform_info .dline .dtitle {height:40px; width:90px; line-height:40px; color:#444; float:left; font-size:16px;  }
    .diyform_info .dline .dinfo { width:100%;float:right;margin-left:-90px; position: relative; font-size:14px; color:#999; line-height:40px; height:40px; }
    .diyform_info .dline .dinner { margin-left:90px; }
    .diyform_info .dline1  { height:auto;overflow:hidden;}
     .diyform_info .dline2 .dinfo img  { margin-top:5px;}
   .diyform_info .dline1 .dinfo { height:auto; line-height:35px;}
   .diyform_info1 { border:none; margin-top:0px; border:1px solid #efefef; border-top:none;  }
   .diyform_info1 .dline { margin:0;}
   .diyform_info1 .dline .dtitle { padding-left:10px;width:80px;font-size:13px;}
   .diyform_info1 .dline .dinner { font-size:13px;}
   .diyform_info .btn { text-decoration: none;  display: block; background:#f0f0f0; width:100%; text-align: center; color: #999;padding:3px; border-radius:1px; line-height:25px;  }
    .detail_user:after{
        display: block;clear: both;content: '';
    }
    .detail_user,.detail_user .info .info1 .inner .address,.detail_user .info .info1{
        height: auto;overflow: visible;
    }
    .detail_user .info .info1 .inner .address{

    }
</style>
<div id='container'></div>

<script id='tpl_detail' type='text/html'>
<div class="page_topbar">
    <a href="<?php  echo $this->createMobileUrl('order')?>" class="back"><i class="fa fa-angle-left"></i></a>
    <%if order.status==1 && order.isverify=='1' && order.verifyied!='1'%><a href="javascript:;" class="btn" onclick="VerifyHandler.verify('<?php  echo $_GPC['id'];?>')"><i class="fa fa-qrcode"></i></a><%/if%>
    <div class="title">订单详情</div>
</div>
<div class="detail_topbar">
    <!-- <div class="ico"><i class="fa fa-file-text-o"></i></div> -->
    <div style="font-size:16px;margin-left:10px;color:#fff;margin-bottom:5px">
        <%if order.status==0 && order.paytype!=3%>等待付款<%/if%>
        <%if order.paytype==3 && order.status==0%>货到付款，等待发货<%/if%>
        <%if order.status==1%>买家已付款<%/if%>
        <%if order.status==2 %>卖家已发货<%/if%>
        <%if order.status==3%>交易完成<%/if%>
        <%if order.status==-1%>交易关闭<%/if%>
    </div>
    <div class="tips">
        <span>订单金额(含运费): ￥<%order.price%><span><br/>
        <span>运费: ￥<%order.dispatchprice%><span><br/>
    </div>
</div>
  <%if show==1%>
    <%if order.isverify==1 || order.virtual!='0'%>
    
    <div class="detail_user">
        <div class="info" >
            <div class="ico"><i class="fa fa-user"></i></div>
                <div class='info1'>
                     <div class='inner'>
                         <div class="user">联系人:  <%carrier.carrier_realname%></div>
                         <div class="address">联系电话: <span id='carrier_address'><%carrier.carrier_mobile%></span></div>
                     </div>
                 </div>
            </div>
          </div>
    </div>
    <%/if%>
<%/if%>

    <%if order.isverify==1%>
   <!--  <div class="store_title" onclick="showStores(this)" show="1" >适用的门店
         <i class="fa fa-angle-down" style="float:right; line-height:44px; font-size:26px;"></i>
    </div>
  
    
      <div class="stores">
      <%each stores as store index%>
     <%if index<=1%>
     <div class="store" >
             <div class="info">
             <div class="ico"><i class="fa fa-building-o"></i></div>
             <div class='info1'>
                 <div class='inner'>
                     <div class="user"><%store.storename%></div>
                     <div class="address">地址: <%store.address%></div>
                     <div class="tel">电话: <%store.tel%></div>
                 </div>
             </div>
             <a href="http://api.map.baidu.com/marker?location=<%store.lat%>,<%store.lng%>&title=<%store.storename%>&name=<%store.storename%>&content=<%store.address%>&output=html"><div class="ico2"><i class='hs hs-shouhuodizhi'></i></div></a>
             <a href="tel:<%store.tel%>"><div class="ico3" ><i class='fa fa-phone'></i></div></a>
        </div>
       </div>
     <%/if%>
     <%/each%> 
         <div id='store_more' style="display:none">
      <%each stores as store index%>
     <%if index>1%>
     <div class="store" >
             <div class="info">
             <div class="ico"><i class="fa fa-building-o"></i></div>
             <div class='info1'>
                 <div class='inner'>
                     <div class="user"><%store.storename%></div>
                     <div class="address">地址: <%store.address%></div>
                     <div class="tel">电话: <%store.tel%></div>
                 </div>
             </div>
             <a href="http://api.map.baidu.com/marker?location=<%store.lat%>,<%store.lng%>&title=<%store.storename%>&name=<%store.storename%>&content=<%store.address%>&output=html"><div class="ico2"><i class='hs hs-shouhuodizhi'></i></div></a>
             <a href="tel:<%store.tel%>"><div class="ico3" ><i class='fa fa-phone'></i></div></a>
        </div>
       </div>
     <%/if%>
     <%/each%> 
         </div>
    <%if stores.length>=3%>
     <div class="store_more" onclick="$('#store_more').show();$(this).remove()">显示更多 <i class="fa fa-angle-double-down"></i></div>
     <%/if%> 
      </div> -->
     <%if order.addressid!=0%>
<div class="detail_user">
    <input type='hidden' id='addressid' value='<%address.id%>' />
    <div class="info">
        <div class="ico"><i class="hs hs-shouhuodizhi"></i></div>
         <div class='info1'>
                 <div class='inner'>
                        <div class="user">收件人：<span id='address_realname'><%address.realname%></span>(<span id='address_mobile'><%address.mobile%></span>)</div>
                        <div class="address"><span id='address_address'><%address.address%></span></div>
                 </div>
             </div>
   
    </div>
</div>
 <%/if%>
 
 <%if order.dispatchtype==1%>
 <div class="detail_user">
     <input type='hidden' id='carrierindex' value='0' />
    <div class="info" id='carrier_select' >
        <div class="ico"><i class="hs hs-shouhuodizhi"></i></div>
            <div class='info1'>
                 <div class='inner'>
                     <div class="user">自提地点：<span id='carrier_realname'><%carrier.realname%></span>(<span id='carrier_mobile'><%carrier.mobile%></span>)</div>
                     <div class="address"><span id='carrier_address'><%carrier.address%></span></div>
                 </div>
         </div>
    </div>
</div>

<div class="detail_user">
     <input type='hidden' id='carrierindex' value='0' />
    <div class="info" id='carrier_select' >
            <div class='info1'>
                 <div class='inner'>
                     <div class="user" style="font-size: 14px;padding-left: 10px">提货人姓名：<span id='carrier_realname' ><%carrier.carrier_realname%></span></div>
                     <div class="user" style="font-size: 14px;padding-left: 10px">提货人手机：<span id='carrier_mobile'><%carrier.carrier_mobile%></span></div>
                 </div>
         </div>
    </div>
</div>
 <%/if%>
   
    <%else%>
    
	
 


 <%if order.addressid!=0%>
<div class="detail_user">
    <input type='hidden' id='addressid' value='<%address.id%>' />
    <div class="info">
        <div class="ico"><i class="hs hs-shouhuodizhi"></i></div>
         <div class='info1'>
                 <div class='inner'>
                        <div class="user">收件人：<span id='address_realname'><%address.realname%></span>(<span id='address_mobile'><%address.mobile%></span>)</div>
                        <div class="address"><span id='address_address'><%address.address%></span></div>
                 </div>
             </div>
   
    </div>
</div>
 <%/if%>
 
<!--  <%if order.dispatchtype==1%>
 <div class="detail_user">
     <input type='hidden' id='carrierindex' value='0' />
    <div class="info" id='carrier_select' >
        <div class="ico"><i class="hs hs-shouhuodizhi"></i></div>
            <div class='info1'>
                 <div class='inner'>
                     <div class="user">自提地点：<span id='carrier_realname'><%carrier.realname%></span>(<span id='carrier_mobile'><%carrier.mobile%></span>)</div>
                     <div class="address"><span id='carrier_address'><%carrier.address%></span></div>
                 </div>
         </div>
    </div>
</div>
 <%/if%> -->
 <%/if%>
 
 

<!--<span>diyform</span>-->
<?php  if($diyform_flag == 1 && count($goods)==1) { ?>
<?php  $datas = iunserializer($goods[0]['diyformdata'])?>
<div class="diyform_info">
<?php  if(is_array($goods[0]['diyformfields'])) { foreach($goods[0]['diyformfields'] as $value) { ?>
<div class='dline   <?php  echo $value['tp_css'];?>'>
        <div class='dtitle'><?php  echo $value['tp_name'];?>：</div>
        <div class='dinfo'>
			<div class='dinner'>
		           <?php  echo $value['tp_value'];?>
			</div>
        </div>
</div>
<?php  } } ?>
</div>
<?php  } ?>	
<div class="detail_good">
    <div class="ico"><i class="hs hs-store" style="color:#666; font-size:20px;"></i></div>
    <div class="shop"><%set.name%></div>
    <%each goods as g%>
     <div class="good">
            <div class="img"  onclick="location.href='<?php  echo $this->createMobileUrl('shop/detail')?>&id=<%g.goodsid%>'"><img src="<%g.thumb%>"/></div>
            <div class='info' onclick="location.href='<?php  echo $this->createMobileUrl('shop/detail')?>&id=<%g.goodsid%>'">
                <div class='inner'>
                       <div class="name"><%g.title%></div>     
                       <div class='option'><%if g.optionid!='0'%>规格:  <%g.optiontitle%><%/if%></div>
                </div>
            </div>
            <div class="price">
                <div class='pnum'><span class='marketprice'>￥<%g.price%></span></div>
                <div class='pnum'><span class='total'>×<%g.total%></span></div>
            </div>
        </div>
	<?php  if(count($goods)>1) { ?>
	<%if g.diyformfields.length>0%>
	 
	<div class="diyform_info diyform_info1">
	        <a href='javascript:;' class='btn' onclick='showDiyInfo(this)' hide='1'>查看提交的资料</a>
	        <div style='display:none'>
		<%each g.diyformfields as v %>
			<div class='dline  <%=v.tp_css%>'>
				 <div class='dtitle'><%=v.tp_name%>：</div>
				 <div class='dinfo'>
					 <div class='dinner'>
						 <%=v.tp_value%>
					 </div>
				 </div>
		   </div> <%/each%>
		 </div>
	      
          </div>
		<%/if%>
		
		<?php  } ?>

    <%/each%>
</div> 
 <%if order.virtual_str!=''%>
<div class="detail_good" style='margin-bottom:10px;'>
    <div class="ico"><i class="fa fa-cubes" style="color:#666; font-size:20px;"></i></div>
    <div class="shop">发货信息</div>
    <div class='text'><%=order.virtual_str%></div>
</div> 
 
 <%/if%>
<div class="detail_price" >
    <input type="hidden" id="weight" value="<%weight%>" />
    <div class="price">
        <div class="line">商品小计:<span>￥<span class='goodsprice'><%order.goodsprice%></span></span></div>
        	
        <div class="line">运费:<span>￥<span class='dispatchprice'><%order.olddispatchprice%></span></span></div>
      
	
        <%if order.discountprice>0%>
        <div class="line">会员折扣:<span>-￥<span class='discountprice'><%order.discountprice%></span></span></div>
        <%/if%>
        <%if order.deductprice>0%>
        <div class="line">积分抵扣:<span>-￥<span class='deductprice'><%order.deductprice%></span></span></div>
        <%/if%>
        <%if order.deductcredit2>0%>
        <div class="line">余额抵扣:<span>-￥<span class='deductprice2'><%order.deductcredit2%></span></span></div>
        <%/if%>
        <%if order.changeprice!=0%>
        <div class="line">改价优惠:<span><%if order.changeprice>0%>+<%/if%>￥<span class='changeprice2'><%order.changeprice%></span></span></div>
        <%/if%>
        
        <%if order.changedispatchprice!=0%>
        <div class="line">运费改价:<span><%if order.changedispatchprice>0%>+<%/if%>￥<span class='changedispatchprice2'><%order.changedispatchprice%></span></span></div>
        <%/if%>

        <%if order.couponprice!=0%>
        <div class="line">优惠券优惠:<span><%if order.couponprice>0%>-<%/if%>￥<span class='changedispatchprice2'><%order.couponprice%></span></span></div>
        <%/if%>
        
        <div class="line">实付费(含运费):<span><span class='dispatchprice' style='color:#e43a3d'>￥<%order.price%></span></span></div>
      </div> 
</div>
    <%if order.status==3%>
    <div class="detail_price" style="margin-top:15px;height:80px;">
    <div class="price" style="padding-top:10px;">
     <div class="line">订单号:<span><%order.ordersn%></span></div>
     <div class="line">交易完成时间:<span><%order.finishtime%></span></div>
    </div>
    </div>
<!--<div class='detail_express'>-->
    <!--<div class="ico"><i class="fa fa-truck" style="color:#666; font-size:20px;"></i></div>-->
    <!--<div class="title">物流跟踪</div>-->
    <!--<div class='content' id='express_container'>-->

    <!--</div>-->

<!--</div>-->
     <%/if%>
     <div style="height:80px;"></div>
     
<div class="detail_pay">
      <%if order.status==0%>
	  <%if order.paytype!=3%>
		<div class="paysub" onclick="location.href ='<?php  echo $this->createMobileUrl('order/pay')?>&orderid=<%order.id%>&openid=<?php  echo $openid;?>'">确认付款</div>
           <%/if%>
           <div class="paysub1 order_cancel" style='position:relative;width:85px;border: 1px solid #ddd;'>
               <span style='position:absolute;display:block;width:70px;top:-10px;color:#999'>取消订单</span>
           <select>
               <option value="">不取消了</option>
               <option value="我不想买了">我不想买了</option>
               <option value="信息填写错误，重新拍">信息填写错误，重新拍</option>
               <option value="同城见面交易">同城见面交易</option>
               <option value="其他原因">其他原因</option>
           </select>
             </div>
      <%/if%>
  
      
      <%if order.status==2 %>
             <div class="paysub order_complete">确认收货</div>
			 <%if order.expresssn!=''%>
             <div class="paysub1 order_express">查看物流</div>
			 <%/if%>
      <%/if%>
      <%if order.status==3 && order.iscomment==0%>
             <div class="paysub1 order_comment">评价</div>
      <%/if%>
      <%if order.status==3 && order.iscomment==1%>
             <div class="paysub1 order_comment">追加评价</div>
      <%/if%>
      <%if order.status==3  || order.status==-1%>
             <div class="paysub1 order_delete">删除订单</div>
      <%/if%>
      <%if order.canrefund%>
         <%if order.refundid!=0%>
               <div class="paysub order_refund">退款申请中</div>
         <%else%>
               <div class="paysub order_refund">申请退款</div>
         <%/if%>
      <%/if%> 
       <%if order.isverify=='1' %>
              <%if order.verified!='1'%>
                      <%if order.status==1%>
                       <div class="paysub1" onclick="VerifyHandler.verify('<?php  echo $_GPC['id'];?>')" style='float:left'><i class="fa fa-qrcode"></i> 确认使用</div>
                       <%/if%>
            <%/if%>
      <%/if%>
</div>
</script>
<?php  if(p('verify')) { ?>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('verify/pop', TEMPLATE_INCLUDEPATH)) : (include template('verify/pop', TEMPLATE_INCLUDEPATH));?>
<?php  } ?>

<script type="text/javascript">
	function showDiyInfo(obj){
				var hide = $(obj).attr('hide');
				$(obj).next().toggle('fadeIn');
				$(obj).attr('hide',hide=='1'?'0':'1');
			}
			
     function showStores(obj){
        if($(obj).attr('show')=='1'){
            $(obj).next('div').slideUp(100);
            $(obj).removeAttr('show').find('i').removeClass('fa-angle-down').addClass('fa-angle-up');
        }
        else{
            $(obj).next('div').slideDown(100);
            $(obj).attr('show','1').find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
        }
    }
    require(['tpl', 'core'], function(tpl, core) {
    
	function is_weixin(){
		
	}
        core.json('order/detail',{id:'<?php  echo $_GPC['id'];?>'},function(json){
                 
                 if(json.status==0){
                     core.message('订单已取消或不存在，无法查看!',"<?php  echo $this->createMobileUrl('order')?>" ,'error');
                     return;
                 }
                 $('#container').html(  tpl('tpl_detail',json.result) );
				 
				 
				     var ua = navigator.userAgent.toLowerCase();
						var isWX = ua.match(/MicroMessenger/i) == "micromessenger";
						var z = []; 
						$(".diyform_info img").each(function() {
							 z.push($(this).attr("src"));
						 });
						 var current;
						 if (isWX) {
							 $(".diyform_info img").click(function(e) {
								 e.preventDefault();
								 var startingIndex = $(".diyform_info img").index($(e.currentTarget));
								 var current = null;
								 $(".diyform_info img").each(function(B, A) {
									 if (B === startingIndex) {
										 current = $(A).attr("src");
									 }
								 });
								 WeixinJSBridge.invoke("imagePreview", {
									 current: current,
									 urls: z
								 });
							 });
						 }
			 
                 $("#verifycode").html( json.result.order.verifycode);
                 $(".order_cancel").find('select').change(function(){
                        var reason = $(this).val();

                        if(reason!=''){
                             core.json('order/op',{'op':'cancel', orderid:'<?php  echo $_GPC['id'];?>',reason:reason},function(json){

                                 if(json.status==1){
                                      location.href = core.getUrl('order');
                                      return;
                                 }
                                 else{
                                      core.tip.show(json.result);
                                 }
                             },true,true);
                        }
                 });
             
                 $('.order_refund').click(function(){
                       location.href = core.getUrl('order/op',{op:'refund',orderid:'<?php  echo $_GPC['id'];?>'});
                  });
                    $('.order_express').click(function(){
                       location.href = core.getUrl('order/express',{id:'<?php  echo $_GPC['id'];?>'});
                  });
                
                 $(".order_complete").click(function(){
  
                      core.tip.confirm('确认您已经收货?',function(){
                      
                         core.json('order/op',{'op':'complete', orderid:'<?php  echo $_GPC['id'];?>'},function(json){
                                 if(json.status==1){
                                      location.reload();
                                      return;
                                 }
                                 core.tip.show(json.result);
                             },true,true);
                       });
                 });
               
                 $(".order_comment").click(function(){
                             location.href = core.getUrl('order/op',{op:'comment',orderid:'<?php  echo $_GPC['id'];?>'});
                 });
            
                 $(".order_delete").click(function(){
                         core.json('order/op',{'op':'delete', orderid:'<?php  echo $_GPC['id'];?>'},function(json){

                              if(json.status==1){
                                   location.href = core.getUrl('order');
                                   return;
                               }
                              core.tip.show(json.result);
                         },true,true);
                 });

         },true, true);
   });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>

