
//写cookies（name:名字,value：值,day:天）
function setCookie(name,value,days){
console.log(days); 
var exp = new Date();
exp.setTime(exp.getTime() + days*24*60*60*1000);
let hostname = location.hostname.substring(location.hostname.indexOf(".")+1)  //设置为一级域名
document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString()+";domain="+hostname+";path=/"; 

}

  
//读取cookies
function getCookie(name)
{
 var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
  
 if(arr=document.cookie.match(reg))  
  return (arr[2]);
 else
  return null;
}
 
function delCookie(name){
	var exp = new Date();
	var name = "access_token";
	exp.setTime(exp.getTime() - 1);
	var cval=getCookie(name);
	if(cval!=null){
	let hostname = location.hostname.substring(location.hostname.indexOf(".")+1)
	document.cookie= name + "='';expires="+exp.toGMTString()+";domain="+hostname+";path=/";
	}

} 