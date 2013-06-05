<!DOCTYPE HTML>
<html>
<head>
	<title>Where's CaBi? - A lightweight webapp to find closest Capital Bikeshare stations</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="img/favicon.ico" />
	<link rel="apple-touch-icon-precomposed" href="apple-touch-icon.png"/>
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="apple-touch-icon-72-72.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="apple-touch-icon-114-114.png" />
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="apple-touch-icon-144-144.png" />
	<link rel="apple-touch-startup-image" href="apple-touch-startup.png" />
	<link rel="apple-touch-startup-image" href="apple-touch-startup-114-114.png" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href='style.css' rel='stylesheet' type='text/css'>
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<![endif]-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
</head>

<body>

<header>
<h1><img class="header-img" src="img/bike.svg" />WHERE IS CABI?</h1>
<h4>A lightweight webapp to find the closest Capital Bikeshare locations in Washington, DC.</h4>
<h6>Developed by Matt Nelson-Abell</h6>
<h6>Key: miles / bikes / docks / station name</h6>
<h6 class="standalone-notification-device"></h6>
</header>


<div class="results">
	<a class="refresh clearfix" href="javascript:void(0);" style="display:none;">Tap to refresh</a>
	<div class="row key clearfix">
		<div class="distance col">MI</div>
		<div class="bikes smallcol">B</div>
		<div class="docks smallcol">D</div>
		<div class="name bigcol">STATION</div>
	</div>
	<div class="row result geo-notification clearfix">
		<p>To use this app, you must have location-based features enabled for your browser.</p>
		<p class="geo-notification-device"></p>
	</div>
</div>

<footer>
</footer>

<script src="js/geo.js" ></script>
</body>
</html>