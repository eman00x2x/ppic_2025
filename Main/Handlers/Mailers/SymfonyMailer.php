<?php

namespace EO\Handlers\Mailers;

use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mailer\Transport\TransportFactory;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

use EO\Handlers\Exceptions\MailerException;
use EO\Interfaces\IMailer;
use EO\View;

class SymfonyMailer implements IMailer
{
	private Mailer $mailer;
	private Email $email;

	private $sender = EMAIL_ADDRESS_RESPONDER['email'];
	private $smtpUsername = EMAIL_ADDRESS_RESPONDER['email'];
	private $smtpPassword = EMAIL_ADDRESS_RESPONDER['password'];
	private $smtpServer = EMAIL_ADDRESS_RESPONDER['host'];
	private $smtpPort = EMAIL_ADDRESS_RESPONDER['port'];
	private $message;

	public function __construct() 
	{
		$transport = Transport::fromDsn($this->domainDSN());
		$this->mailer = new Mailer($transport);
		$this->email = new Email();
	}

	public function gmailDSN() 
	{
		return "gmail+smtp://{$this->smtpUsername}:{$this->smtpPassword}";
	}

	public function domainDSN() 
	{
		return "smtp://{$this->smtpUsername}:{$this->smtpPassword}@{$this->smtpServer}:{$this->smtpPort}";
	}

    public function sender(string $email) 
	{
		$this->email->from($email);
	}

    public function subject(string $subject) 
	{
		$this->email->subject($subject);
	}

    public function to(array $emails) 
	{
		$this->email->to(implode(", ", $emails));
	}

    public function cc(array $emails) 
	{
		$this->email->cc(implode(", ", $emails));
	}

    public function bcc(array $emails) 
	{
		$this->email->bcc(implode(", ", $emails));
	}

    public function attachments(array $attachments) 
	{
		if(!empty($attachments)) {
			foreach($attachments as $attachment) {
				$file = new File($attachment);
				$dataPart = new DataPart($file);
				$this->email->addPart($dataPart);
			}
		}else {
			throw new MailerException("No attachments provided!");
		}
	}

    public function send(string $subject) 
	{
		$this->subject($subject);

		$this->sender($this->sender);
		$this->email->replyTo($this->sender);
		$this->email->text(strip_tags($this->message));
		$this->email->html($this->message);

		try {
			$this->mailer->send($this->email);
		} catch(TransportExceptionInterface $e) {
			throw new MailerException("Message could not be sent. Mailer Error: {$e->getMessage()}");
		}
	}

    public function template(string $template, $message) 
	{
		$data['content'] = View::import(ROOT . '/Resources/Templates/mail/' . $template . '.php', $message);

		$template_path = View::getMasterTemplate();
		$this->message = View::import($template_path, $data);
		return $this;
	}
}