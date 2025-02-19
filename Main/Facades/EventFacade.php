<?php

namespace EO\Facades;

use RuntimeException;
use EO\Handlers\EventHandler;

class EventFacade
{
    public static EventHandler $eventHandler;

    public static function setEvent(EventHandler $eventHandler) 
	{
        self::$eventHandler = $eventHandler;
    }

    public static function dispatch($event_name, $event_data) 
	{
		self::$eventHandler->dispatch($event_name, $event_data);
    }
}