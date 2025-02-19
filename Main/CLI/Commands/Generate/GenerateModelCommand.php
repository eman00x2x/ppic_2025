<?php

namespace EO\CLI\Commands\Generate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use EO\Facades\FileSystemFacade as FileSystem;
use EO\Database\DBModel;

class GenerateModelCommand extends Command
{
	protected static $defaultName = 'generate:model';
	private $defaultDir = ROOT . 'Main/Model/';
	private $modelName;

	protected function configure()
	{
		$this
			->setName(self::$defaultName)
			->setDescription('Generates a new model file')
            ->setHelp('This command allows you to generate a PHP model file...')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the model')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);
		$io->title('Generate Model');

		if(!FileSystem::exists($this->defaultDir)) {
			$io->text("First time! Creating model directory...");
			FileSystem::makeDir($this->defaultDir);
			$io->text("Model directory created!");
		}
		
		$this->modelName = ucfirst($input->getArgument('name'));
		$io->text("Generating " . $this->modelName . "Model");

		// Define the file path
        $file_path = $this->defaultDir . $this->modelName . 'Model.php';

		 // Check if the file already exists
        if (FileSystem::exists($file_path)) {
            $io->error($this->modelName . 'Model already exists!');
            return Command::FAILURE;
        }

		$this->createModel($file_path);
		$io->success($this->modelName . 'Model generated successfully!');

		return Command::SUCCESS;
	}
	private function createModel(string $file_path): void
	{
		$model_class_name = $this->modelName . 'Model';
		$table_name = strtolower($this->modelName) . 's';
		$primary_key = strtolower($this->modelName) . '_id';

		$model_content = '<?php

namespace EO\Model;

use Pecee\Exceptions\InvalidArgumentException;
use EO\Model;
use EO\Interfaces\IModel;

class ' . $model_class_name . ' extends Model implements IModel 
{
	protected $table = "' . $table_name . '";
	protected $primaryKey = "' . $primary_key . '";
	
	'.$this->generateProperties($table_name).'
}';

		FileSystem::write($file_path, $model_content);
	}

	private function generateProperties(string $table_name)
	{
		$db_model = new DBModel();
		$columns = $db_model->fetchColumns($table_name);

		$property_fields = [];
		foreach ($columns as $column) {
			$property_fields[] = $column['Field'];
		}

		foreach ($columns as $column) {
			$fields[] = '"'.$column['Field'].'" => "'.$table_name.'.'.$column['Field'].'",'."\n\t\t\t\t";
		}

		return 'protected $properties = [
		"' . implode('", "', $property_fields) . '"
	];

	public function columns() {
		return [
			"fields" => [
				' . implode('', $fields) . '
			]
		];
	}
';
	}

}