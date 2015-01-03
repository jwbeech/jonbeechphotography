<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Dashboard extends AuthenticatedController
{
	function index(){
		$this->load->view('/admin/dashboard_view.php');
	}
}

?>