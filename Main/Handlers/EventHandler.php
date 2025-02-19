<?php

namespace EO\Handlers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventHandler
{
	private array $listeners = [];
    
    // Define subscribers with their dependencies
    private array $subscribers = [
		\EO\Handlers\EventsListeners\AccountEventsListener::class => null,
		\EO\Handlers\EventsListeners\PropertyEventsListener::class => null,
		\EO\Handlers\EventsListeners\LogEventsListener::class => null,
    ];

    public function __construct()
    {
        $this->registerSubscribers(); // Automatically register subscribers upon initialization
    }

    public function addListener(string $event_name, callable $listener): void
    {
        $this->listeners[$event_name][] = $listener;
    }

    public function addSubscriber(EventSubscriberInterface $subscriber): void
    {
        // Register the subscriber and its events
        foreach ($subscriber::getSubscribedEvents() as $event_name => $method) {
            $this->addListener($event_name, [$subscriber, $method]);
        }
    }

    public function dispatch(string $event_name, $event_data = null)
    {
        if (!isset($this->listeners[$event_name])) {
            return;
        }

        foreach ($this->listeners[$event_name] as $listener) {
            call_user_func($listener, $event_data);
        }
    }

    private function registerSubscribers(): void
    {
        foreach ($this->subscribers as $subscriber_class => $dependencies) {
            $instances = [];

			if(is_array($dependencies)) {
				$this->createInstances($dependencies, $instances);
			}

            // Create subscriber instance with resolved dependencies
            $subscriber_instance = new $subscriber_class(...$instances);
            $this->addSubscriber($subscriber_instance);
        }
    }

    private function createInstances(array $dependencies, array &$instances): void
    {
        foreach ($dependencies as $dependency => $sub_dependencies) {
            if (is_array($sub_dependencies)) {
                // If this dependency has sub-dependencies, resolve them first
                $this->createInstances($sub_dependencies, $instances);
                // Then create the main dependency instance
                $instances[] = new $dependency(...$instances);
            } else {
                // For other single dependencies, just instantiate and add to instances
                $instances[] = new $dependency();
            }
        }
    }

}