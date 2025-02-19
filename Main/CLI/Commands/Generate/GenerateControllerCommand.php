<?php

namespace EO\CLI\Commands\Generate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use EO\Support\Helpers\Inflect;

use EO\Facades\FileSystemFacade as FileSystem;
use EO\Database\DBModel;

class GenerateControllerCommand extends Command
{
	protected static $defaultName = 'generate:controller';
	private $defaultDir = ROOT . 'Main/Http/Controllers/';
	private $variableName;
	private $controllerName;
	private $serviceName;

	protected function configure()
	{
		$this
			->setName(self::$defaultName)
			->setDescription('Generates a new controller file')
            ->setHelp('This command allows you to generate a PHP controller file...')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the controller')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);
		$io->title('Generate Controller');

		
		if(!FileSystem::exists($this->defaultDir)) {
			$io->text("First time! Creating controllers directory...");
			FileSystem::makeDir($this->defaultDir);
			$io->text("Controllers directory created!");
		}
		
		$this->variableName = Inflect::pluralize(lcfirst($input->getArgument('name')));
		$this->controllerName = Inflect::pluralize(ucfirst($input->getArgument('name'))) . 'Controller';
		$this->serviceName = lcfirst($input->getArgument('name')) . 'Service';

		$io->text("Generating " . $this->controllerName . "");

		// Define the file path
        $file_path = $this->defaultDir . $this->controllerName . '.php';

		 // Check if the file already exists
        if (FileSystem::exists($file_path)) {
            $io->error($this->controllerName .' already exists!');
            return Command::FAILURE;
        }

		$this->createController($file_path);
		$io->success($this->controllerName . ' generated successfully!');

		return Command::SUCCESS;
	}

	private function createController(string $file_path): void
	{
		$service = ucfirst($this->serviceName);
		$controller_content = '<?php

namespace EO\Http\Controllers;

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Auth\Auth;
use EO\Interfaces\IController;
use EO\Http\BaseController;
use EO\Services\\' . $service . ';
use EO\View;

class ' . $this->controllerName . ' extends BaseController implements IController
{
	protected ' . $service . ' $' . $this->serviceName . ';

	function __construct()
	{
		$this->' . $this->serviceName . ' = new ' . $service . '();
	}
	'.$this->generateIndexMethod().'
	'.$this->generateAddMethod().'
	'.$this->generateEditMethod().'
	'.$this->generateSaveNewMethod().'
	'.$this->generateSaveMethod().'
	'.$this->generateDeleteMethod().'
}';

		FileSystem::write($file_path, $controller_content);
	}

	private function generateIndexMethod() 
	{
		return '
	public function index() 
	{
		$request = input()->all() ?? [];

		$data["' . $this->variableName . '"] = $this->' . $this->serviceName . '->get' . ucfirst($this->variableName) . '($request);

		return View::set(path: "/' . $this->variableName . '/index.php")->bind(data: $data);
	}';
	}

	private function generateAddMethod() 
	{
		return '
	public function add() 
	{
		return View::set(path: "/' . $this->variableName . '/add.php");
	}';
	}

	private function generateEditMethod() 
	{
		return '
	public function edit($id) 
	{
		$' . $this->variableName . ' = $this->' . $this->serviceName . '->get' . ucfirst($this->variableName) . '($id);

		$this->authorize("edit_' . $this->variableName . '", $' . $this->variableName . ');

		$data["' . $this->variableName . '"] = $' . $this->variableName . ';

		return View::set(path: "/' . $this->variableName . '/edit.php")->bind(data: $data);
	}';
	}

	private function generateSaveNewMethod() 
	{
		return '
	public function saveNew()
	{
		$data = input()->all();
		$data["created_by"] = Auth::user()->account["full_name"];

		try {
			$this->' . $this->serviceName . '->create($data);
		} catch (ValidationException $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Successfully created new ' . Inflect::singularize($this->variableName) . '!");
	}';
	}

	private function generateSaveMethod() 
	{
		return '
	public function save($id)
	{
		$data = input()->all();
		$data["modified_by"] = Auth::user()->account["full_name"];

		try {
			$this->' . $this->serviceName . '->update($id, $data);
		} catch (ValidationException $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		} catch (\Exception $e) {
			return $this->handleMessageResponse($e->getMessage(), "error", 2);
		}

		return $this->handleMessageResponse("Successfully updated ' . Inflect::singularize($this->variableName) . '!");
	}';
	}

	private function generateDeleteMethod() 
	{
		return '
	public function delete($id = null): mixed
	{
		$data = $this->' . $this->serviceName . '->get' . ucfirst($this->variableName) . '($id);

		$this->authorize("delete_' . $this->variableName . '", $data);

		if (input()->get("delete")) {
			$this->' . $this->serviceName . '->destroy($id);
			return $this->handleMessageResponse("' . ucwords(Inflect::singularize($this->variableName)) . ' Deleted Successfully!");
		}

		return View::set("/' . $this->variableName . '/delete.php")->bind(data: $data);
	}';
	}

}