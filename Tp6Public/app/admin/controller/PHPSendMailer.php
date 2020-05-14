<?php
namespace app\admin\controller;

use app\BaseController;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPSendMailer extends BaseController{

    public function sendMailer(){
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        $sendmail='1530215484@qq.com';
        $getmail='wb1530215484@sina.com';
        $Password='zbdaikawctzeghfj';
        try {
            //服务器配置
            $mail->CharSet ="UTF-8";                     //设定邮件编码
            $mail->SMTPDebug = 0;                        // 调试模式输出
            $mail->isSMTP();                             // 使用SMTP
            $mail->Host = 'smtp.qq.com';                // SMTP服务器
            $mail->SMTPAuth = true;                      // 允许 SMTP 认证
            $mail->Username = $sendmail;                // SMTP 用户名  即邮箱的用户名
            $mail->Password = $Password;             // SMTP 密码  部分邮箱是授权码(例如qq邮箱)
            $mail->SMTPSecure = 'ssl';                    // 允许 TLS 或者ssl协议
            $mail->Port = 465;                            // 服务器端口 25 或者465 具体要看邮箱服务器支持

            $mail->setFrom($sendmail, 'Mailer');  //发件人
            $mail->addAddress($getmail, 'Joe');  // 收件人
            //$mail->addAddress('ellen@example.com');  // 可添加多个收件人
            $mail->addReplyTo($sendmail, 'info'); //回复的时候回复给哪个邮箱 建议和发件人一致
            //$mail->addCC('cc@example.com');                    //抄送
            //$mail->addBCC('bcc@example.com');                    //密送

            //发送附件
            // $mail->addAttachment('1.png');         // 添加附件
            // $mail->addAttachment('../hh-1.jpg', 'lo.jpg');    // 发送附件并且重命名

            //Content
            $mail->isHTML(true);// 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
            $mail->Subject = '这里是邮件标题' . time();
            $mail->Body='我莫名奇妙的笑了，只正因想到了你。';//正文
            $mail->AltBody = '如果邮件客户端不支持HTML则显示此内容';
            $mail->send();
            echo '邮件发送成功';
        } catch (Exception $e) {
            echo '邮件发送失败: ', $mail->ErrorInfo;
        }
    }


}