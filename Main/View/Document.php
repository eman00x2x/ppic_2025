<?php

namespace EO\View;

class Document {

	protected $title = '';
	protected $description = '';
	protected $metaTags = [];
	protected $scripts = [];
	protected $script = [];
	protected $styleSheets = [];
	protected $style = [];
	protected $generator = '';
	protected $canonical = '';
	
	protected $mime = '';
	protected $tab = "\11";
	protected $lineEnd = "\12";

	function __construct() {}
	
	public function getMetaData(string $name, bool $http_equiv = false): string {
		$name = strtolower($name);

		if ($name === 'generator') {
			return $this->getGenerator();
		} elseif ($name === 'description') {
			return $this->getDescription();
		}

		$meta_tags = $http_equiv ? $this->metaTags['http-equiv'] : $this->metaTags['standard'];

		return $meta_tags[$name] ?? '';
	}

	
	function setMetaData(string $name, string $content, bool $http_equiv = false) {
		$name = strtolower($name);

		if ($name === 'generator') {
			$this->setGenerator($content);
		} elseif ($name === 'description') {
			$this->setDescription($content);
		} else {
			$this->metaTags[$http_equiv ? 'http-equiv' : 'standard'][$name] = $content;
		}

		return $this;
	}

	/**
	 * Sets a Facebook meta tag
	 *
	 * @param string $name  Meta tag name
	 * @param string $value Meta tag value
	 */
	public function setFacebookMetaData(string $name, string $value) {
		$this->metaTags['facebook'][$name] = $value;
		return $this;
	}
	
	/**
	 * Adds a script to the page
	 *
	 * @param string $script_url The URL of the script
	 * @param string $script_type The MIME type of the script (defaults to 'text/javascript')
	 */
	public function addScript(string $script_url, string $script_type = 'text/javascript') {
		$this->scripts[$script_url] = $script_type;
		return $this;
	}

	/**
	 * Adds a script to the page
	 *
	 * @param string $script_content Script content
	 * @param string $script_type     Scripting mime (defaults to 'text/javascript')
	 * @return void
	 */
	public function addScriptDeclaration(string $script_content, string $script_type = 'text/javascript') {
		$script_type = strtolower($script_type);

		if (!isset($this->script[$script_type])) {
			$this->script[$script_type] = $script_content;
		} else {
			$this->script[$script_type] .= "\n" . $script_content;
		}
		return $this;
	}

	/**
	 * Adds a linked stylesheet to the page
	 *
	 * @param string $style_sheet_url URL to the linked style sheet
	 * @param string $mime_type       Mime encoding type
	 * @param string $media_type      Media type that this stylesheet applies to
	 * @param array  $attributes     Additional attributes to add to the stylesheet link
	 */
	public function addStyleSheet(string $style_sheet_url, string $mime_type = 'text/css', string $media_type = null, array $attributes = []) {
		$this->styleSheets[$style_sheet_url] = [
			'mime'     => $mime_type,
			'media'    => $media_type,
			'attribs' => $attributes,
		];
		return $this;
	}

	/**
	 * Adds a stylesheet declaration to the page
	 *
	 * @param string $style_content Style declarations
	 * @param string $style_type     Type of stylesheet (defaults to 'text/css')
	 *
	 * @return void
	 */
	public function addStyleDeclaration(string $style_content, string $style_type = 'text/css') {
		$style_type = strtolower($style_type);

		if (!isset($this->style[$style_type])) {
			$this->style[$style_type] = $style_content;
		} else {
			$this->style[$style_type] .= "\n" . $style_content;
		}
		return $this;
	}

	public function setCanonical(string $link) {
		$this->canonical = $link;
		return $this;
	}
	
	public function setDescription(string $description) {
		$this->description = $description;
		return $this;
	}
	
	/**
	 * Returns the page description
	 *
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}
	
	public function setGenerator(string $generator) {
		$this->generator = $generator;
		return $this;
	}
	
	public function getGenerator(): string {
		return $this->generator;
	}
	
	public function setTitle(string $title) {
		$this->title = $title;
		return $this;
	}

	public function getTitle(): string {
		return $this->title;
	}

	function setTab($string) {
		$this->tab = $string;
	}
	
	function getTab() {
		return $this->tab;
	}
	
	/**
	 * Returns the line end type
	 * 
	 * @return string
	 */
	public function getLineEnd(): string {
		return $this->lineEnd;
	}
	
	public function setLineEndingType(string $line_ending_type): void {
		switch ($line_ending_type) {
			case 'win':
				$this->lineEnd = "\15\12";
				break;
			case 'unix':
				$this->lineEnd = "\12";
				break;
			case 'mac':
				$this->lineEnd = "\15";
				break;
			default:
				$this->lineEnd = $line_ending_type;
		}
	}
	
	public function setMimeEncoding(string $mime_type = 'text/html'): void {
		$this->mime = strtolower($mime_type);
	}

}