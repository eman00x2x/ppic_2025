<?php

namespace Main\Model;

use Main\Interfaces\IModel as IModel;

class PropertyImageModel extends \Main\Model implements IModel
{

	function __construct() {
		$this->alias = "pr";
		$this->table = "property_images";
		$this->primary_key = "image_id";
		$this->init();
	}

	function getByPropertyId($id) {

		// Check data integrity
		if($response = $this->checkDataIntegrity(data: ["property_id" => $id])) {
			// data integrity fail
			return $response;
		}

		if($data = $this->getBy("property_id", $this->column['property_id'])) {
			return $data;
		}else {
			return false;
		}

	}

}
