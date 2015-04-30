<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Jonathan Beech Photography</title>

	<link href="/static/css/bootstrap.css" rel="stylesheet">
	<link href="/static/css/main.css" rel="stylesheet">

	<script type="text/javascript" src="/static/js/vendor/jquery-2.1.3.js"></script>
	<script type="text/javascript" src="/static/js/vendor/underscore.js"></script>
	<script type="text/javascript" src="/static/js/home.js"></script>

</head>

<body>
	<div id="imageTile" class="sizingWrapper clearfix">
		<?php
		foreach ($rows as $row){
			for ($i = 0; $i < count($row); $i++){
				if ($i == 0){
					echo '<div class="imgHolder">';
				}
				else{
					echo '<div class="imgHolder middleImg">';
				}
				$imgObj = $row[$i];
				echo "<img src='$imgObj->src' width='$imgObj->width' height='$imgObj->height' />";
				echo '</div>';
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