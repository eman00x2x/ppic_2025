<?php

namespace EO\Facades;

/**
 * Sample usage
 * Mailer::template('PasswordResetEmail', [
 * "username" => $data['username'],
 * 'url' => $url,
 * 'web_url' => DOMAIN . '/mail/PasswordResetEmail/' . $web_url
 * ])->to([$data['email']])->send(subject: 'Password Reset Request!')
 */
class MailerFacade
{
	private static $mailer;

	public static function setMailer($mailer) {
		self::$mailer = $mailer;
	}

	public static function sender($email) 
	{
		self::$mailer->sender($email);
		return new self;
	}

	public static function subject($subject) 
	{
		self::$mailer->subject($subject);
		return new self;
	}

	public static function to(array $emails) 
	{
		self::$mailer->to($emails);
		return new self;
	}

	public static function cc(array $emails) 
	{
		self::$mailer->cc($emails);
		return new self;
	}

	public static function bcc(array $emails) 
	{
		self::$mailer->bcc($emails);
		return new self;
	}

	public static function attachments(array $attachments) 
	{
		self::$mailer->attachments($attachments);
		return new self;
	}

	public static function getSender() 
	{
		return self::$mailer->sender;
	}

	public static function send($subject) 
	{
		self::$mailer->send($subject);
		return new self;
	}

	public static function template(string $template, mixed $message) 
	{
		self::$mailer->template($template, $message);
		return new self;
	}
}