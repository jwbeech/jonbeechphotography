<?php

include 'application/libraries/SourceImage.php';
include 'application/libraries/ThumbImage.php';
include 'application/libraries/StringUtil.php';
include 'application/libraries/FileUtil.php';
include 'application/libraries/ProcessPercent.php';
include 'application/libraries/ImageResize.php';

Class ImageService extends CI_Model
{
	const CONVERT_PROCESS = "convertImages";

	/*----------------------------------------------+
	| PUBLIC METHODS								|
	+----------------------------------------------*/
	public function generateThumbsStoreData(){
		if (is_null($this->completePercent())){
			$process = new ProcessPercent(ImageService::CONVERT_PROCESS);
			$this->db->insert("process", $process->getZeroPercent());

			// Clear out the database
			$this->db->query("DELETE FROM source_image");
			$this->storeSourceImages();

			$this->db->query("DELETE FROM thumb_image");
			$gap				= 10;
			$heightRatio		= 400 / 1800;
			$sizes				= array(1800, 1200);/*, 979, 676, 480, 320, 300);*/
			$process->totalSets	= count($sizes);

			for ($i = 0; $i < count($sizes); $i++){
				$width 		= $sizes[$i];
				$this->generateThumbs($width, $gap, floor($width * $heightRatio), $process);

				$this->db->update("process", $process->getSetPercent($i));
			}
			// Remove the process entry
			$this->db->delete("process", array('name' => ImageService::CONVERT_PROCESS));
		}
	}

	public function completePercent(){
		$arResult	= $this->db->get("process", array('name' => ImageService::CONVERT_PROCESS));
		$result		= $arResult->result();
		return count($result) > 0 ? $result[0] : null;
	}

	/*----------------------------------------------+
	| PRIVATE METHODS								|
	+----------------------------------------------*/
	private function storeSourceImages(){
		$images 		= glob("static/images/photos/source/*.jpg");

		foreach($images as $imagePath) {
			$meta				= getimagesize($imagePath);
			// Create a DB entry for each
			$sourceImg			= new SourceImage();
			$sourceImg->width	= $meta[0];
			$sourceImg->height	= $meta[1];
			$sourceImg->url		= "/".$imagePath;
			$this->db->insert("source_image", $sourceImg);
		}
	}

	private function generateThumbs($width, $gap, $magicHeight, $process){
		// Get the source images
		$imageResult	= $this->db->get('source_image');
		$imageList		= $imageResult->result();

		log_message("info", "Generating thumbs | \$width: $width, \$gap: $gap, \$magicHeight: $magicHeight");

		// Shuffle the list into portrait and landscape alternating
		log_message("info", "Shuffling");
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
		log_message("info", "Breaking into rows");
		$rows			= array();
		$cnt			= 0;
		$max			= count($imageList) * 2;
		$totalImages	= 0;
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
				$imgObj->width	= $imgObj->width * ($magicHeight / $imgObj->height);
				$imgObj->height	= $magicHeight;

				// If the gap left to fill is greater than the half the image width
				// Add the current image
				$totalGap	= $gap * count($row->images);
				if ($imgObj->width / 2 < ($width - $totalGap) - $row->calcWidth){
					$totalImages++;
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
		log_message("info", "Fixing widths");
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
		FileUtil::recursiveRemove($output);
		mkdir($output);
		$cnt	= 0;

		for ($r = 0; $r < count($rows); $r++){
			$row = $rows[$r];
			for ($i = 0; $i < count($row->images); $i++){
				$imgObj		= $row->images[$i];
				$fileName	= StringUtil::padNumber($r) . "_" . StringUtil::padNumber($i, 2) . ".jpg";
				$outputFile = $output . $fileName;
				log_message("info", "Resizing and outputting file: $imgObj->url -> $outputFile");
				$image 		= new \Eventviva\ImageResize(substr($imgObj->url, 1));
				$image->resize($imgObj->width, $imgObj->height);
				$image->save($outputFile, IMAGETYPE_JPEG, 100);

				// Add the data base entry
				$thumb					= new ThumbImage();
				$thumb->source_image_id	= $imgObj->id;
				$thumb->page_width		= $width;
				$thumb->width			= $imgObj->width;
				$thumb->height			= $imgObj->height;
				$thumb->url				= "/" . $outputFile;
				$thumb->row_number		= $r;
				$this->db->insert("thumb_image", $thumb);

				$this->db->update("process", $process->getImagePercent($cnt, $totalImages));
				$cnt++;
			}
		}
	}


}
?>