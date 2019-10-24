
var SiteUrl = "";
var ApiUrl = "";
var pagesize = 10;
var WapSiteUrl = "";
var IOSSiteUrl = "";
var AndroidSiteUrl = "";


// auto url detection
(function() {
    var m = /^(https?:\/\/.+)\/wap/i.exec(location.href);
    if (m && m.length > 1) {
        SiteUrl = m[1] + '/shop';
        ApiUrl = m[1] + '/mobile';
        WapSiteUrl = m[1] + '/wap';
    }
})();
