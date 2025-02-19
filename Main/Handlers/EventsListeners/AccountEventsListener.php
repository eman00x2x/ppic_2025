<?php

namespace EO\Handlers\EventsListeners;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EO\Facades\LoggerFacade as Logger;
use EO\Facades\MailerFacade as Mailer;
use EO\Facades\EventFacade as Event;

class AccountEventsListener implements EventSubscriberInterface
{
	public function __construct()
    {
        
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'account.registered' => 'onAccountRegistered',
            'account.password.reset.request' => 'onPasswordResetRequest',
			'two.factor.auth.request' => 'onTwoFactorAuthRequest',
            // Other events can be added here
        ];
    }

    public function onAccountRegistered($event_data): void
    {
        try {
			Mailer::template('ActivationEmail', $event_data['data'])
            ->to([$event_data['email']])->send(subject: 'Account Activation!');
			
			Logger::log('info', 'Send activation email succeeded', $event_data);
		} catch(TransportExceptionInterface  $e) {
			Event::dispatch('onEOEngineException', $e);
		}
    }

    public function onPasswordResetRequest($event_data): void
    {
        try {
			Mailer::template('PasswordResetEmail', $event_data['data'])
            ->to([$event_data['email']])->send(subject: 'Password Reset Request!');

			Logger::log('info', 'Send password reset email succeeded', $event_data);
		} catch(TransportExceptionInterface  $e) {
			Event::dispatch('onEOEngineException', $e);
		}
    }

	public function onTwoFactorAuthRequest($event_data): void
	{
		try {
			Mailer::template('TwoFactorAuthEmail', $event_data['data'])
			->to([$event_data['email']])->send(subject: 'Two Factor Authentication Request!');
			
			Logger::log('info', 'Send two factor auth email succeeded', $event_data);
		} catch(TransportExceptionInterface  $e) {
			Event::dispatch('onEOEngineException', $e);
		}
	}
}