<?php

namespace Main;

/**
 * View class for handling view related operations.
 */
class View
{
	/**
	 * @var string $basePath The base path for view files.
	 */
	static $basePath;

	/**
	 * Sets the base path for view files.
	 *
	 * @param string $path The base path.
	 */
	static function setBasePath($path) {
		self::$basePath = $path;
	}

	/**
	 * Gets a template file and returns its content.
	 *
	 * @param string $path The path to the template file relative to the view directory.
	 * @param array $data The data to be passed to the template.
	 * @param object $model The model object.
	 * @param string|null $real_path The real path to the template file. If provided, it will be used instead of the base path.
	 * @return string The content of the template file.
	 */
	static function getTemplate($path, $data, $model, $document_headers) {

	   if(self::$basePath == "") {
			$base = ROOT . "/Resources/Templates";
		}else {
			$base = self::$basePath;
		}

		if($path == "") {
			throw new \Exception('Set the template path. $this->setTemplate($path) from the controller.');
		}

		if(file_exists($base.$path)) {
			self::setDocumentHeaderElements(($document_headers != null ? $document_headers : []));
			require_once($base.$path);
		}else {
			$theFile1 = explode("\\",$path);
			$theFile = array_pop($theFile1);
			throw new \Exception("$theFile is missing in ".implode("\\",$theFile1)."");
		}

		return implode("", $html);

	}

	static function renderJSON($data) {

		/* header("Content-Type: application/json"); */
		echo json_encode($data);
		exit();

	}

	static function setDocumentHeaderElements($data) {

		if(!is_null($data)) {

			$document = \Library\Factory::getDocument();
			
			if(isset($data['scripts'])) {
				foreach($data['scripts'] as $script) {
					$document->addScript($script);
				}
			}

			if(isset($data['styles'])) {
				foreach($data['styles'] as $style) {
					$document->addStylesheet($style);
				}
			}
		
			if(isset($data['title'])) { 
				$document->setTitle($data['title']);
				$document->setFacebookMetaData("og:title", $data['title']);
			}

			if(isset($data['description'])) {
				$document->setDescription($data['description']); 
				$document->setMetaData("keywords", $data['description']);
				$document->setFacebookMetaData("og:description", $data['description']);
			}

			if(isset($data['url'])) { $document->setFacebookMetaData("og:url", $data['url']); }
			if(isset($data['image'])) { $document->setFacebookMetaData("og:image", $data['image']); }
			
			$document->setFacebookMetaData("og:type", (isset($data['type']) ? $data['type'] : "website"));
			$document->setFacebookMetaData("og:updated_time", (isset($data['modified_at']) ? $data['modified_at'] : DATE_NOW));

		}

	}

}
