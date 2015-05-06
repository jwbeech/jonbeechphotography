<?php
// From here down the header rendering should never change as it is based on the model data
$buildNumber	= $environment == "production" ? $version : microtime(true) * 10000;
$jsPrefix		= $environment == "production" ? "/static/js-min" : "/static/js";

// Render CSS Imports
$css			= $environment == "production" ? $productionCSS : $developmentCSS;
foreach ($css as $file){
	echo "<link rel='stylesheet' type='text/css' href='$file?build=$buildNumber'>";
}
?>

<!-- Javascript -->
<script type="text/javascript" src="<?php echo $jsPrefix?>/vendor/require.js?build=<?php echo $buildNumber; ?>"></script>
<script type="text/javascript">
	window.jsPrefix		= "<?php echo $jsPrefix; ?>";
	window.buildNumber	= "<?php echo $buildNumber; ?>";

	// Load the common code, it will be config
	// For production it will be config plus all the frameworks defined
	require([jsPrefix + "/common.js?build=" + buildNumber], function(){
		require(["frameworks"], function(){
			require(["<?php echo $pageName; ?>"]);
		});
	});
</script>