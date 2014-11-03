<!DOCTYPE html>
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
    <link rel="stylesheet" href="/themes/bdteam-1/css/master.css" />
    <!-- Scripts -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
    <script src="//ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script> 
    <script src="api/script"></script>
    <script src="/global/themes/global.js"></script>
    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700|Varela+Round:400' rel='stylesheet' type='text/css'>
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
            <h5 class="campaign">{{campaign.name}}</h5>
            <h5 class="amount-raised" style="color:{{campaign.primaryColor}};"><i class="icon icon-coin"></i>{{campaign.amountRaisedFormatted}}</h5>
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
                <h4 style="color:{{campaign.primaryColor}}" ng-cloak>{{campaign.charity.name}}</h4>
                <h5 data-pullr='campaign-startDateFormatted' ng-cloak>{{campaign.startDateFormatted}}</span> - <span data-pullr='campaign-endDateFormatted'>{{campaign.endDateFormatted}}</h5>
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
                    <img ng-src="{{team.logo}}" ng-cloak/>
                    <a href="http://www.twitch.tv/{{team.display_name}}" ng-cloak>{{team.display_name}}</a>
                </div>
            </div>
            <div class="user-details-social">
                <a href="http://www.twitch.tv/{{campaign.channelTeam}}" title="Twitch" class="icon-twitch2"></a>
                <a href="http://twitter.com/{{campaign.twitterName}}" class="icon-twitter" title="Twitter" ng-show="campaign.twitterEnable == 1"></a>
                <a href="http://www.facebook.com/{{campaign.facebookUrl}}" class="icon-facebook" title="Facebook" ng-show="campaign.facebookUrl == 1"></a>
                <a href="https://www.youtube.com/user/{{campaign.youtubeUrl}}" class="icon-youtube" title="YouTube" ng-show="campaign.youtubeEnable == 1"></a>
            </div>
        </div>
    </section>

    <!-- Campaign Info -->
    <section class="info container" ng-cloak>
        <div class="info-wrap cf">
            <div class="right donate-wrap">
                <div class="donate-button">
                    <button class="btn btn-primary donate" style="background:{{campaign.primaryColor}}">Donate</button>
                    <div class="slant"></div>
                </div>
            </div>
            <div class="feed-details">
                <h4 ng-show='campaign.layoutType == LAYOUT_TYPE_TEAM'>{{selectedChannel.title}}</h4>
                <span ng-show='campaign.layoutType == LAYOUT_TYPE_TEAM'>{{selectedChannel.meta_game}}</span>
            </div>
        </div>
    </section>

    <!-- Layout -->
    <div pullr-campaign-layout></div>

    <!-- Footer -->
    <footer>
        <h5>Powered by</h5>
        <a class="logo icon-pullr" href="http://www.pullr.io" target="_blank"></a>
        <span class="copy"> &copy; <? print(Date("Y")); ?> Pullr, LLC. All Rights Reserved. <a href="http://www.pullr.io/terms">Terms &amp; Conditions</a> &bull; <a href="http://www.pullr.io/privacy">Privacy Policy</a></span>
    </footer>
</div>
<script>
    Pullr.Init({id: <?= $campaign->id ?>, key: <?= json_encode($campaign->key) ?>});
</script>
<script src="/global/themes/js/stream.js"></script>
</body>
</html>