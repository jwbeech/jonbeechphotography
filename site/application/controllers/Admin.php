<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function renderThumbs(){
		$this->load->model("AdminImageService");
		$percent		= $this->AdminImageService->completePercent();
		$model			= new stdClass();
		$model->busy	= !is_null($percent);
		$model->percent	= $percent;
		$this->load->view("render_view", $model);
	}
	public function doThumbRendering(){
		$this->load->model("AdminImageService");
		$this->AdminImageService->generateThumbsStoreData();
		log_message("info", "Completed");
	}
	public function thumbStatus(){
		$this->load->model("AdminImageService");
		$percent		= $this->AdminImageService->completePercent();
		$model			= new stdClass();
		$model->busy	= !is_null($percent);
		$model->percent	= $percent;
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($model));
	}
}