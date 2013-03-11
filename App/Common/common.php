<?php
/**
 * 
 * @param unknown_type $address
 * @param unknown_type $title
 * @param unknown_type $message
 * @copyright 请确保class.phpmailer.php文件就在ThinkPHP\Vendor\PHPMailer\class.phpmailer.php
 */
function SendMail($address,$title,$message)
{
	vendor('PHPMailer.class#phpmailer');
	$mail = new PHPMailer();
	
	//设置PHPMail使用SMTP服务器发送
	$mail->IsSMTP();
	//设置字符编码
	$mail->CharSet='UTF-8';
	// 添加收件人地址，可以多次使用来添加多个收件人
	$mail->AddAddress($address);
	// 设置邮件标题
	$mail->Subject=$title;
	// 设置邮件正文
	$mail->Body=$message;
	// 设置邮件头的From字段。
	$mail->From=C('MAIL_FROM');
	// 设置发件人名字
	$mail->FromName=C('MAIL_NAME');
	// 设置SMTP服务器。
	$mail->Host=C('MAIL_SMTP');
	// 设置为"需要验证"
	$mail->SMTPAuth=true;
	// 设置用户名和密码。
	$mail->Username=C('MAIL_USERNAME');
	$mail->Password=C('MAIL_PASSWORD');
	
	$bools = $mail->Send();
	return $bools;
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
	if(function_exists("mb_substr"))
		$slice = mb_substr($str, $start, $length, $charset);
	elseif(function_exists('iconv_substr')) {
		$slice = iconv_substr($str,$start,$length,$charset);
		if(false === $slice) {
			$slice = '';
		}
	}else{
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("",array_slice($match[0], $start, $length));
	}
	return $suffix ? $slice.'......' : $slice;
}