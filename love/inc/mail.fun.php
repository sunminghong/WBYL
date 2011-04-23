<?php
function sendMail($email){	
	require_once(ROOT.'inc/class.phpmailer.php');
	include(ROOT."inc/class.smtp.php");	
	$mail = new PHPMailer(true);	
	$mail->IsSMTP();
	try {
		$mail->Host       = "smtp.163.com"; #SMTP server
		$mail->Port       = 25; #set the SMTP port
		
		$mail->CharSet = "utf-8";
		$mail->SMTPDebug  = 2;#enables SMTP debug information (for testing)
		$mail->SMTPAuth   = true;#enable SMTP authentication
		$mail->Username   = "upfiles"; #SMTP account username
		$mail->Password   = "87256939";#SMTP account password
		
		$mail->AddReplyTo('upfiles@163.com', '刺鸟ReplyTo');		
		$mail->SetFrom('upfiles@163.com', '刺鸟SetFrom');

		$mail->AddAddress($email, '刺鸟QQ');
		$body="asdasdasdasdasdasdasdasdasdsdfsfsdfsdf";		
		$mail->Subject = '测试邮件';
		$mail->AltBody = 'text/html';
		$mail->MsgHTML($body);
		$mail->Send();
		echo "发送成功";
	} catch (phpmailerException $e) {
		echo "发送失败";
	} catch (Exception $e) {
		echo "发送失败";
	}
}
sendMail('3431771@qq.com');
?>