<?php

namespace Library;

/* use PHPMailer\PHPMailer\PHPMailer as PHPMailer;
use PHPMailer\PHPMailer\Exception; */

class Mailer
{

	private $mailer;
	private $sender = EMAIL_ADDRESS_RESPONDER['email'];
	private $smtp_username = EMAIL_ADDRESS_RESPONDER['email'];
	private $smtp_password = EMAIL_ADDRESS_RESPONDER['password'];
	private $smtp_server = EMAIL_ADDRESS_RESPONDER['host'];
	private $smtp_port = EMAIL_ADDRESS_RESPONDER['port'];
	private $message;

	function __construct() {}

	function setMailSender($email) {
		$this->sender = $email;
		return $this;
	}

	function getMailSender() {
		return $this->sender;
	}

	/**
	 * @param array $to ["to" => [emails], "cc" => [emails], "bcc" => [emails] ]
	 * @param string $subject
	 * @param array $headers
	 */

	function send(array $to, string $subject, array $attachments = null) {

		require_once(ROOT."/Vendor/PHPMailer/phpmailer/src/Exception.php");
		require_once(ROOT."/Vendor/PHPMailer/phpmailer/src/PHPMailer.php");
		require_once(ROOT."/Vendor/PHPMailer/phpmailer/src/SMTP.php");
		
		$this->mailer = new \PHPMailer\PHPMailer\PHPMailer();

		try {

			//Set who the message is to be sent from
			$this->mailer->setFrom($this->sender, 'PAREB MLS Mailer');
			$this->mailer->isHTML(true);
			$this->mailer->isSMTP();
			
			//Enable SMTP debugging
			//SMTP::DEBUG_OFF = off (for production use)
			//SMTP::DEBUG_CLIENT = client messages
			//SMTP::DEBUG_SERVER = client and server messages
			$this->mailer->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_OFF;
			$this->mailer->Host = $this->smtp_server;
			$this->mailer->Port = $this->smtp_port;
			$this->mailer->SMTPAuth = true;
			$this->mailer->Username = $this->smtp_username;
			$this->mailer->Password = $this->smtp_password;

			foreach($to['to'] as $to) {
				//Set who the message is to be sent to
				$this->mailer->addAddress($to);
			}

			if(isset($to['cc'])) {
				foreach($to['cc'] as $cc) {
					$this->mailer->addCC($cc);
				}
			}

			if(isset($to['bcc'])) {
				foreach($to['bcc'] as $bcc) {
					$this->mailer->addBCC($bcc);
				}
			}

			if(!is_null($attachments)) {
				foreach($attachments as $file) {
					$this->mailer->addAttachment($file);
				}
			}
			
			$this->mailer->Subject = $subject;
			$this->mailer->Body    = $this->message;
			$this->mailer->AltBody = strip_tags($this->message);

			if(!$this->mailer->send()) {
				return [
					"status" => 2,
					"type" => "error",
					/* "message" => "Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}" */
					"message" => "Could not process your request. Please try again later."
				];
			}

			return [
				"status" => 1,
				"type" => "success",
				"message" => "Message has been sent"
			];

		} catch(Exception $e) {

			return [
				"status" => 2,
				"type" => "error",
				/* "message" => "Message could not be sent. Mailer Error: {$e->getMessage()}" */
				"message" => "Could not process your request. Please try again later."
			];

		}
	}

	function build($message) {

		$html[] = "<div style='font-family:calibri; line-height:1.5; max-width: 767px; margin:0 auto;color:#323232; font-size:14px; background-color:#F2F1EF;'>";

			/** HEADER */
			$html[] = "<div style='text-align:center;padding:20px;border-bottom:1px solid #ECECEC;background-color:#F2F1EF;'>";
				$html[] = "<img src='".CDN."images/favicon/favicon-32x32.png' alt='Logo' /> PAREB MLS";
			$html[] = "</div>";

			/** BODY */
			$html[] = "<div style='padding:20px;width:90%;margin:0 auto; background-color:#FFF; text-wrap: wrap;'>";
				$html[] = $message;
			$html[] = "</div>";

			/** FOOTER */
			$html[] = "<div style='background-color:#F2F1EF;font-size:13px;text-align:center;padding:10px 20px;color:#6C7A89;border-top:1px solid #ECECEC;'>";
				$html[] = "<br/><p>You received this email, because you are registered in <a href='".WEBDOMAIN."'>".WEBDOMAIN."</a>.</p>";
				$html[] = "<p>This email message is automated, please do not reply, thus no one will received your message. </p><br/>";
			$html[] = "</div>";

		$html[] = "</div>";

		$this->message = implode("", $html);
		return $this;
	}

	function getMessage() {
		return $this->message;
	}

}