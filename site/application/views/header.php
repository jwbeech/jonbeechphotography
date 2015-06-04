<?php
$env	= "development";

// Dynamic embedding of assets so the config can be shared
$json	= json_decode(file_get_contents("static/fileregister.json"), true);
$data	= $env == "development" ? $json["development"] : $json["production"];
foreach ($data["css"] as $css)	echo "<link rel='stylesheet' type='text/css' href='$css'>\r";
foreach ($data["js"] as $js)	echo "<script type='text/javascript' src='$js'></script>\r";
?>