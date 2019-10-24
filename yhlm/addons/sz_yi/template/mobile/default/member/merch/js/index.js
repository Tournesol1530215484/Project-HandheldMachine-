function redict(id) {
    event.preventDefault();
    //接下来使用js代码进行页面跳转
    $.ajax({
        url : zan,
        type:"post",
        data:{
            id : id
        }, success: function (res) {
            if(res!=0) {
                //$(obj).find("span").hide();
                var data = $.parseJSON(res);
                var html = '';
                html='<span>'+data.likes+'人点赞</span>'
                $("#zan").html(html);
            }else{
                alert('您已经赞过了');
            }
        }
    });
}
