$(function(){
$("#fw").addClass("weui_bar_item_on");
loadTab();
		
$(".btn").click(function(){
		
	var bianma   = $("#bianma").val();
	var newopenId   = $("#newopenId").val();
	var query_type   = $("#query_type").val();
	var validate   = $("#validate").val();
	$(".tabs,#content").css('display','none');
 	if (bianma=="") {  
			//$.toast("请输入防伪码或扫描二维码！","forbidden");
			$("#msg").removeClass('success error').fadeIn('slow').html("<span class='error'>请输入防伪码或扫描二维码!</span>");
			//$(".ts").hide();
			$("#bianma").focus();
            return false; 
        }
		// if (validate=="") {  
			//$("#msg").removeClass('success').addClass('error').fadeIn('slow').html("请输入验证码!");
		//	alert('请输入验证码!');
			//$("#validate").focus();
         //   return false; 
     //   }

    $.ajax({
        type: "POST",
        dataType: "json",
		beforeSend:function(){$("#msg").fadeIn('slow').html("<span class='success'>查询中...请稍候，请不要刷新或关闭本页面！</span>"),$(".btn").val("查询中..."),$('.btn').attr("disabled","disabled");},
        url: "verify.php?act=erweima",
        data: {bianma: bianma,newopenId:newopenId,query_type:query_type,validate:validate},
        success: function(msg) {
            var tishi = msg.tishi;
			var results = msg.results;
			var detail = msg.detail;
			var traces = msg.traces;
			var infos = msg.infos;
			 if(tishi==1){
				$("#msg").removeClass('success').addClass('error').fadeIn('slow').html("验证码错误!请重新输入!");
				$("#validate").focus().select();
				$("#validate").val("");
				reloadcode()
				$(".btn").val("查询");	
				$(".btn").removeAttr("disabled");//恢复按钮
			  }
			  else{
            $("#msg").removeClass('success error').fadeIn("slow").html(tishi);
			if(results!=3){
			$(".tabs,#content").css('display','block');
		}else{
			$(".tabs,#content").css('display','none');
		}
			$("#detail").html(detail);
			$("#traces").html(traces);
			$("#infos").html(infos);
				///遍历图片 start
				var imgOne=$("#detail img").eq(0).attr("src");
				var img_urls = [];
				$("#detail img").each(function() {
					img_urls.push($(this).attr("src"));
				});
				//alert(img_urls);
					$("#detail img").click(function(){
				Viewimg(imgOne,img_urls);	
				})
					///遍历图片	end
				$("#bianma").val("");
				$("#validate").val("");
				$(".btn").val("查询");	
				reloadcode()
				$(".btn").removeAttr("disabled");//恢复按钮
			  }/////
			if(results!=1||results!=2||results!=3){
				return false; 
			}
        }
    })
	});
	///////////
		$(".test").click(function(){
		$("#bianma").val($(this).text());
	})

})


   function  Viewimg(url,urls){
	jWeixin.previewImage({
      current:url,
      urls: urls
    });
	}  