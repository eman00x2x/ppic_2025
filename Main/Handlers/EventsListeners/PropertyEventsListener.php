<?php

namespace EO\Handlers\EventsListeners;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EO\Facades\LoggerFacade as Logger;
use EO\Facades\MailerFacade as Mailer;

class PropertyEventsListener implements EventSubscriberInterface
{
	public function __construct()
    {
        
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'property.action' => 'onAction',
            // Other events can be added here
        ];
    }

    public function onAction($event_data): void
    {
        
    }
}