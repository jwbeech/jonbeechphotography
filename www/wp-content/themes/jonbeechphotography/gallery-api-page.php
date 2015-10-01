<?php
/**
 * Template Name: Gallery API Page
 */

include(dirname(__FILE__) . "/utils/ImageFetcher.php");

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$result 	= new stdClass();
$imageAPI	= new ImageFetcher();

if (isset($_GET['call'])){
	switch($_GET['call']){
		case 'api_images':
			$imageAPI->route_getImages();
			$result = $imageAPI->result;
			break;
		case 'api_page_number':
			$imageAPI->route_getPageNumber();
			$result = $imageAPI->result;
			break;
		default:
			$result->message = 'Call parameter "' . $_GET['call'] . '" not supported. ';
			break;
	}
}
else{
	$result->message = "GET: 'call' parameter not set";
}


header("Content-Type:application/json");
echo json_encode($result);
