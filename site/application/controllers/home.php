<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include 'application/controllers/BaseController.php';
include 'application/libraries/ImageResize.php';

class Home extends BaseController {

	public function index() {
		$images 	= glob("static/images/photos/1800/*.jpg");
		$json		= array();

		foreach($images as $imagePath) {
			// Build image object
			$meta			= getimagesize($imagePath);
			$obj			= new stdClass();
			$obj->width		= $meta[0];
			$obj->height	= $meta[1];
			$obj->src		= $imagePath;

			// Figure out the row and number
			$parts 			= explode(".", basename($imagePath));
			$numberParts	= explode("_", $parts[0]);
			$rowNum			= $numberParts[0] * 1;
			$imgNum			= $numberParts[1] * 1;
			//$this->line("\$row: $rowNum, \$imgNum: $imgNum");
			if (!array_key_exists($rowNum, $json)){
				$json[$rowNum]	= array();
			}

			// Push into the right place
			$json[$rowNum][$imgNum]	= $obj;
		}

		$model = array(
			'rows' => $json
		);
		$this->load->view('home_view', $model);
	}

	public function renderThumbs(){
		$this->generateThumbs(1800, 10, 400);
		$this->line("Completed");
	}

	/*
	UTILS
	--------------------------------------------
	*/
	private function fetchImages($directory){
		$images 	= glob($directory . "*.jpg");
		$json		= array();

		foreach($images as $imagePath) {
			$meta				= getimagesize($imagePath);
			$obj				= new stdClass();
			$obj->width			= $meta[0];
			$obj->height		= $meta[1];
			$obj->originalWidth	= $meta[0];
			$obj->originalHeight= $meta[1];
			$obj->url			= $imagePath;
			$json[]				= $obj;
		}
		return $json;
	}

	private function generateThumbs($width, $gap, $magicHeight){
		// Build list of images to work with
		$images 		= glob("static/images/photos/source/*.jpg");
		$imageList		= array();

		foreach($images as $imagePath) {
			$meta				= getimagesize($imagePath);
			$obj				= new stdClass();
			$obj->width			= $meta[0];
			$obj->height		= $meta[1];
			$obj->originalWidth	= $meta[0];
			$obj->originalHeight= $meta[1];
			$obj->url			= $imagePath;
			$imageList[]		= $obj;
		}

		$this->line("Generating thumbs | \$width: $width, \$gap: $gap, \$magicHeight: $magicHeight");

		// Shuffle the list into portrait and landscape alternating
		$this->line("Shuffling");
		$landscape	= array();
		$portrait	= array();
		foreach ($imageList as $imgObj){
			if ($imgObj->width > $imgObj->height){
				$landscape[] = $imgObj;
			}
			else{
				$portrait[] = $imgObj;
			}
		}

		$imageList = array();
		while (count($landscape) > 0 || count($portrait) > 0){
			if (count($landscape) > 0) $imageList[] = array_shift($landscape);
			if (count($portrait) > 0) $imageList[] = array_shift($portrait);
		}

		// Define rows with the logical distribution
		// Some may be over some under the width
		$this->line("Breaking into rows");
		$rows	= array();
		$cnt	= 0;
		$max	= count($imageList) * 2;
		while(count($imageList) > 0){
			$row 				= new stdClass();
			$row->images		= array();
			$row->calcWidth		= 0;
			$row->incomplete	= false;

			while(true){
				if (count($imageList) == 0){
					$row->incomplete = true;
					break;
				}
				$imgObj		= $imageList[0];

				// Set the image to use a predefined start height
				$imgObj->width	= $imgObj->originalWidth * ($magicHeight / $imgObj->originalHeight);
				$imgObj->height	= $magicHeight;

				// If the gap left to fill is greater than the half the image width
				// Add the current image
				$totalGap	= $gap * count($row->images);
				if ($imgObj->width / 2 < ($width - $totalGap) - $row->calcWidth){
					array_shift($imageList);
					$row->images[]	= $imgObj;
					$row->calcWidth	+= $imgObj->width;
				}
				else{
					if (count($row->images) == 0) throw new Exception("Failed to add even 1 image :(");
					break;
				}
				if ($row->calcWidth >= $width){
					break;
				}

				$cnt++;
				if ($cnt > $max) throw new Exception("Too much looping");
			}

			if (!$row->incomplete) $rows[] = $row;
		}

		// Fix the image widths
		$this->line("Fixing widths");
		foreach ($rows as $row){
			$totalGap	= $gap * (count($row->images) - 1);
			$n 			= ($width - $totalGap) / $row->calcWidth;
			foreach ($row->images as $imgObj){
				$imgObj->width	= $imgObj->width * $n;
				$imgObj->height	= $imgObj->height * $n;
			}
		}

		// Now lets build the images in order
		$output = "static/images/photos/$width/";
		$this->recursiveRemove($output);
		mkdir($output);
		$cnt	= 0;
		for ($r = 0; $r < count($rows); $r++){
			$row = $rows[$r];
			for ($i = 0; $i < count($row->images); $i++){
				$imgObj		= $row->images[$i];
				$fileName	= $this->padNumber($r) . "_" . $this->padNumber($i, 2) . ".jpg";
				$outputFile = $output . $fileName;
				$this->line("Resizing and outputting file: $imgObj->url -> $outputFile");
				$image = new \Eventviva\ImageResize($imgObj->url);
				$image->resize($imgObj->width, $imgObj->height);
				$image->save($outputFile, IMAGETYPE_JPEG, 100);
			}
		}
	}

	private function padNumber($cnt, $totalLen = 5){
		$result 	= $cnt;
		for ($i = strlen($cnt); $i < $totalLen; $i++){
			$result	= "0".$result;
		}
		return $result;
	}

	private function recursiveRemove($dir) {
		$structure = glob(rtrim($dir, "/").'/*');
		if (is_array($structure)) {
			foreach($structure as $file) {
				if (is_dir($file)) $this->recursiveRemove($file);
				else if (is_file($file)) unlink($file);
			}
		}
		try{
			$this->line("Removing dir $dir");
			rmdir($dir);
		}
		catch(Exception $e){

		}
	}

	private function line($message){
		echo "----> ".$message . "<br />";
	}
}