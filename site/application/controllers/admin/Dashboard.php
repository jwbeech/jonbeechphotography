<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include APPPATH.'controllers/BaseController.php';

class Dashboard extends BaseController
{
	function index(){
		$this->load->view('/admin/dashboard_view.php');
	}
}

?>