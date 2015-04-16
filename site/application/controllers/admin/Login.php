<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include APPPATH.'controllers/BaseController.php';

class Login extends BaseController {

	function __construct() {
		parent::__construct();
		$this->load->model('user','',TRUE);
	}

	function index() {
		if ($this->isGET()){
			$this->load->helper(array('form'));
			$this->load->view('/admin/login_view.php');
		}
		else if ($this->isPOST()){
			//This method will have the credentials validation
			$this->load->library('form_validation');

			$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');

			if ($this->form_validation->run() == FALSE) {
				// Field validation failed. User redirected to login page
				$this->load->view('/admin/login_view.php');
			}
			else {
				// Go to private area
				redirect('admin/dashboard', 'refresh');
			}
		}
	}

	function check_database($password) {
		//Field validation succeeded.  Validate against database
		$username	= $this->input->post('username');
		//query the database
		$result		= $this->user->login($username, $password);

		if ($result) {
			$userData = array();
			foreach ($result as $row) {
				$userData = array('id' => $row->id, 'username' => $row->username);
				$this->session->set_userdata('loginData', $userData);
			}
			return true;
		}
		else {
			$this->form_validation->set_message('check_database', 'Invalid username or password');
			return false;
		}
	}
}

?>