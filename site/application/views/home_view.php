<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Jonathan Beech Photography</title>

	<?php
	$environment	= "production";
	$version		= "0.1";
	$pageName		= basename(__FILE__, ".php");
	$developmentCSS	= array(
		"/static/css/vendor/bootstrap.css",
		"/static/css/main.css"
	);
	$productionCSS	= array(
		"/static/css-min/main.css"
	);
	include "includes/header.php";
	?>
</head>

<body>
	<div id="imageTile" class="sizingWrapper clearfix">
		<?php
		foreach ($rows as $row){
			for ($i = 0; $i < count($row); $i++){
				$imgObj = $row[$i];

				$output	= "";
				if ($i == 0){
					$output = $output . '<div class="imgHolder"';
				}
				else{
					$output = $output . '<div class="imgHolder middleImg"';
				}
				$output = $output . ' style="width:' . $imgObj->width . 'px; height:' . $imgObj->height . 'px; background-image:url(/' . $imgObj->src . ') ">';
				$output = $output . '</div>';

				echo $output;
			}
		}
		?>
	</div>
	<div id="footer">
		<div class="sizingWrapper">
			<h1 id="logo">Jon Beech Photography</h1>
		</div>
	</div>
</body>
</html>