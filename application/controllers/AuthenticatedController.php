<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start(); // we need to call PHP's session object to access it through CI

class AuthenticatedController extends BaseController
{
	function __construct() {
		parent::__construct();

		if($this->session->userdata('userData')) {
			// Do nothing
		}
		else {
			//If no session, redirect to login page
			redirect('admin/login', 'refresh');
		}
	}
}

?>