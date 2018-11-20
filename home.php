<!DOCTYPE html>
<html lang="en">

<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(E_ALL & ~E_NOTICE);
?>

<head>
	<meta charset="utf-8">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="icon" href="http://www.altindex.io/favicon.ico?v=2" />

	<title>T&amp;C 20</title>

	<script src="https://code.highcharts.com/stock/highstock.js"></script>
	<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css?v=1" rel="stylesheet">

	<link href="https://fonts.googleapis.com/css?family=Roboto+Mono|Roboto+Slab" rel="stylesheet">
</head>

<body>
<?php include_once("analyticstracking.php") ?>
	<div class="container-fluid">
	<div class="row">
		<div class="col-sm-2">
			<nav class="navbar navbar-default" role="navigation">
				<div class="navbar-header">

					<button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
					</button> <div class="pull-left"><a href="/"><img class="navlogo" src="/images/logo/newlogo.png"></a></div>
				</div>

				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav nav-tabs nav-stacked pull-right">
						<li>
							<p>
							The <b>T&amp;C 20</b> is a digital currency index and blog that tracks growth in the alternative currency market, aka altcoins, using similar methodology as the S&amp;P 500<sup>Ⓡ</sup>.
							</p>
						</li>
						<li>
							<center>
								<p>
									<!--a href="http://facebook.com/tc20altindex"><img src="/images/icons/Facebook-black.svg" width="21"></a>-->
									<a href="http://twitter.com/tcalt20"><img src="/images/icons/Twitter-black.svg" width="21"></a>
								</p>
								<p>
									<span class="glyphicon glyphicon-envelope"></span><a href="mailto:james@altindex.io"> james@altindex.io</a>
								</p>
							</center>
						</li>
					</ul>
				</div>
			</nav>
		</div>

		<div class="col-sm-8">
			<?php include_once("article-view.php"); ?>

			<?php if($a == 0){ ?>

				<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#data">Charts</a></li>
				<li><a data-toggle="tab" href="#announcements">Announcements</a></li>
				<li><a data-toggle="tab" href="#method">Methodology</a></li>
				<li><a data-toggle="tab" href="#about">About Us</a></li>
				</ul>

				<div class="tab-content">
				<div id="data" class="tab-pane fade in active">
					<?php include_once("data.php"); ?>
				</div>
				<div id="announcements" class="tab-pane fade">
					<?php include_once("announcements.php") ?>
				</div>
				<div id="method" class="tab-pane fade">
					<?php include_once("method.php"); ?>
				</div>
				<div id="about" class="tab-pane fade">
					<?php include_once("about.php"); ?>
				</div>
				</div>

				<hr>
			<?php } ?>

			<?php include_once("articles.php"); ?>

		</div>
		<div class="col-sm-2">

		</div>
	</div>
	</div>

</body>

<footer class="footer" style="text-align: center;">
	<div class="container">
	<p></p>
	<p>
	<!--<a href="http://facebook.com/tc20altindex"><img src="/images/icons/Facebook-black.svg" width="21"></a>-->
	<a href="http://twitter.com/tcalt20"><img src="/images/icons/Twitter-black.svg" width="21"></a>
	</p>
	<p>
	<span class="glyphicon glyphicon-envelope"></span><a href="mailto:james@altindex.io"> james@altindex.io</a>
	</p>
	<p class="text-muted">Copyright © 2016 T&amp;C Alt Index. All rights reserved.</p>

	</div>
</footer>
</html>