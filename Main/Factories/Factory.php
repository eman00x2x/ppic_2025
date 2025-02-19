<?php

namespace EO\Factories;

class Factory {

	static function DBO() {
		static $instance;

		if (!is_object($instance)) {
			$instance = Factory::_createDBO();
		}
		return $instance;
	}

	static function Document() {

		static $instance;
		if (!is_object($instance)) {
			$instance = Factory::_createDocument();
		}
		return $instance;
	}

	static function Validator() {
		static $instance;
		if (!is_object($instance)) {
			$instance = Factory::_createValidator();
		}
		return $instance;
	}

	static function Pagination() {
		static $instance;
		if (!is_object($instance)) {
			$instance = Factory::_createPagination();
		}
		return $instance;
	}

	static function HttpHeader($status, $description = null) {
		static $instance;
		if (!is_object($instance)) {
			$instance = Factory::_createHeaderStatus($status,$description);
		}
		return $instance;
	}

	static function FileUpload() {
		static $instance;
		if (!is_object($instance)) {
			$instance = Factory::_createFileUpload();
		}
		return $instance;
	}

	static function Cache() {
		static $instance;
		if (!is_object($instance)) {
			$instance = Factory::_createCache();
		}
		return $instance;
	}

	static function _createCache() {
		$cache = new \EO\Http\Cache;
		return $cache;
	}

	static function _createFileUpload() {
		$fileUpload = new \EO\Http\FileUpload;
		return $fileUpload;
	}

	static function _createHeaderStatus($status, $description) {
		$headers = new \EO\Http\HttpHeaders;
		return $headers;
	}

	static function _createDBO() {
		$db = new \EO\Database\DBModel;
		return $db;
	}

	static function _createDocument() {
		$document = new \EO\View\Document;
		return $document;
	}

	static function _createValidator() {
		$validator = new \EO\Validation\Validator;
		return $validator;
	}

	static function _createPagination() {
		$paginate = new \EO\Database\Pagination;
		return $paginate;
	}

}

?>
