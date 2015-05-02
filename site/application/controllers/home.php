<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include 'application/controllers/BaseController.php';

class Home extends BaseController {

	public function index() {
		$images 	= glob("static/images/photos/1800/*.jpg");
		$json		= array();

		foreach($images as $imagePath) {
			// Build image object
			$meta			= getimagesize($imagePath);
			$obj			= new stdClass();
			$obj->width		= $meta[0];
			$obj->height	= $meta[1];
			$obj->src		= $imagePath;

			// Figure out the row and number
			$parts 			= explode(".", basename($imagePath));
			$numberParts	= explode("_", $parts[0]);
			$rowNum			= $numberParts[0] * 1;
			$imgNum			= $numberParts[1] * 1;
			//$this->line("\$row: $rowNum, \$imgNum: $imgNum");
			if (!array_key_exists($rowNum, $json)){
				$json[$rowNum]	= array();
			}

			// Push into the right place
			$json[$rowNum][$imgNum]	= $obj;
		}

		$model = array(
			'rows' => $json
		);
		$this->load->view('home_view', $model);
	}

	public function admin(){

	}

	public function renderThumbs(){
		$this->load->model("ImageService");
		$this->ImageService->generateThumbsStoreData();
		log_message("info", "Completed");
	}
}