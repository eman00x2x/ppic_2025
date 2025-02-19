<?php

namespace EO\Handlers\Files;

use Verot\Upload\Upload;

class FileUpload 
{

	private const ALLOWED_MIME_TYPES = [
		"image" => ["image/jpeg", "image/png", "image/webp"],
		"video" => ["video/x-msvideo", "video/mp4", "video/mpeg", "video/ogg", "video/webm"],
		"audio" => ["audio/3gpp", "audio/wav", "audio/mp3", "audio/mpeg", "audio/midi", "audio/x-midi"],
		"document" => [
			"application/msword",
			"application/vnd.ms-excel",
			"application/vnd.ms-powerpoint",
			"application/vnd.openxmlformats-officedocument.wordprocessingml.document",
			"application/vnd.openxmlformats-officedocument.presentationml.presentation",
			"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
			"application/vnd.oasis.opendocument.text",
			"application/vnd.oasis.opendocument.spreadsheet",
			"application/vnd.oasis.opendocument.graphics",
			"application/vnd.oasis.opendocument.graphics-template",
			"application/vnd.oasis.opendocument.image",
			"application/vnd.oasis.opendocument.image-template",
			"application/vnd.oasis.opendocument.presentation"
		],
		"pdf" => ["application/pdf"]
	];

	private const FILE_SIZES = [
		"MB" => 1048576,
		"GB" => 1073741824
	];

	private $settings = [
		"multiple" => false,
		"temp_folder" => "/Public/global_assets/images/temporary",
		"destination_folder" => "/Public/global_assets/images",
		"temp_url" => "/images/temporary",
		"final_url" => "",
		"file_type" => "image",
		"file_max_size" => 2097152,
		"image_resize" => [
			"width" => 200,
			"height" => 200
		]
	];

	private $mimeTypes;

	private $options;
	private $results = [];
	private $errors = [];

	private $allowedFileSizes = [
		"image" => 2097152,
		"video" => 2097152,
		"audio" => 2097152,
		"document" => 2097152,
		"pdf" => 2097152
	];

	protected function setMaxFileSize(string $file_type, string $file_size): void
	{
		if(!is_string($file_type)) {
			throw new \InvalidArgumentException("Invalid file type specified: " . $file_type);
		}

		if(!is_string($file_size)) {
			throw new \InvalidArgumentException("Invalid file size specified: " . $file_size);
		}

		$this->allowedFileSizes[$file_type] = $this->convertfileSize($file_size);
		$this->settings['file_max_size'] = $this->allowedFileSizes[$file_type];
	}

	protected function setAllowedMimeTypes($file_type): void 
	{
		if(!isset(self::ALLOWED_MIME_TYPES[$file_type])) {
			throw new \InvalidArgumentException("Invalid file type specified: " . $file_type);
		}

		$this->mimeTypes = self::ALLOWED_MIME_TYPES[$file_type];
	}

	protected function convertfileSize(string $size)
	{
		if(strpos($size, "MB") !== false) {
			$input = explode("MB", $size);
			$unit = "MB";
		}else if(strpos($size, "GB") !== false) {
			$input = explode("GB", $size);
			$unit = "GB";
		}else {
			throw new \InvalidArgumentException("Invalid file size specified: " . $size);
		}

		return $input[0] * self::FILE_SIZES[$unit];
	}

	protected function setDestinationFolder(string $destination_folder): void
	{
		$this->settings['destination_folder'] = $destination_folder;
	}

	protected function setTempFolder(string $temp_folder): void 
{
		$this->settings['temp_folder'] = $temp_folder;
	}

	protected function setTempUrl(string $temp_url): void
	{
		$this->settings['temp_url'] = $temp_url;
	}

	protected function setFinalUrl(string $final_url): void
	{
		$this->settings['final_url'] = $final_url;
	}

	private function resizeImage($resize, $handle)
	{
		if(!isset($resize["width"])) {
			throw new \InvalidArgumentException("Image resize options must have width and/or height values");
		}

		$handle->image_resize = true;
		$handle->image_x = $resize["width"];

		if(isset($resize["height"])) {
			$handle->image_x = $resize["height"];
		}else {
			$handle->image_ratio_y = true;
		}
		
		return $handle;
	}

