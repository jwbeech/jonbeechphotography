<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include APPPATH.'controllers/BaseController.php';

class Home extends BaseController {

	public function index()
	{
		$directory	= "static/images/photos/";
		$images 	= glob($directory . "*.jpg");
		$json		= array();

		foreach($images as $imagePath) {
			$meta				= getimagesize($imagePath);
			$obj				= new stdClass();
			$obj->width			= $meta[0];
			$obj->height		= $meta[1];
			$obj->originalWidth	= $meta[0];
			$obj->originalHeight= $meta[1];
			$obj->url			= "/".$imagePath;
			$json[]				= $obj;
			//echo $meta['width'];
			//print_r($meta);
			//print_r($obj);
			//echo '<br />';
		}

		$model = array(
			'images' => $json
		);
		$this->load->view('home_view', $model);
	}
}