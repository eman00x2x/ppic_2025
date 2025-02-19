<?php

namespace EO\Handlers\Tasks;

use EO\Interfaces\ITask;
use EO\Facades\LoggerFacade as Logger;
use EO\Facades\CacheFacade as Cache;

class CachePruningTask implements ITask
{
    public function run()
    {
        Cache::pruneCache();

        Logger::log("info", "Scheduler run CachePruningTask", [
			"route" => "cron.php",
			"data" => ["Cache has been pruned at " . date("M d,Y h:i:s A")]
		]);
    }

	function schedule() {
		return "@midnight";
	}
}