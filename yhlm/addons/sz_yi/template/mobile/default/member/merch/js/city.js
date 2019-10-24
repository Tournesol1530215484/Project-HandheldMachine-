/**
 * Created by Administrator on 2017/5/7.
 */
$(function () {

    $('.container').show();
    // 显示城市
   /* $('body').on('click', '.city-list p', function () {
        //点击的时候获取点击的数据
           var res = $(this).html();
            // alert(res);
           console.log(res);
           $.cookie('the_cookie',res );
        
    });*/
    // 显示选中的城市字母
    $('body').on('click', '.letter a', function () {
        var s = $(this).html();
        $(window).scrollTop($('#' + s + '1').offset().top);
        $("#showLetter span").html(s);
        $("#showLetter").show().delay(500).hide(0);
    });
})