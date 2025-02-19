<?php

namespace EO\CLI\Commands\Generate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use EO\Facades\FileSystemFacade as FileSystem;
use EO\Database\DBModel;

class GenerateServiceCommand extends Command
{
	protected static $defaultName = 'generate:service';
	private $defaultDir = ROOT . 'Main/Services/';
	private $serviceName;
	private $modelName;

	protected function configure()
	{
		$this
			->setName(self::$defaultName)
			->setDescription('Generates a new service file')
            ->setHelp('This command allows you to generate a PHP service file...')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the service')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);
		$io->title('Generate Service');

		
		if(!FileSystem::exists($this->defaultDir)) {
			$io->text("First time! Creating services directory...");
			FileSystem::makeDir($this->defaultDir);
			$io->text("Services directory created!");
		}
		
		$this->serviceName = ucfirst($input->getArgument('name'));
		$this->modelName = $this->serviceName;
		$io->text("Generating " . $this->serviceName . "Service");

		// Define the file path
        $file_path = $this->defaultDir . $this->serviceName . 'Service.php';

		 // Check if the file already exists
        if (FileSystem::exists($file_path)) {
            $io->error($this->serviceName .'Service already exists!');
            return Command::FAILURE;
        }

		$this->createService($file_path);
		$io->success($this->serviceName . 'Service generated successfully!');

		return Command::SUCCESS;
	}

	private function createService(string $file_path): void
	{
		$service_className = $this->serviceName . 'Service';

		$service_content = '<?php

namespace EO\Services;

use Pecee\Http\Exceptions\MalformedUrlException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Service as Service;
use EO\Facades\FileSystemFacade as FileSystem;
use EO\Facades\CacheFacade as Cache;
use EO\Database\DataModel;
use EO\Model\\'.$this->modelName.'Model as '.$this->modelName.';

class ' . $service_className . ' extends Service
{
	function __construct()
	{
		parent::__construct();
		$this->validator->setConstraints([
		]);
	}
	'.$this->generateGetAllMethod().'
	'.$this->generateGetItemMethod().'
	'.$this->generateCreateMethod().'
	'.$this->generateUpdateMethod().'
	'.$this->generateDestroyMethod().'
	'.$this->generateBuildFiltersMethod().'
	'.$this->generateFormatResultMethod().'
}';

		FileSystem::write($file_path, $service_content);
	}

	private function generateGetAllMethod()
	{
		return '
	public function get'.$this->serviceName.'s(array $request = []): array
	{		
		$this->buildFilters($request);
		try {
			$collections = '.$this->modelName.'::getCollections($request);
			$items = $collections->getItems();

			if ($items->isNotEmpty()) {
				return $items->map(function($data, $key) {
					return $this->formatResultData($data);
				})->toArray();
			}
		} catch (MalformedUrlException $e) {
			throw new ResourceNotFoundException("Resource Not Found! " . $e->getMessage());
		}

		return $items->toArray();
	}';
	}

	private function generateGetItemMethod()
	{
		return '
	function get'.$this->serviceName.'(int $id): array
	{
		if ($_ENV["CACHE_ENABLE"] && ($data = Cache::getData("'.strtolower($this->modelName).'-$id"))) {
			return $data;
		}
		
		$collections = Traffic::load( Traffic::columns() )->getId($id);
		$items = $collections->getItems();
 
		if ($items->isNotEmpty()) {
			$traffic = $items->map(function($data, $key) {
				return $this->formatResultData($data);
			})->first()->toArray();
			
			if ($_ENV["CACHE_ENABLE"]) {
				Cache::setData("'.strtolower($this->modelName).'-$id", $traffic);
			}

			return $traffic;
		}else {
			throw new ResourceNotFoundException("Resource Not Found! '.$this->modelName.' ID: $id");
		}

		return $items->toArray();
	}';
	}

	private function generateCreateMethod()
	{
		return '
	function create(array $data): int 
	{
		$data["created_at"] = DATE_NOW;

		$validatedData = $this->validateInput($data);

		try {
			$id = '.$this->modelName.'::create(data: $validatedData);

			$this->log([
				"type" => "info",
				"message" => "'.$this->modelName.' creation with ID: $id succeeded",
				"data" => $validatedData
			]);
		} catch (\Exception $e) {
			$this->log([
				"type" => "warning",
				"message" => "'.$this->modelName.' creation with ID: $id failed",
				"data" => [
					"error" => $e->getMessage(),
					"data" => $validatedData
				]
			]);
			throw new \Exception($e->getMessage());
		}

		return $id;
	}';
	}

	private function generateUpdateMethod()
	{
		return '
	function update(int $id, array $data): array 
	{
		$this->get'.$this->serviceName.'(id: $id);

		$validatedData = $this->validateInput($data);

		try {
			'.$this->modelName.'::modify($validatedData, $id);
			$this->log([
				"type" => "info",
				"message" => "'.$this->modelName.' update with ID: $id succeeded",
				"data" => $validatedData
			]);
		} catch (\Exception $e) {
			$this->log([
				"type" => "warning",
				"message" => "'.$this->modelName.' update with ID: $id failed",
				"data" => [
					"error" => $e->getMessage(),
					"data" => $validatedData
				]
			]);
			throw new \Exception($e->getMessage());
		}

		if ($_ENV["CACHE_ENABLE"]) {
			Cache::removeCache("'.strtolower($this->modelName).'-$id");
		}

		return $validatedData;
	}';
	}

	private function generateDestroyMethod()
	{
		return '
	function destroy($id): void 
	{
		$data = $this->get'.$this->serviceName.'(id: $id);

		'.$this->modelName.'::delete(["'.strtolower($this->modelName).'_id" => $id]);

		$this->log([
			"type" => "info", 
			"message" => "'.$this->modelName.' deleted with ID: $id succeeded",
			"data" => $data
		]);
		
		if ($_ENV["CACHE_ENABLE"]) {
			Cache::removeCache("'.strtolower($this->modelName).'-$id");
		}
	}';
	}

	private function generateBuildFiltersMethod()
	{
		return '
	private function buildFilters(array &$request): void 
	{
		if (isset($request["search"])) {
			$request["OR"] = [
				"name[~]" => $request["search"],
			];
			unset($request["search"]);
		}

		if(isset($request["created_at"])) {
			if(isset($request["created_at"]["from"]) && !isset($request["created_at"]["to"])) {
				$request["AND"]["created_at[>=]"] = strtotime($request["created_at"]["from"]);
			}

			if(isset($request["created_at"]["from"]) &&  isset($request["created_at"]["to"])) {
				$request["AND"]["created_at[<>]"] = [strtotime($request["created_at"]["from"]), strtotime($request["created_at"]["to"])];
			}

			unset($request["created_at"]);
		}
	}';
	}

	private function generateFormatResultMethod()
	{
		return '
	private function formatResultData(IModel $data): IModel 
	{
		$data->created_date = date("d M Y", $data->created_at);
		return $data;
	}';
	}

}