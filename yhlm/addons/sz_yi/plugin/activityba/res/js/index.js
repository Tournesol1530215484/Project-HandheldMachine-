$(function() {
    function h() {
        var j = document.documentElement,
        k = j.requestFullScreen || j.webkitRequestFullScreen || j.mozRequestFullScreen || j.msRequestFullScreen,
        l;
        if (typeof k != "undefined" && k) {
            k.call(j);
            return
        }
        if (typeof window.ActiveXObject != "undefined") {
            l = new ActiveXObject("WScript.Shell");
            if (l) {
                l.SendKeys("{F11}")
            }
        }
    }
    function b() {
        var k = document,
        j = k.cancelFullScreen || k.webkitCancelFullScreen || k.mozCancelFullScreen || k.exitFullScreen,
        l;
        if (typeof j != "undefined" && j) {
            j.call(k);
            return
        }
        if (typeof window.ActiveXObject != "undefined") {
            l = new ActiveXObject("WScript.Shell");
            if (l != null) {
                l.SendKeys("{F11}")
            }
        }
    }
    var c = $(".m-container .notice-box .word .scroll");
    var f = null;
    function a() {
        if (f != null) {
            clearInterval(f)
        }
        c.css("margin-top", "0px");
        var k = c.find("span").length;
        if (k > 1) {
            var j = 0;
            f = setInterval(function() {
                j++;
                c.css("margin-top", (0 - j % k * 50) + "px");
                if (j % k == 0) {
                    j = 0;
                    c.css({
                        transition: "all 0s",
                        "-webkit-transition": "all 0s",
                        "-moz-transition": "all 0s"
                    });
                    setTimeout(function() {
                        c.css("margin-top", "0px");
                        c.css({
                            transition: "all 1s",
                            "-webkit-transition": "all 1s",
                            "-moz-transition": "all 1s"
                        })
                    },
                    2000)
                }
            },
            8000)
        }
    }
    a();
    $(".icon.screen").click(function() {
        h();
        $(".icon.screen").closest("li").addClass("hidden-box");
        $(".icon.exit_screen").closest("li").removeClass("hidden-box")
    });
    $(".icon.exit_screen").click(function() {
        b();
        $(".icon.screen").closest("li").removeClass("hidden-box");
        $(".icon.exit_screen").closest("li").addClass("hidden-box")
    });
    window.iframe = {};
    window.iframe.position = {
        top: 0,
        left: 0
    };
    function e() {
        if ($(window).height() > 747) {
            $(".m-container").addClass("fixed")
        } else {
            $(".m-container").removeClass("fixed")
        }
        window.iframe.position.top = $("iframe").offset().top;
        window.iframe.position.left = $("iframe").offset().left
    }
    e();
    $(window).resize(e);
    //Esc按键处理 不起作用
    // $(document).keydown(function(event) {
    //     var event = event || window.event;
    //     var kc = (navigator.appname == 'Netscape') ? event.keyCode : event.which;
    //     if ( kc == 27) {
    //         if($(".icon.screen").parent().hasClass("hidden-box")){
    //             alert("111");
    //             $(".icon.exit_screen").click()
    //         }else{
    //             alert("1113");
    //         }
    //     }
    // });
 });
var mainFunction = {
    showCover: function(b) {
        var a = '<div class="main-cover"></div>';
        if ($("body").find(".main-cover").length == 0) {
            $("body").append(a);
            $(window.frames.subpage.document.body).append(a)
        }
        $("body").find(".main-cover").on("click",
        function() {
            b()
        });
    },
    hideCover: function() {
        if ($("body").find(".main-cover").length != 0) {
            $("body").find(".main-cover").remove();
            $(window.frames.subpage.document).find(".main-cover").remove()
        }
    },
    showTips: function(b) {
        var a = '<div class="main-tips">' + b + "</div>";
        if ($("body").find(".main-tips").length == 0) {
            $("body").append(a);
            $("body").find(".main-tips").fadeIn("fast");
            setTimeout(function() {
                $("body").find(".main-tips").remove()
            },
            1500)
        }
    }
};