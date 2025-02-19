<?php

namespace EO\Handlers\Mailers;

require_once(ROOT . "/Vendor/PHPMailer/phpmailer/src/PHPMailer.php");
require_once(ROOT . "/Vendor/PHPMailer/phpmailer/src/Exception.php");
require_once(ROOT . "/Vendor/PHPMailer/phpmailer/src/SMTP.php");

use EO\Handlers\Exceptions\MailerException;
use EO\Interfaces\IMailer;
use EO\View;

class PHPMailer implements IMailer
{
	private $mailer;
	private $sender = EMAIL_ADDRESS_RESPONDER['email'];
	private $smtpUsername = EMAIL_ADDRESS_RESPONDER['email'];
	private $smtpPassword = EMAIL_ADDRESS_RESPONDER['password'];
	private $smtpServer = EMAIL_ADDRESS_RESPONDER['host'];
	private $smtpPort = EMAIL_ADDRESS_RESPONDER['port'];
	private $message;

	function __construct()
	{
		$this->mailer = new \PHPMailer\PHPMailer\PHPMailer();
	}

	function sender($email)
	{
		$this->sender = $email;
		return $this;
	}

	function subject($subject)
	{
		$this->mailer->Subject = $subject;
		return $this;
	}

	function to(array $emails)
	{
		foreach($emails as $email) {
			$this->mailer->addAddress($email);
		}
		return $this;
	}

	function cc(array $emails)
	{
		$this->mailer->addCC($emails);
		return $this;
	}

	function bcc(array $emails)
	{
		$this->mailer->addBCC($emails);
		return $this;
	}

	function attachments(array $attachments) 
	{
		$this->mailer->addAttachment($attachments);
		return $this;
	}

	function send(string $subject)
	{
		$this->subject($subject);

		try {
			//Set who the message is to be sent from
			$this->mailer->setFrom($this->sender, CONFIG['site_name']);
			$this->mailer->isHTML(true);
			$this->mailer->isSMTP();
			
			//Enable SMTP debugging
			//SMTP::DEBUG_OFF = off (for production use)
			//SMTP::DEBUG_CLIENT = client messages
			//SMTP::DEBUG_SERVER = client and server messages
			$this->mailer->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_OFF;
			$this->mailer->Host = $this->smtpServer;
			$this->mailer->Port = $this->smtpPort;
			$this->mailer->SMTPAuth = false;
			$this->mailer->SMTPAutoTLS = false; 
			$this->mailer->Username = $this->smtpUsername;
			$this->mailer->Password = $this->smtpPassword;

			$this->mailer->Body    = $this->message;
			$this->mailer->AltBody = strip_tags($this->message);

			if(!$this->mailer->send()) {
				throw new MailerException("Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}");
			}

			return true;

		} catch(Exception $e) {
			throw new MailerException("Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}");
		}
	}

	function template(string $template, mixed $message)
	{
		$data['content'] = View::import(ROOT . '/Resources/Templates/mail/' . $template . '.php', $message);

		$template_path = View::getMasterTemplate();
		$this->message = View::import($template_path, $data);
		return $this;
	}

}