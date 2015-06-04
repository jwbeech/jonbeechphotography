<?php

include 'application/libraries/SourceImage.php';
include 'application/libraries/ThumbImage.php';
include 'application/libraries/ThumbRow.php';
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
			$result				= new stdClass();
			$result->name		= ImageService::CONVERT_PROCESS;
			$result->percent	= 0;
			$this->db->insert("process", $result);

			// Clear out the database
			$this->db->query("DELETE FROM source_image");
			$this->storeSourceImages();

			$this->db->query("DELETE FROM thumb_image");
			$gap		= 10;
			$height		= 400;
			$pageWidths	= array(1800, 1200/*, 979, 767, 480, 320, 300*/);
			$rows		= $this->generateThumbMemoryData($pageWidths, $height, $gap);
			$this->generateThumbPhysicalData($pageWidths, $rows);

			// Remove the process entry
			$this->db->delete("process", array('name' => ImageService::CONVERT_PROCESS));
		}
	}

	public function completePercent(){
		$arResult	= $this->db->get("process", array('name' => ImageService::CONVERT_PROCESS));
		$result		= $arResult->result();
		return count($result) > 0 ? $result[0]["percent"] : null;
	}

	public function fetchPage($pageNumber, $limit){
		$offset		= ($pageNumber - 1) * $limit;
		// First select the rows, store ids and min row
		$rows		= $this->db->get("thumb_row", $limit, $offset)->result();
		$rowIds		= array();
		$minRow		= 5000000000000;
		$rowMap		= array();
		foreach ($rows as $row){
			$rowIds[]			= $row->id;
			$minRow				= min($minRow, $row->number);
			$rowMap[$row->id]	= $row->number;
		}

		$this->db->where_in("thumb_row_id", $rowIds);
		$images		= $this->db->get("thumb_image")->result();
		// Generate results of rows
		$rows		= array();
		foreach ($images as $image){
			$rowNumber	= $rowMap[$image->thumb_row_id];
			$rowIndex	= $rowNumber - $minRow;
			// Create row mapper
			if (!isset($rows[$rowIndex])){
				$rows[$rowIndex] = array();
			}
			// Create row size mapper
			if (!isset($rows[$rowIndex][$image->page_width])){
				$rows[$rowIndex][$image->page_width] = array();
			}
			$rows[$rowIndex][$image->page_width][] = $image;
		}
		return $rows;
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

	private function generateThumbMemoryData($pageWidths, $magicHeight, $gap){
		$pageWidth		= $pageWidths[0];

		// Get the source images
		$imageResult	= $this->db->get('source_image');
		$imageList		= $imageResult->result();

		log_message("info", "Generating thumbs | \$width: $pageWidth, \$gap: $gap, \$magicHeight: $magicHeight");

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
				if ($imgObj->width / 2 < ($pageWidth - $totalGap) - $row->calcWidth){
					$totalImages++;
					array_shift($imageList);
					$row->images[]	= $imgObj;
					$row->calcWidth	+= $imgObj->width;
				}
				else{
					if (count($row->images) == 0) throw new Exception("Failed to add even 1 image :(");
					break;
				}
				if ($row->calcWidth >= $pageWidth){
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
			$n 			= ($pageWidth - $totalGap) / $row->calcWidth;
			foreach ($row->images as $imgObj){
				$imgObj->width	= $imgObj->width * $n;
				$imgObj->height	= $imgObj->height * $n;
			}
		}

		$magicRatio		= $magicHeight / $pageWidth;

		// Lets create an image set for every page size
		for ($r = 0; $r < count($rows); $r++) {
			$row				= $rows[$r];

			// Move the current width into its own set
			$row->$pageWidth	= $row->images;

			// Now run a loop for each other width
			for ($w = 1; $w < count($pageWidths); $w++){
				$currPageWidth		= $pageWidths[$w];
				$currArr			= array();
				$currHeight			= $currPageWidth * $magicRatio;

				// Now run through the image list an generate new rows for this size
				for ($i = 0; $i < count($row->images); $i++) {
					$imgObj 		= $row->images[$i];
					$newImg			= new stdClass();
					$newImg->id		= $imgObj->id;
					$newImg->url	= $imgObj->url;
					// Set the height then scale the width proportionally
					$newImg->height	= $currHeight;
					$newImg->width	= $imgObj->width * ($currHeight / $imgObj->height);
					$currArr[]		= $newImg;
				}

				// Assign to its own property
				$row->$currPageWidth	= $currArr;
			}
		}

		return $rows;
	}

	private function generateThumbPhysicalData($pageWidths, $rows){
		// Now that all the data has been manipulated lets create database entries and images for every one
		$totalImages			= 0;

		// Lets create an image set for every page size
		for ($r = 0; $r < count($rows); $r++) {
			$row			= $rows[$r];
			$totalImages	+= count($pageWidths) * count($row->images);
		}


		// First clear all the folders
		foreach ($pageWidths as $currWidth){
			$output = "static/images/photos/$currWidth/";
			FileUtil::recursiveRemove($output);
			mkdir($output);
		}

		// Now lets get stuck into generation
		$cnt	= 0;
		for ($r = 0; $r < count($rows); $r++) {
			$row 			= $rows[$r];
			$dbRow			= new ThumbRow();
			$dbRow->number	= $r;
			$this->db->insert("thumb_row", $dbRow);
			$rowId			= $this->db->insert_id();

			for ($w = 0; $w < count($pageWidths); $w++){
				$currPageWidth		= $pageWidths[$w];
				$currArr			= $row->$currPageWidth;

				for ($i = 0; $i < count($currArr); $i++) {
					$imgObj			= $currArr[$i];
					// Fix sizes
					$imgObj->width	= floor($imgObj->width);
					$imgObj->height	= floor($imgObj->height);

					// First generate the image
					$fileName		= StringUtil::padNumber($r) . "_" . StringUtil::padNumber($i, 2) . ".jpg";
					$outputFile 	= "static/images/photos/$currPageWidth/" . $fileName;

					log_message("info", "Resizing and outputting file: $outputFile");

					$image 			= new \Eventviva\ImageResize(substr($imgObj->url, 1));
					$image->resize($imgObj->width, $imgObj->height);
					$image->save($outputFile, IMAGETYPE_JPEG, 100);


					// Then the DB entry
					$dbImg					= new ThumbImage();
					$dbImg->source_image_id	= $imgObj->id;
					$dbImg->page_width		= $currPageWidth;
					$dbImg->width			= $imgObj->width;
					$dbImg->height			= $imgObj->height;
					$dbImg->url				= "/" . $outputFile;
					$dbImg->thumb_row_id	= $rowId;
					$this->db->insert("thumb_image", $dbImg);

					// Update the percent
					$cnt++;
					$process			= new stdClass();
					$process->name		= ImageService::CONVERT_PROCESS;
					$process->percent	= $cnt / $totalImages * 100;
					$this->db->update("process", $process);
				}

			}
		}
	}


}
?>