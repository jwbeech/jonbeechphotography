<?php

Class ImageService extends CI_Model
{
	/*----------------------------------------------+
	| PUBLIC METHODS								|
	+----------------------------------------------*/
	public function fetchPage($pageNumber, $limit){
		$pageNumber	= $pageNumber * 1;
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

		$model 					= new stdClass();
		$model->rows			= $rows;
		$model->pageNumber		= $pageNumber;
		$model->offset			= $offset;
		$model->limit			= $limit;
		$model->totalEntries	= $this->db->count_all("thumb_row");
		$model->totalPages		= ceil($model->totalEntries / $limit);
		return $model;
	}


}
?>