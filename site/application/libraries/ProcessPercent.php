<?php

class ProcessPercent
{
    var $name;
    var $totalSets;
	var $currentSet = 0;

	function __construct($name) {
		$this->name = $name;
	}

	public function getZeroPercent(){
		$result				= new stdClass();
		$result->name		= $this->name;
		$result->percent	= 0;
		return $result;
	}
	public function getSetPercent($currentSet){
		$this->$currentSet	= $currentSet;
		$result				= new stdClass();
		$result->name		= $this->name;
		$result->percent	= ($currentSet + 1) / $this->totalSets * 100;
		return $result;
	}
	public function getImagePercent($imgCnt, $totalImages){
		$result				= new stdClass();
		$result->name		= $this->name;
		$result->percent	= $this->currentSet / $this->totalSets * 100;
		$portion			= 1 / $this->totalSets * 100;
		$miniPercent		= (($imgCnt + 1) / $totalImages) * $portion;
		$result->percent	+= $miniPercent;
		return $result;
	}
}
?>