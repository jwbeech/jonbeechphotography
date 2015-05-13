<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Jonathan Beech Photography</title>

	<?php
	$environment	= "development";
	$version		= "0.1";
	$pageName		= basename(__FILE__, ".php");
	$developmentCSS	= array(
		"/static/css/vendor/bootstrap.css",
		"/static/css/vendor/fonts.css",
		"/static/css/main.css"
	);
	$productionCSS	= array(
		"/static/css-min/main.css"
	);
	include "includes/header.php";
	?>

</head>

<body class="rendering">
	<h1>Thumb rendering page</h1>
	<hr />
	<button type="button" class="btn btn-default" id="renderBtn" disabled>Render Thumbs Now</button>
	<div id="percent">
	</div>
</body>
</html>