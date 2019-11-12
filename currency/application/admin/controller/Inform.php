<?php
namespace app\admin\controller;
use app\admin\controller\Coom;
use Catetree\Catetree;
use think\Db;
use  think\Loader;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\POP3;
use PHPMailer\PHPMailer\SMTP;
use PHPUnit\Framework\TestCase;

class Inform extends Coom{

	public function Sms(){
		echo "SMS";
	}

	/**
     * 通过邮箱进行验证码的传输-----发件人小明，收件人小红
     * @param email $email  [收件人邮箱地址]
     * @param int  $code    [验证码]
     * @throws \PHPMailer\Exception [邮件处理异常]
     */
	public function Mail(){
		Loader::import('PHPMailer.src.PHPMailer');
		Loader::import('PHPMailer.src.SMTP');
		Loader::import('PHPMailer.src.Exception');

		$email='wb1530215484@sina.com';//1722199055@qq.com,318404687@qq.com

		$code=4325;//验证码，可做代码参数进行传递
		//可将附件进行事前上传，然后吧url路由传
		// $file1 = request()->file('userfile1');//附件是form表单提交过来的,添加附件，这里是绝对路径哦
	 //    if($file1 != ''){
	 //        $info1 = $file1->move(ROOT_PATH . 'public/static/index/uploads/cateimg');//执行上传
	 //        if($info1){
	 //            $imgPath = $info1->getSaveName();//打印结果为 20190612\7eaffaa4bf8ecb50c739935bd676d99b.pdf
	 //            $imgPath = str_replace("\\", "/", $imgPath);//进行路径转换
	 //            $url = ROOT_PATH . 'public/static/index/uploads/cateimg/'.$imgPath;//拼接附件路径
	 //        }else{
	 //            echo $file1->getError();
	 //        }
	 //    }
    // public function send_code_to_email($email,$code){
         $toemail=$email;//定义收件人的邮箱
         $sendmail = '1530215484@qq.com'; //发件人邮箱
         $sendmailpswd = "zbdaikawctzeghfj"; //客户端授权密码,而不是邮箱的登录密码，就是手机发送短信之后弹出来的一长串的密码
         $send_name = '王彬';// 设置发件人信息，如邮件格式说明中的发件人，
         $to_name = '嘿，你还好吗';//设置收件人信息，如邮件格式说明中的收件人
         $mail = new PHPMailer();
         $mail->isSMTP();// 使用SMTP服务
         $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
         $mail->Host = "SMTP.qq.com";// 发送方的SMTP服务器地址
         $mail->SMTPAuth = true;// 是否使用身份验证
         $mail->Username = $sendmail;//// 发送方的
         $mail->Password = $sendmailpswd;//客户端授权密码,而不是邮箱的登录密码！
         $mail->SMTPSecure = "ssl";// 使用ssl协议方式
         $mail->Port = 465;//  sina端口110或25） //qq  465 587
         $mail->setFrom($sendmail, $send_name);// 设置发件人信息，如邮件格式说明中的发件人，
         $mail->addAddress($toemail, $to_name);// 设置收件人信息，如邮件格式说明中的收件人，
         $mail->addReplyTo($sendmail, $send_name);// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
         $mail->Subject = "这是来自王彬的一封邮件";// 邮件标题
         //$code=$code;
        // session("code",$code);
         //return $code."----".session("code");
         //$mail->AddAttachment($url); // 添加附件,并指定名称
         $mail->Body = "青青子佩，悠悠我思，纵我不往，子宁不来。挑兮达兮，在城阙兮，一日不见，如三月兮。";// 邮件正文
         //$mail->AltBody = "This is the plain text纯文本";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用

       
        if(!$mail->send()){
 
            //$this->return_msg(400,$mail->ErrorInfo);//返回数据格式自己定义的一个函数
            // (你也可以写return false;)
           // echo "error";
        	var_dump($mail->ErrorInfo);
    
        }else{
            //$this->return_msg(200,"验证码已经发送，请注意查收");
             // (你也可以写return true;)
        	 echo "success";
        }
    // }



	}
}