<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="en-us" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="en-us" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="en-us" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-us" class="no-js"> <!--<![endif]-->
<html>
    <head>
        <title>Pullr Api Test</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <base href="<?= \Yii::$app->urlManager->createUrl('/'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- CSS -->
        <link rel="stylesheet" href="layoutview/api.css" />
        <link rel="stylesheet" href="layoutview/bootstrap.css" />
        <!-- Theme Specific -->
        <link rel="stylesheet" href="../web/themes/bdteam-1/css/master.css" />
        <link rel="stylesheet" href="../web/themes/bdteam-1/css/animate.css" />
        <link rel="stylesheet" href="../web/themes/bdteam-1/css/owl.carousel.css" />
        <!-- Scripts -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
        <script src="//ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script> 
        <script src="api/js"></script>
        <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    </head>
    <body class="responsive" id="top">
        <header>
            <div class="container head">        
                <div class="row">
                    <div class="col-md-3 col-sm-3 animated fadeInDown">
                        <a href="/" class="icon-pullr logo"></a>
                    </div>
                    <div class="menu-actions">
                        <a class="popout" href="#login">Start your campaign</a>
                    </div>
                </div>
            </div>  
        </header>
        <section class="stats">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-4 raised">
                        <span>Amount raised</span>
                        <h1 data-pullr='campaign-amountRaised'></h1>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4 center">
                        <span>Target Amount</span>
                        <h1 data-pullr='campaign-goalAmount'></h1>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4 right">
                        <span>No. of Donors</span>
                        <h1 data-pullr='campaign-numberOfUniqueDonors'></h1>
                    </div>
                </div>
                <div class="amount-progress">
                <div class="row">
                    <div class="project-progress status">
                            <div class="project-progressbar"></div>
                            <span>$10,997</span>
                    </div>
                </div>
            </div>
        </section>
        <button href="<?= yii\helpers\Url::to().'/donate' ?>" class="btn btn-primary donate" data-effect="mfp-zoom-in">Donate</button>
        <div class='row'>
            <h1 data-pullr='campaign-name'></h1><h3>for <span data-pullr="campaign-charity-name"></span></h3>
            
            <span data-pullr='campaign-startDateFormatted'></span> -
            <span data-pullr='campaign-endDateFormatted'></span>
            
        </div>
        
        <div id='pullr-player'>
        </div>
        <div id='pullr-channels'></div>
        <footer>
            <p class="powered">powered by <a href="#" class="icon-pullr"></a></p>
            <p>&copy; 2014 pullr LLC</p>
            <p class="tandc"><a href="/terms">terms</a>|<a href="/privacy">privacy</a>|<a href="/flag" class="icon-flag2 flag"></a></p>
        </footer>
        <script type='text/javascript'>
            Pullr.Init({id: <?= $campaign->id ?>, key: <?= json_encode($campaign->key) ?>});
            // Pullr.Ready(function(){alert(Pullr.event.name)});
        </script>
        <script src="../web/themes/bdteam-1/js/owl.carousel.js"></script>
        <script src="../web/themes/bdteam-1/js/custom.js"></script>
    </body>
</html>