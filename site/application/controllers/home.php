<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include 'application/controllers/BaseController.php';

class Home extends BaseController {

	public function index() {
		$this->load->view("home_view");
	}

	public function fetchPage($page){
		$rowsPerPage	= 5;

	}

	public function fetchThumbs(){
		$size		= $this->input->get("size");
		$images 	= glob("static/images/photos/$size/*.jpg");
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

		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($json));
	}

	public function renderThumbs(){
		$this->load->model("ImageService");
		$percent		= $this->ImageService->completePercent();
		$model			= new stdClass();
		$model->busy	= !is_null($percent);
		$model->percent	= $percent;
		$this->load->view("render_view", $model);
	}

	public function doThumbRendering(){
		$this->load->model("ImageService");
		$this->ImageService->generateThumbsStoreData();
		log_message("info", "Completed");
	}
	public function thumbStatus(){
		$this->load->model("ImageService");
		$percent		= $this->ImageService->completePercent();
		$model			= new stdClass();
		$model->busy	= !is_null($percent);
		$model->percent	= $percent;
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($model));
	}
}