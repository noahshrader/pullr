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
	<link rel="stylesheet" href="/themes/bdsingle/css/master.css" />
	<!-- Scripts -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
	<script src="//ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script> 
	<script src="api/script"></script>
	<script src="/global/themes/global.js"></script>
	<!-- Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,500,700|Varela+Round' rel='stylesheet' type='text/css'>
	<!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body class="responsive" id="top">
<div class="donation-form">
    <iframe src="<?= yii\helpers\Url::to().'/donate' ?>"></iframe>
</div>
<div class="wrapper">
	<!-- Slidestats -->
	<div class="slidestats">
		<div class="container">
			<img src="{{channel.logo}}" ng-cloak/>
			<h5 class="campaign">{{campaign.name}}</h5>
			<h5 class="amount-raised" style="color:{{campaign.primaryColor}};">{{campaign.amountRaisedFormatted}}</h5>
		</div>
	</div>
	<!-- Header -->
	<header>
		<div class="container">
			<div class="logo">
				<a href="/" class="icon-pullr logo"></a>
			</div>
			<div class="menu-actions">
				<a href="http://www.pullr.io">Start your campaign</a>
			</div>
		</div>
	</header>

	<!-- Main Stats -->
	<section class="stats" bg-image="{{campaign.backgroundImg}}">
		<div class="stats-wrap">
			<div class="campaign-info">
				<h1 ng-cloak>{{campaign.name}}</h1>
				<h4 style="color:{{campaign.primaryColor}}" ng-cloak>{{campaign.charity.name}}{{campaign.customCharity}}</h4>
			</div>
			<div class="numbers" ng-cloak>
				<div class="container">
					<div class="col-md-4 col-sm-4 col-xs-4 raised">
						<span>Amount Raised</span>
						<h1 style="color:{{campaign.primaryColor}};">{{campaign.amountRaisedFormatted}}</h1>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4 center">
						<span ng-hide='campaign.goalAmount == 0.00'>Goal Amount</span>
						<h1 ng-hide='campaign.goalAmount == 0.00'>{{campaign.goalAmountFormatted}}</h1>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4 right">
						<span>Donors</span>
						<h1>{{campaign.numberOfUniqueDonors}}</h1>
					</div>
				</div>
			</div>
			<div class="project-progress status">
				<div class="project-progressbar" style="background:{{campaign.primaryColor}}; width: {{campaign.percentageOfGoal}}%"></div>
			</div>
		</div>
		<div class="overlay"></div>
	</section>

	<!-- User -->
	<section class="user-bar">
		<div class="container">
			<div class="user">
				<div class="avatar">
					<img src="{{channel.logo}}" ng-cloak/>
					<a href="http://www.twitch.tv/{{channel.display_name}}" ng-cloak>{{channel.display_name}}</a>
				</div>
				<div class="user-details">
					<span ng-show='campaign.layoutType == LAYOUT_TYPE_MULTI || campaign.layoutType == LAYOUT_TYPE_SINGLE' ng-cloak><i class="icon icon-user"></i>{{selectedChannel.followers}}</span>
					<span ng-show='campaign.layoutType == LAYOUT_TYPE_MULTI || campaign.layoutType == LAYOUT_TYPE_SINGLE' ng-cloak><i class="icon icon-view"></i>{{selectedChannel.views}}</span>
				</div>
			</div>
			<div class="user-details-social">
				<a href="http://www.twitch.tv/{{channel.display_name}}" title="Twitch" class="icon-twitch2"></a>
				<a href="http://twitter.com/{{campaign.twitterName}}" class="icon-twitter" title="Twitter" ng-show="campaign.twitterEnable == 1"></a>
	            	<a href="{{campaign.facebookUrl}}" class="icon-facebook" title="Facebook" ng-show="campaign.facebookEnable == 1"></a>
	            	<a href="{{campaign.youtubeUrl}}" class="icon-youtube" title="YouTube" ng-show="campaign.youtubeEnable == 1"></a>
			</div>
		</div>
	</section>

	<!-- Campaign Info -->
	<section class="info container" ng-cloak>
		<div class="info-wrap cf">
			<div class="right donate-wrap">
				<div class="donate-button">
					<button class="btn btn-primary donate" style="background:{{campaign.primaryColor}}">{{campaign.donationButtonText}}</button>
				</div>
			</div>
			<div class="feed-details">
				<h4 ng-show='campaign.layoutType == LAYOUT_TYPE_MULTI || campaign.layoutType == LAYOUT_TYPE_SINGLE'>{{selectedChannel.status}}</h4>
				<span ng-show='campaign.layoutType == LAYOUT_TYPE_MULTI || campaign.layoutType == LAYOUT_TYPE_SINGLE'>{{selectedChannel.game}}</span>
			</div>
		</div>
	</section>

	<!-- Layout -->
	<div pullr-campaign-layout></div>

	<!-- Description -->
	<section class="campaign-info container">
		<div class="description holder pad">
			<h4>Campaign Info</h4>
			<p ng-cloak ng-bind-html="to_trusted(campaign.description)"></p>
		</div>
		<div class="donor-wall holder pad">
			<h4>Donors</h4>
			<div pullr-donor-list ng-cloak></div>
		</div>
	</section>

	<!-- Footer -->
	<footer>
		<h5>Powered by</h5>
		<a class="logo icon-pullr" href="http://www.pullr.io" target="_blank"></a>
		<span class="copy"> &copy; <? print(Date("Y")); ?> Pullr, LLC. All Rights Reserved. <a href="http://www.pullr.io/terms">Terms &amp; Conditions</a> &bull; <a href="http://www.pullr.io/privacy">Privacy Policy</a></span>
	</footer>
</div>

<script type='text/javascript'>
	Pullr.Init({id: <?= $campaign->id ?>, key: <?= json_encode($campaign->key) ?>});
</script>

<script src="/global/themes/js/stream.js"></script>
</body>
</html>