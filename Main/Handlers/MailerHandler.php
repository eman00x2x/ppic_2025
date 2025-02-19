<?php

namespace EO\Handlers;

use EO\Handlers\Exceptions\MailerException;
use EO\Interfaces\IMailer;
use EO\View;

class MailerHandler
{
	private IMailer $mailer;

	function __construct(IMailer $mailer) 
	{
		$this->mailer = $mailer;
	}

	function sender($email) 
	{
		$this->mailer->sender($email);
		return $this;
	}

	function subject($subject)
	{
		$this->mailer->Subject = $subject;
		return $this;
	}

	function to(array $emails)
	{
		$this->mailer->to($emails);
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

	function send($subject)
	{
		return $this->mailer->send($subject);
	}

	function template(string $template, mixed $message)
	{
		$this->mailer->template($template, $message);
		return $this;
	}

}