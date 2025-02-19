<?php

namespace EO\View;

use Pecee\Exceptions\InvalidArgumentException;
use EO\Factories\Factory as Factory;
use EO\Facades\FileSystemFacade as FileSystem;
use EO\Database\Pagination as Pagination;
use EO\Service as Service;

class Template extends Service
{
    private static $basePath = ROOT . "/Resources/Templates";
	private static $masterTemplatePath;
	private static $template;
	private static $includes;
	private static $responseType = "HTML";
	private static $data = [];
	public static $collections;
	
	function __toString() { return ""; }
	
	public static function render(): string 
	{
		if (self::$responseType === "JSON") {
			response()->json(self::$data);
		}

		return self::getTemplate(self::$data);
	}

    public static function setMasterTemplate($path)
	{
		self::$masterTemplatePath = self::$basePath . $path;
	}

	public static function getMasterTemplate() 
	{
		return self::$masterTemplatePath;
	}

	public static function setTemplateBasePath(string $base_path): self 
	{
		self::$basePath = $base_path;
		return new self();
	}

	public static function getTemplateBasePath(): string
	{
		return self::$basePath;
	}

	/**
	 * Defines a template include.
	 *
	 * @param string $name The name of the template include.
	 * @param string $path The path to the template include.
	 * @param array $data An array of data to pass to the template.
	 */
	public static function define(string $name, string $path, array $data): void 
	{
		self::$includes[$name] = [
			'path' => self::$basePath . $path,
			'data' => $data,
		];
	}

	/**
	 * Includes a template include.
	 *
	 * @param string $template_name The name of the template include.
	 *
	 * @return string The rendered template include.
	 */
	public static function include(string $template_name) 
	{
		$path = self::$includes[$template_name]['path'] ?? '';
		$data = self::$includes[$template_name]['data'] ?? [];

		return self::import($path, $data);
	}

	public static function import(string $file_path, array $data = []) 
	{
		if(request()->isAjax() && empty($data)) {
			return false;
		}

		if (strpos($file_path, '.php') === false) {
			throw new InvalidArgumentException("PHP file not set in import($file_path)! Missing php file only folder pass as argument!");
		}

		if (!file_exists($file_path)) {
			/* FileSystem::write($file_path, '<?php'); */
			throw new InvalidArgumentException("File $file_path not found !");
		}

		require_once $file_path;

		if (!isset($html)) {
			response()->json($data);
		}

		return implode("", $html);
	}

	public static function getTemplate(array $data) 
	{
		return self::import( self::$basePath . "" . self::$template, $data );
	}

	public static function set(string $path): self 
	{
		if ($path !== null) {
			if ($path === "JSON") {
				self::$responseType = $path;
			} else {
				if(self::$template == "") {
					self::$template = $path;
				}
			}
		}

		return new self();
	}

	public static function bind(...$args) 
	{
		if(!isset($args['data'])) {
			throw new \Exception('data bindings in View::bind() are not set. tip: use named arguments like View::bind(data: $data)');
		}

		self::$data = isset($args['data']) ? $args['data'] : [];

		self::$collections = Factory::Pagination()->getPagination();
		/* if(is_object(parent::$collections)) {
			self::$collections = parent::$collections->getPagination();
		} */
	}

	static function getPaginationTemplate(): ?string 
	{
		return self::import(
			file_path: self::$basePath . '/default/pagination.php',
			data: self::$collections
		);
	}

	public static function setDocumentHeader(array $data): void 
	{
		if (is_null($data)) {
			return;
		}

		$document = Factory::Document();

		foreach ($data['scripts'] ?? [] as $script) {
			$document->addScript($script);
		}

		foreach ($data['styles'] ?? [] as $style) {
			$document->addStylesheet($style);
		}

		$document->setTitle($data['title'] ?? '')
			->setDescription($data['description'] ?? '')
			->setMetaData('keywords', $data['description'])
			->setFacebookMetaData('og:title', $data['title'] ?? '')
			->setFacebookMetaData('og:description', $data['description'] ?? '')
			->setFacebookMetaData('og:url', $data['url'] ?? '')
			->setFacebookMetaData('og:image', $data['image'] ?? '')
			->setFacebookMetaData('og:type', $data['type'] ?? 'website')
			->setFacebookMetaData('og:updated_time', $data['modified_at'] ?? DATE_NOW);
	}
}