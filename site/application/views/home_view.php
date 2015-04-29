<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Jonathan Beech Photography</title>

	<link href="/static/css/main.css" rel="stylesheet">
	<link href="/static/css/bootstrap.css" rel="stylesheet">

	<script type="text/javascript" src="/static/js/vendor/jquery-2.1.3.js"></script>
	<script type="text/javascript" src="/static/js/vendor/underscore.js"></script>
	<script type="text/javascript" src="/static/js/home.js"></script>

	<script type="text/javascript">
		var images = <?php echo json_encode($images); ?>;
	</script>

</head>

<body>
	<div id="imageTile">

	</div>
</body>
</html>