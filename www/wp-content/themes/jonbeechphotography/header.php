<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

<!--
<link rel="apple-touch-icon-precomposed" sizes="57x57" href="images/favicon/apple-touch-icon-57x57.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/favicon/apple-touch-icon-114x114.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/favicon/apple-touch-icon-72x72.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/favicon/apple-touch-icon-144x144.png" />
<link rel="apple-touch-icon-precomposed" sizes="60x60" href="images/favicon/apple-touch-icon-60x60.png" />
<link rel="apple-touch-icon-precomposed" sizes="120x120" href="images/favicon/apple-touch-icon-120x120.png" />
<link rel="apple-touch-icon-precomposed" sizes="76x76" href="images/favicon/apple-touch-icon-76x76.png" />
<link rel="apple-touch-icon-precomposed" sizes="152x152" href="images/favicon/apple-touch-icon-152x152.png" />
<link rel="icon" type="image/png" href="images/favicon/favicon-196x196.png" sizes="196x196" />
<link rel="icon" type="image/png" href="images/favicon/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/png" href="images/favicon/favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="images/favicon/favicon-16x16.png" sizes="16x16" />
<link rel="icon" type="image/png" href="images/favicon/favicon-128.png" sizes="128x128" />
<meta name="application-name" content="&nbsp;"/>
<meta name="msapplication-TileColor" content="#FFFFFF" />
<meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png" />
<meta name="msapplication-square70x70logo" content="images/favicon/mstile-70x70.png" />
<meta name="msapplication-square150x150logo" content="images/favicon/mstile-150x150.png" />
<meta name="msapplication-wide310x150logo" content="images/favicon/mstile-310x150.png" />
<meta name="msapplication-square310x310logo" content="images/favicon/mstile-310x310.png" />

<meta property="og:title" content="Left & Right" />
<meta property="og:description" content="We're a front-end development company, founded by two brothers and based in beautiful Cape Town. We specialise in web & mobile apps." />
<meta property="og:type" content="website" />
<meta property="og:url" content="http://www.leftandright.co.za/" />
<meta property="og:image" content="http://www.leftandright.co.za/images/logoblack.gif" />
<meta property="og:image:type" content="image/gif" />
<meta property="og:image:width" content="200" />
<meta property="og:image:height" content="200" />
-->


<?php
// render out css files
foreach ($fileData['css'] as $cssPath){
	echo "<link rel='stylesheet' href='$cssPath' />\r";
}
?>