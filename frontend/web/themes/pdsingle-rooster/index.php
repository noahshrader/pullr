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
    <link rel="stylesheet" href="/themes/pdsingle-rooster/css/master.css" />
    <!-- Scripts -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
    <script src="//ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script> 
    <script src="api/script"></script>
    <script src="/global/themes/global.js"></script>
    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
    <!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body class="responsive" id="top">
<div class="donation-form">
    <iframe src="<?= yii\helpers\Url::to().'/donate' ?>"></iframe>
</div>
<div class="wrapper">
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
    <div class="bg-image">
        <section class="stats">
            <div class="container">
                <div class="row feed-details">
                    <div class="col-md-12 center">
                        <img class="avatar" src="{{channel.logo}}" ng-cloak/>     
                        <h1 ng-cloak>{{campaign.name}}</h1>
                        <h5 data-pullr='campaign-startDateFormatted' ng-cloak>{{campaign.startDateFormatted}}</span> - <span data-pullr='campaign-endDateFormatted'>{{campaign.endDateFormatted}}</h5>
                        <div class="donate-button">
                            <button class="btn btn-primary donate" style="background:{{campaign.primaryColor}}">Donate</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="donor-stats">
                        <div class="col-md-4 col-sm-12 col-xs-12 raised">
                            <div class="icon-holder">
                                <span class="icon-coins"></span>
                            </div>
                            <span>Amount Raised</span>
                            <h1 style="color:{{campaign.primaryColor}};" ng-cloak>{{campaign.amountRaisedFormatted}}</h1>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="icon-holder" ng-hide='campaign.goalAmount == 0.00'>
                                <span class="icon-coin"></span>
                            </div>  
                            <span>Goal Amount</span>
                            <h1 ng-cloak>{{campaign.goalAmountFormatted}}</h1>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="icon-holder">
                                <span class="icon-charity"></span>
                            </div>  
                            <span>Donors</span>
                            <h1 ng-cloak>{{campaign.numberOfUniqueDonors}}</h1>
                        </div>
                    </div>
                </div>
                <div class="amount-progress">
                    <div class="project-progress status">
                        <div class="project-progressbar" style="background:{{campaign.primaryColor}}; width: {{campaign.percentageOfGoal}}%">
                            <span class="progress-marker"></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Layout -->
    <div pullr-campaign-layout></div>

    <!-- Description -->
    <section class="campaign-description container">
        <p>{{campaign.description}}</p>
    </section>

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