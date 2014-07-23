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
        <link rel="stylesheet" href="/themes/bdteam-1/css/master.css" />
        <link rel="stylesheet" href="/themes/bdteam-1/css/animate.css" />
        <link rel="stylesheet" href="/themes/bdteam-1/css/owl.carousel.css" />
        <!-- Scripts -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
        <script src="//ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script> 
        <script src="api/js"></script>
        <!-- Fonts -->
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700,500' rel='stylesheet' type='text/css'>
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
        <section class="stats"> <!-- main stats -->
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
        <section class="feed"> <!-- main content -->
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-8 feed-details">
                    <div class="feed-details-title">
                        <h3 data-pullr='campaign-name'></h3> <!-- CAMPAIGN NAME -->
                        <p><span data-pullr='campaign-startDateFormatted'></span> - <span data-pullr='campaign-endDateFormatted'></span></p> <!-- CAMPAIGN DATES -->
                    </div>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-4 feed-details">
                    <div class="sharing">
                        <div class="dropdown">
                            <button class="btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">share
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Twitter</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Facebook</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 right feed-donate">
                    <button href="<?= yii\helpers\Url::to().'/donate' ?>" class="btn btn-primary donate" data-effect="mfp-zoom-in">Donate</button>
                </div>
                
            </div>
            <div class="row stream">
                    <div id="stream" class="clear">
                    <div id="featured">
                        <div class="featuredstreamcontainer">
                            <div class="featuredstream">
                                <object type="application/x-shockwave-flash" height="378" width="620" id="live_embed_player_flash" data="http://www.twitch.tv/widgets/live_embed_player.swf?channel=funforfreedom" bgcolor="#000000"><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="all" /><param name="movie" value="http://www.twitch.tv/widgets/live_embed_player.swf" /><param name="flashvars" value="hostname=www.twitch.tv&amp;channel=funforfreedom&amp;auto_play=true&amp;start_volume=25" /></object>
                            </div>
                        </div>
                        <div class="featuredchat">
                            <iframe frameborder="0" scrolling="no" id="twitchChat" src="http://twitch.tv/chat/embed?channel=funforfreedom&amp;popout_chat=true"></iframe>
                        </div>
                    </div>
                    <div class="stream-controls">
                        <a class="togglechat icon-chat" href="javascript:void(0);"></a>
                    </div>
                </div>

            </div>  

                <div class="row">
                    <div class="col-md-12">
                    <div class="user-details">
                        <div class="username">
                            <img src="https://s3.amazonaws.com/uifaces/faces/twitter/chadengle/128.jpg" class="user-details-avatar" /> <a href="http://www.twitch.tv/kombatshift" class="user-details-twitch">username</a>
                            <div class="user-details-social">
                                <a href="#" class="icon-twitter"></a>
                                <a href="#" class="icon-facebook"></a>
                                <a href="#" class="icon-youtube"></a>
                                <a href="#" class="icon-twitch2"></a>
                            </div>
                            
                        </div>
                        <div class="user-viewers">
                                <p>100,001 viewers</p>
                            </div>
                    </div>
                    <div class="team-mates">
                        <div class="team-mate-single">
                        <a href="#" class="avatar-link">
                            <img src="http://placehold.it/142x142&text=avatar" />
                        </a>
                                <div class="team-mate-single-social">
                                <a href="#" class="team-mate-single-social-twitch-link">username</a><br />
                                <a href="#" class="icon-twitter"></a>
                                <a href="#" class="icon-facebook"></a>
                                <a href="#" class="icon-youtube"></a>
                                <a href="#" class="icon-twitch2"></a><br />
                                <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                                </div>
                        </div>
                        <div class="team-mate-single">
                        <a href="#" class="avatar-link">
                            <img src="http://placehold.it/142x142&text=avatar" />
                        </a>
                                <div class="team-mate-single-social">
                                <a href="#" class="team-mate-single-social-twitch-link">username</a><br />
                                <a href="#" class="icon-twitter"></a>
                                <a href="#" class="icon-facebook"></a>
                                <a href="#" class="icon-youtube"></a>
                                <a href="#" class="icon-twitch2"></a><br />
                                <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                                </div>
                        </div>
                        <div class="team-mate-single">
                        <a href="#" class="avatar-link">
                            <img src="http://placehold.it/142x142&text=avatar" />
                        </a>
                                <div class="team-mate-single-social">
                                <a href="#" class="team-mate-single-social-twitch-link">username</a><br />
                                <a href="#" class="icon-twitter"></a>
                                <a href="#" class="icon-facebook"></a>
                                <a href="#" class="icon-youtube"></a>
                                <a href="#" class="icon-twitch2"></a><br />
                                <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                                </div>
                        </div>
                        <div class="team-mate-single">
                        <a href="#" class="avatar-link">
                            <img src="http://placehold.it/142x142&text=avatar" />
                        </a>
                                <div class="team-mate-single-social">
                                <a href="#" class="team-mate-single-social-twitch-link">username</a><br />
                                <a href="#" class="icon-twitter"></a>
                                <a href="#" class="icon-facebook"></a>
                                <a href="#" class="icon-youtube"></a>
                                <a href="#" class="icon-twitch2"></a><br />
                                <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                                </div>
                        </div>
                        <div class="team-mate-single">
                        <a href="#" class="avatar-link">
                            <img src="http://placehold.it/142x142&text=avatar" />
                        </a>
                                <div class="team-mate-single-social">
                                <a href="#" class="team-mate-single-social-twitch-link">username</a><br />
                                <a href="#" class="icon-twitter"></a>
                                <a href="#" class="icon-facebook"></a>
                                <a href="#" class="icon-youtube"></a>
                                <a href="#" class="icon-twitch2"></a><br />
                                <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                                </div>
                        </div>
                        <div class="team-mate-single">
                        <a href="#" class="avatar-link">
                            <img src="http://placehold.it/142x142&text=avatar" />
                        </a>
                                <div class="team-mate-single-social">
                                <a href="#" class="team-mate-single-social-twitch-link">username</a><br />
                                <a href="#" class="icon-twitter"></a>
                                <a href="#" class="icon-facebook"></a>
                                <a href="#" class="icon-youtube"></a>
                                <a href="#" class="icon-twitch2"></a><br />
                                <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                                </div>
                        </div>
                        <div class="team-mate-single">
                        <a href="#" class="avatar-link">
                            <img src="http://placehold.it/142x142&text=avatar" />
                        </a>
                                <div class="team-mate-single-social">
                                <a href="#" class="team-mate-single-social-twitch-link">username</a><br />
                                <a href="#" class="icon-twitter"></a>
                                <a href="#" class="icon-facebook"></a>
                                <a href="#" class="icon-youtube"></a>
                                <a href="#" class="icon-twitch2"></a><br />
                                <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                                </div>
                        </div>
                        <div class="team-mate-single">
                        <a href="#" class="avatar-link">
                            <img src="http://placehold.it/142x142&text=avatar" />
                        </a>
                                <div class="team-mate-single-social">
                                <a href="#" class="team-mate-single-social-twitch-link">username</a><br />
                                <a href="#" class="icon-twitter"></a>
                                <a href="#" class="icon-facebook"></a>
                                <a href="#" class="icon-youtube"></a>
                                <a href="#" class="icon-twitch2"></a><br />
                                <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                                </div>
                        </div>
                        <div class="team-mate-single">
                        <a href="#" class="avatar-link">
                            <img src="http://placehold.it/142x142&text=avatar" />
                        </a>
                                <div class="team-mate-single-social">
                                <a href="#" class="team-mate-single-social-twitch-link">username</a><br />
                                <a href="#" class="icon-twitter"></a>
                                <a href="#" class="icon-facebook"></a>
                                <a href="#" class="icon-youtube"></a>
                                <a href="#" class="icon-twitch2"></a><br />
                                <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                                </div>
                        </div>
                        <div class="team-mate-single">
                        <a href="#" class="avatar-link">
                            <img src="http://placehold.it/142x142&text=avatar" />
                        </a>
                                <div class="team-mate-single-social">
                                <a href="#" class="team-mate-single-social-twitch-link">username</a><br />
                                <a href="#" class="icon-twitter"></a>
                                <a href="#" class="icon-facebook"></a>
                                <a href="#" class="icon-youtube"></a>
                                <a href="#" class="icon-twitch2"></a><br />
                                <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                                </div>
                        </div><div class="team-mate-single">
                        <a href="#" class="avatar-link">
                            <img src="http://placehold.it/142x142&text=avatar" />
                        </a>
                                <div class="team-mate-single-social">
                                <a href="#" class="team-mate-single-social-twitch-link">username</a><br />
                                <a href="#" class="icon-twitter"></a>
                                <a href="#" class="icon-facebook"></a>
                                <a href="#" class="icon-youtube"></a>
                                <a href="#" class="icon-twitch2"></a><br />
                                <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                                </div>
                        </div>
                        <div class="team-mate-single">
                        <a href="#" class="avatar-link">
                            <img src="http://placehold.it/142x142&text=avatar" />
                        </a>
                                <div class="team-mate-single-social">
                                <a href="#" class="team-mate-single-social-twitch-link">username</a><br />
                                <a href="#" class="icon-twitter"></a>
                                <a href="#" class="icon-facebook"></a>
                                <a href="#" class="icon-youtube"></a>
                                <a href="#" class="icon-twitch2"></a><br />
                                <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                                </div>
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>
        </section>
        <div class='row'>
            <span data-pullr="campaign-charity-name"></span>
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
        <script src="/themes/bdteam-1/js/owl.carousel.js"></script>
        <script src="/themes/bdteam-1/js/custom.js"></script>
    </body>
</html>