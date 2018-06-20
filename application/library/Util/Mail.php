<?php
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;
use Swift_Attachment;

class Util_Mail
{
	/**
	 * [send description]
	 * @param  [type] $subject [description] 
	 * @param  [type] $to      [description]
	 * @param  [type] $cc      [description]
	 * @param  [type] $body    [description]
	 * @param  [type] $ac      [description] 附件
	 * @return [type]          [description]
	 */
    public static function send($subject,$to,$body,$cc=[],$file_path="")
    {
        //创建Transport对象，设置邮件服务器和端口号，并设置用户名和密码以供验证
		$transport = (new Swift_SmtpTransport('smtp.xxx.qq.com', 25))
		  ->setUsername('xxxx@xxx.com')
		  ->setPassword('xxxxx');
 		
 		// 创建mailer对象
		$mailer = new Swift_Mailer($transport);
		
		// 创建message对象
		$message = (new Swift_Message($subject))
		  ->setFrom(['xxxx' => 'xxxx'])
		  ->setTo($to)
		  ->setBody($body, 'text/html', 'utf-8');

		// 添加抄送人
		if($cc){
			$message->setCc($cc);
		}

		// 创建attachment对象，content-type这个参数可以省略
		if($file_path){
			$file = explode("/", $file_path);

			$filename = $file[count($file)-1];

			$attachment = Swift_Attachment::fromPath($file_path)->setFilename($filename);
			$message->attach($attachment);
		}

		return $mailer->send($message);
    }

   	/**
	 * 1. 首先将数组拆分成以逗号（注意需要英文）分割的字符串
	 * 2. 然后加上每行的换行符号，这里建议直接使用PHP的预定义
	 * 常量PHP_EOL
	 * 3. 最后写入文件
	 */
    public static function writeCsv($file,$csv_header,$csv_body){
		
		// 打开文件资源，不存在则创建
		$fp = fopen($file,'w');

		$code = chr(0xEF).chr(0xBB).chr(0xBF);

		// 处理头部标题
		$header = implode(',', $csv_header) . PHP_EOL;
		// 处理内容
		$content = '';
		foreach ($csv_body as $k => $v) {
		 	$content .= implode(',', $v) . PHP_EOL;
		}
		//拼接
		$csv = $code.$header.$content;
		// 写入并关闭资源
		fwrite($fp, $csv);
		fclose($fp);
    }
}
