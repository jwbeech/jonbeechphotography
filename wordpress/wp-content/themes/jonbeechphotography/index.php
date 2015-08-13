<?php
$env 		= "development";

// Dynamic embedding of assets so the config can be shared
$json		= json_decode(file_get_contents(dirname(__FILE__) . "/fileregister.json"), true);
$fileData	= $env == "development" ? $json["development"] : $json["production"];
?>

<!DOCTYPE html>
<html lang="en" ng-app="jonphoto">
<head>
	<meta charset="utf-8">
	<title><?php bloginfo( 'name' ); ?></title>

	<?php include("header.php"); ?>
</head>

<body>
	<div ng-view autoscroll>
		<span>Loading...</span>
	</div>

	<div id="footer">
		<div class="sizingWrapper">
			<h1 id="logo">Jon Beech Photography</h1>
		</div>
	</div>


	<?php include("footer.php"); ?>

</body>

</html>