<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Jonathan Beech Photography</title>

	<?php
	$env	= "development";

	// Dynamic embedding of assets so the config can be shared
	$json	= json_decode(file_get_contents("../../static/fileregister.json"), true);
	$data	= $env == "development" ? $json["development"] : $json["production"];
	foreach ($data["css"] as $css)	echo "<link rel='stylesheet' type='text/css' href='$css'>\r";
	foreach ($data["js"] as $js)	echo "<script type='text/javascript' src='$js'></script>\r";
	?>

</head>

<body>
	<div id="imageTile" class="sizingWrapper clearfix">
	</div>
	<div id="footer">
		<div class="sizingWrapper">
			<h1 id="logo">Jon Beech Photography</h1>
		</div>
	</div>
</body>
</html>