<?php

class Mail {
	public static function send($to, $subject, $message, $from = false, $replyTo = false, $contentType = false) {
		$mail = new PHPMailer;

		$mail->CharSet = 'UTF-8';

		if (Settings::get('app.mail.type') == 'smtp') {
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = Settings::get('app.mail.smtp.host');  // Specify main and backup SMTP servers
			$mail->SMTPAuth = Settings::get('app.mail.smtp.auth');                               // Enable SMTP authentication
			$mail->Username = Settings::get('app.mail.smtp.username');                 // SMTP username
			$mail->Password = Settings::get('app.mail.smtp.password');                           // SMTP password
			$mail->SMTPSecure = Settings::get('app.mail.smtp.encryption');                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = Settings::get('app.mail.smtp.port');                                    // TCP port to connect to
		}

		$mail->From = Settings::get('app.mail.from');
		$mail->FromName = Settings::get('app.mail.from_name');
		$mail->addAddress($to);

		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body = $message;

		$mail->send();
	}
}
