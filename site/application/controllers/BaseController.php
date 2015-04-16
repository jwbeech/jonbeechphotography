<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BaseController extends CI_Controller
{
	function __construct() {
		parent::__construct();
	}

	function isPOST()	{ return $_SERVER['REQUEST_METHOD'] == 'POST'; }
	function isGET()	{ return $_SERVER['REQUEST_METHOD'] == 'GET'; }
}

?>