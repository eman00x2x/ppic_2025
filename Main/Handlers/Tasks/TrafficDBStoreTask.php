<?php

namespace EO\Handlers\Tasks;

use EO\Interfaces\ITask;
use EO\Facades\LoggerFacade as Logger;
use EO\Facades\CacheFacade as Cache;
use EO\Services\TrafficService;

class TrafficDBStoreTask implements ITask
{
	private TrafficService $trafficService;
	private $filePath = ROOT. '/Storage/Traffics/traffics.json';

	public function __construct()
	{
		$this->trafficService = new TrafficService();
	}

    public function run()
    {
		if(file_exists($this->filePath)) {
			$traffic_data = jsonFileToArray($this->filePath);
			
			foreach ($traffic_data as $session_id => $session_traffic) {
				foreach ($session_traffic as $traffic) {
					$this->trafficService->create($traffic);
				}
			}

			unlink($this->filePath);
		}

		Logger::log("info", "Scheduler run TrafficDBStoreTask", [
			"route" => "cron.php",
			"data" => ["Traffics store in database at " . date("M d, Y H:i:s")]
		]);
    }

    public function schedule()
    {
        // every day at midnight 00:00
        return '@midnight';
    }
}