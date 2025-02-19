<?php

namespace EO\Services;

use EO\Handlers\Exceptions\AuthenticationException;
use EO\Handlers\Exceptions\MailerException;
use EO\Facades\EventFacade as Event;
use EO\Service;
use EO\Auth\Auth;
use Josantonius\Session\Session;

class TwoFactorAuthenticationService extends Service 
{
	private Session $session;

	function __construct() 
	{
		parent::__construct();
		$this->session = new Session();
	}

	public function sendAuthorizationCodeEmail(string $email): void
	{
		if(isset($this->session->get("two_factor_auth")['expiration']) && $this->session->get("two_factor_auth")['expiration'] > time()) {
			return;
		}

		$authorization_code = $this->generateAuthorizationCode();
		$expiration = $this->getExpiration();
		
		$eventData = [
			'email' => $email,
			'data' => [
				'authorization_code' => $authorization_code,
				'expiration' => $expiration,
			],
		];

		$this->setTwoFactorAuth([
			"authorization_code" => $authorization_code,
			"expiration" => $expiration
		]);

		Event::dispatch('two.factor.auth.request', $eventData);
		
	}

	function validateAuthorizationCode(string $authorization_code): void 
	{
		$two_factor_auth = $this->session->get("two_factor_auth");

		if($two_factor_auth['authorization_code'] !== $authorization_code) {
			throw new AuthenticationException('Invalid authorization code! ' . ($_ENV['DEVELOPMENT'] ? $two_factor_auth['authorization_code'] : ''));
		}

		if($two_factor_auth['expiration'] < time()) {
			throw new AuthenticationException('Expired authorization code!  click to <span class="text-primary btn-send-code cursor-pointer" data-url="'.url("sendAuthorizationCode").'">Send new authorization code</a>');
		}

		$this->session->set("two_factor_auth", [
			"authorized" => true
		]);
	}

	function setTwoFactorAuth($data): void 
	{
		$this->session->set("two_factor_auth", [
			"authorized" => false,
			"authorization_code" => $data['authorization_code'],
			"expiration" => $data['expiration']
		]);
	}

	function generateAuthorizationCode(): string 
	{
		return mt_rand(100000, 999999);
	}

	function getExpiration(): int 
	{
		$expiration_date_time = (new \DateTime())->modify("+5 minutes");
		return $expiration_date_time->getTimestamp();
	}

}