<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Jonathan Beech Photography</title>

	<link href="/static/css/bootstrap.css" rel="stylesheet">
	<link href="/static/css/main.css" rel="stylesheet">

	<script type="text/javascript" src="/static/js/vendor/require.js"></script>
	<script type="text/javascript">
		require(["static/js/require_config"], function(){
			require(["home"])
		});
	</script>
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