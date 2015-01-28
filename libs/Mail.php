<?php

class Mail {
	public static function send($to, $subject, $message, $from = false, $replyTo = false, $contentType = false) {
		if ( ! $from)
			$from = Settings::get('app.email');

		if ( ! $replyTo)
			$replyTo = Settings::get('app.email');

		if ( ! $contentType)
			$contentType = 'Content-Type: text/html; charset=UTF-8';

		$from = strip_tags($from);
		$replyTo = strip_tags($replyTo);

		$headers = "From: " . $from . "\r\n";
        $headers .= "Reply-To: ". $replyTo . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= $contentType . "\r\n";

        mail($to, $subject, $message, $headers);
	}
}