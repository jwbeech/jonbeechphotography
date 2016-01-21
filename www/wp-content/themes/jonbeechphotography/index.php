<?php
$env 		= "development";

// Dynamic embedding of assets so the config can be shared
$json		= json_decode(file_get_contents(dirname(__FILE__) . "/fileregister.json"), true);
$fileData	= $env == "development" ? $json["development"] : $json["production"];

$fonts		= "http://fonts.googleapis.com/css?family=";
//$fonts		.= "Raleway:100,200,300,400,500,600,700,900,800";
$fonts		.= "Open+Sans:300,400,700";

$fileData["css"][]	= $fonts;
?>

<!DOCTYPE html>
<html lang="en" ng-app="jonphoto">
<head>
	<meta charset="utf-8">
	<title><?php bloginfo( 'name' ); ?></title>

	<?php include("header.php"); ?>
</head>

<body>

	<div ui-view>
		<img src="/wp-content/themes/jonbeechphotography/images/gears.svg" class="gears" />
	</div>


	<?php include("footer.php"); ?>

</body>

</html>