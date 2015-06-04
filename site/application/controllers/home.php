<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index() {
		$this->load->view("home_view");
	}

	public function fetchPage($pageNumber){
		$this->load->model("ImageService");
		if (!isset($pageNumber)) $pageNumber = 1;
		$rows = $this->ImageService->fetchPage($pageNumber, 5);
		$this->renderJSON($rows);
	}

	private function renderJSON($json){
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($json));
	}
}