	protected function verifyOptions(array $options): void
	{
		$this->options = $options;

		foreach($options as $name => $value) {
			if(!isset($this->settings[$name])) {
				throw new \InvalidArgumentException("Invalid option: " . $name);
			}
		}

		if(isset($options['temp_folder'])) {
			$this->setTempFolder($options['temp_folder']);
		}

		if(isset($options['temp_url'])) {
			$this->setTempUrl($options['temp_url']);
		}

		if(isset($options['destination_folder'])) {
			$this->setDestinationFolder($options['destination_folder']);
		}

		if(isset($options['final_url'])) {
			$this->setFinalUrl($options['final_url']);
		}

		if(isset($options['file_type'])) {

			$file_max_size = (isset($options['file_max_size']) ? $options['file_max_size'] : $this->settings['file_max_size']);

			$this->setAllowedMimeTypes($options['file_type']);
			$this->setMaxFileSize($options['file_type'], $file_max_size);
		}
	}

	function getResults()
	{
		return $this->results;
	}

	function singleUpload(array $data, array $options)
	{
		$this->verifyOptions($options);
		
		$handle = $this->processUpload( $this->createUploadInstance(data: $data) );

		if(empty($this->results)) {
			$this->processUploaded($handle);
		}

		return;
	}

	function multipleUpload(array $data, array $options)
	{
		$this->verifyOptions($options);
		
		$files = array();

		foreach ($data as $k => $l) {
			foreach ($l as $i => $v) {
				if (!array_key_exists($i, $files))
					$files[$i] = array();
					$files[$i][$k] = $v;
			}
		}
		
		foreach ($files as $file) {
			
			$handle = $this->processUpload( $this->createUploadInstance(data: $file) );
			$this->processUploaded($handle);
		   
			unset($handle);

		}

		return;
	}

	function createUploadInstance(array $data): Upload
	{
		$handle = new Upload($data); 
		return $handle;
	}

	function processUpload(Upload $handle): Upload
	{
		if ($handle->uploaded) {

			$handle->mime_check = true;
			$handle->dir_auto_create = true;
			$handle->file_safe_name = true;

			$handle->file_max_size = $this->settings['file_max_size'];
			$handle->allowed = $this->mimeTypes;
			$handle->no_script = false;
			
			if(isset($this->options['image_resize'])) {
				$handle = $this->resizeImage($this->options['image_resize'], $handle);
			}

		}else {
			$this->results[] = [
				"status" => 2,
				"type" => "error",
				"message" => "There was an error uploading your file " . $handle->file_src_name . "."
			];
		}

		return $handle;
	}

	function createThumbnail(Upload $handle, $path, $filename)
	{
		$handle = $this->resizeImage([
			"width" => 300
		], $handle);

		$handle->Process($path);

		if ($handle->processed) {
			rename($path . $handle->file_dst_name, $path . "thumb-" . $filename);
		}
	}

	function processUploaded(Upload $handle)
	{
		$path = ROOT . "/" . $this->settings['temp_folder'] . "/";
		$handle->Process($path);

		if ($handle->processed) {

			$uid = generateRandomString(11);
			$name = explode(".", $handle->file_dst_name);
			$ext = end($name);
			
			$new_name = $uid . "." . $ext;
			rename($path . $handle->file_dst_name, $path . $new_name);
			$this->createThumbnail($handle, $path, $new_name);

			$this->results[] = [
				"status" => 1,
				"id" => $uid,
				"filename" => $new_name,
				"alias" => $uid."_".$handle->file_dst_name,
				"temp_url" => $this->settings['temp_url'] . "/" . $new_name,
				"final_url" => $this->settings['final_url'] . "/" . $new_name,
				"width" => $handle->image_dst_x,
				"height" => $handle->image_dst_y,
				"size" => readableFileSize($handle->file_src_size)
			];
		
		}else {
			$error = "There was an error uploading your file: ".$handle->error;
			
			if(strpos($handle->error, "Incorrect type of file") !== false ) {
				$error = "File " . $handle->file_src_name . " is not allowed to be uploaded.";
			}

			if(strpos($handle->error, "too big") !== false ) {
				$error = "Your file " . $handle->file_src_name . " is too big, please resize your file to ".readableFileSize($this->settings['file_max_size'])." before uploading";
			}

			$this->results[] = [
				"status" => 2,
				"error" => 1,
				"type" => "error",
				"message" => $error
			];

		}
	}

}