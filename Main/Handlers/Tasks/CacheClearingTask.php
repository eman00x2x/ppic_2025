<?php

namespace EO\Handlers\Tasks;

use EO\Interfaces\ITask;
use EO\Facades\LoggerFacade as Logger;
use EO\Facades\CacheFacade as Cache;

class CacheClearingTask implements ITask
{
    public function run()
    {
        Cache::clearCache();

        Logger::log("info", "Scheduler run CacheClearingTask", [
			"route" => "cron.php",
			"data" => ["Cache has been cleared at " . date("M d,Y h:i:s A")]
		]);
    }

	function schedule() {
		return "@midnight";
	}
}