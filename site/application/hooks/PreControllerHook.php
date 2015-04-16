<?php


include APPPATH.'libraries/URLRouteTester.php';

class PreControllerHook {

	private $CI;

	function __construct(){
		$this->CI =& get_instance();
		if (!isset($this->CI->session)) {
			$this->CI->load->library('session');
		}
	}

	public function checkSession(){
		$path			= parse_url($_SERVER['REQUEST_URI'])['path'];
		$tester			= new URLRouteTester($_SERVER['REQUEST_URI']);

		$isLoggedIn		= $this->CI->session->userdata('loginData') != null;
		$isLoginPage	= $tester->testSection('admin') || $tester->testSection('admin/login');
		$isAdminSection	= $tester->getSection(0) == 'admin' && !$isLoginPage;

		if ($isLoginPage && $isLoggedIn){
			redirect('admin/dashboard', 'refresh');
		}
		// If not logged in and any of the admin sections, redirect to login
		else if (!$isLoggedIn && $isAdminSection){
			redirect('admin/login', 'refresh');
		}
	}

}

?>