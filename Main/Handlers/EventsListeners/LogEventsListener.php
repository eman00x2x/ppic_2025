<?php

namespace EO\Handlers\EventsListeners;

use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use EO\Facades\LoggerFacade as Logger;
use EO\Facades\EventFacade as Event;

class LogEventsListener implements EventSubscriberInterface
{
	function __construct() {

	}

	public static function getSubscribedEvents()
	{
		return [
			'eoengine.exception' => 'onEOEngineException',
			'logs.action' => 'onAction',
		];
	}

	public function onAction($event_data): void
	{
		$resolver = new OptionsResolver();
		$this->configureOptions($resolver);

		try {
			$resolver->resolve($event_data);
		} catch (UndefinedOptionsException $e) {
			$this->onEOEngineException($e);
			return;
		}
		
		Logger::log($event_data['type'], $event_data['message'], $event_data['data']);
	}

	public function onEOEngineException(Exception $exception): void
	{
		$data = [
			'route' => url()->getAbsoluteUrl(),
			'data' => [
				'file' => $exception->getFile(),
				'line' => $exception->getLine(),
				'message' => $exception->getMessage(),
				'trace' => $exception->getTraceAsString(),
				'code' => $exception->getCode()
			]
		];
		Logger::log('critical', $exception->getMessage(), $data);
	}

	private function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setRequired([
			'type',
			'message',
			'data',
		]);
	}
}