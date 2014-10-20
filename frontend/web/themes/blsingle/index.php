<!doctype html>
<!--[if lt IE 7]> <html lang="en-us" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="en-us" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="en-us" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-us" class="no-js"> <!--<![endif]-->
<html>
<head>
	<title ng-if='! campaign'>Pullr</title>
	<title ng-if='campaign' ng-cloak>{{campaign.name}} - Pullr</title>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<base href="<?= \Yii::$app->urlManager->createUrl('/'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- CSS -->
	<link rel="stylesheet" href="/layoutview/bootstrap.css" />
	<link rel="stylesheet" href="/global/themes/css/animate.css" />
	<link rel="stylesheet" href="/global/themes/css/global.css" />
	<!-- Theme Specific -->
	<link rel="stylesheet" href="/themes/blsingle/css/master.css" />
	<!-- Scripts -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
	<script src="//ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script> 
	<script src="api/script"></script>
	<script src="/global/themes/global.js"></script>
	<!-- Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700,500' rel='stylesheet' type='text/css'>
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body class="responsive" id="top">

<!-- Header -->
<header>
	<div class="container head">		
		<div class="logo animated fadeInDown">
			<a href="/" class="icon-pullr logo"></a>
		</div>
		<div class="menu-actions">
			<a href="http://www.pullr.io">Start your campaign</a>
		</div>
	</div>	
</header>

<!-- Main Stats -->
<section class="stats">
	<div class="container" ng-cloak>
		<div class="row">
			<div class="col-md-4 col-sm-4 col-xs-4 raised">
				<span>Amount raised</span>
                        <h1 data-pullr='campaign-amountRaisedFormatted' style="color:{{campaign.primaryColor}};">
                            {{campaign.amountRaisedFormatted}}
                        </h1>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-4 center">
				<span>Target Amount</span>
                        <h1 data-pullr='campaign-goalAmountFormatted'>
                            {{campaign.goalAmountFormatted}}
                        </h1>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-4 right">
				<span>No. of Donors</span>
                        <h1 data-pullr='campaign-numberOfUniqueDonors'>
                            {{campaign.numberOfUniqueDonors}}
                        </h1>
			</div>
		</div>
		<div class="amount-progress">
			<div class="project-progress status">
				<div class="project-progressbar" style="background:{{campaign.primaryColor}}; width: {{campaign.percentageOfGoal}}%"></div>
			</div>
		</div>
	</div>
</section>

<!-- Campaign Info -->
<section class="info cf">
	<div class="container" ng-cloak>
		<div class="col-md-6 col-sm-6 col-xs-8 feed-details">
			<div class="feed-details-title">
				<h3>{{campaign.name}}</h3>
                  	<p><span data-pullr='campaign-startDateFormatted' ng-cloak>{{campaign.startDateFormatted}}</span> - <span data-pullr='campaign-endDateFormatted'>{{campaign.endDateFormatted}}</span></p>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-sm-4 col-xs-12 right feed-donate">
		<button href="<?= yii\helpers\Url::to().'/donate' ?>" class="btn btn-primary donate" data-effect="mfp-zoom-in" style="background:{{campaign.primaryColor}}">Donate</button>
	</div>
</section>

<!-- Layout -->
<div pullr-campaign-layout></div>

<!-- Footer -->
<footer>
	<h5>Powered by</h5>
	<a class="logo icon-pullr" href="http://www.pullr.io" target="_blank"></a>
	<p>
		&copy; <? print(Date("Y")); ?> Pullr, LLC.
	</p>
	<p class="tandc"><a href="/terms">Terms &amp; Conditions</a> | <a href="/privacy">Privacy Policy</a></p>
</footer>

<script type='text/javascript'>
	Pullr.Init({id: <?= $campaign->id ?>, key: <?= json_encode($campaign->key) ?>});
	// Pullr.Ready(function(){alert(Pullr.event.name)});
</script>
<script src="/global/themes/js/singlestream.js"></script>
</body>
</html>