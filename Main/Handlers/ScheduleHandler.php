<?php

namespace EO\Handlers;

use EO\Facades\LoggerFacade as Logger;

class ScheduleHandler 
{
	private $schedules;
	private $tasks = [];
	private $list = [
		\EO\Handlers\Tasks\CachePruningTask::class => "This task will remove unused files from the cache folder",
		\EO\Handlers\Tasks\TrafficDBStoreTask::class => "This task will store traffics in database from a file created by TrafficCollector",
		\EO\Handlers\Tasks\TemporaryFolderClearingTask::class => "This task will clear the temporary folder",
	];

	private $otherTasks = [
		\EO\Handlers\Tasks\CacheClearingTask::class => "This task will clear files from the cache folder",
	];

	public function __construct() 
	{
		$this->loadTasks($this->list);
	}

	public function loadTasks(array $tasks): void
	{
		foreach ($tasks as $task_class => $description) {
			$task = new $task_class();
			$schedule = $task->schedule();

			$this->tasks[$task_class] = [
				'provider' => $task,
				'schedule' => new \Cron\CronExpression($schedule),
				'description' => $description,
				'time' => $schedule
			];
		}
	}

	public function run() 
	{
		$this->dispatchSchedules();
	}

	public function getList()
	{
		return $this->list;
	}

	public function getTasks()
	{
		return $this->tasks;
	}

	public function getOtherTasks()
	{
		return $this->otherTasks;
	}

	private function dispatchSchedules(): void
	{
		$schedules = [];
		foreach ($this->tasks as $task) {
			if ($task['schedule']->isDue()) {
				$task['provider']->run();

				$task_name_parts = explode("\\", get_class($task['provider']));
				$schedules[] = str_replace("Task", "", end($task_name_parts));
			}
		}

		Logger::log("info", "Scheduler successful run", [
			"route" => "cron.php",
			"data" => [
				"schedules" => $schedules,
			]
		]);
	}
}