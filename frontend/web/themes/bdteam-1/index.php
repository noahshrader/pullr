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
        <link rel="stylesheet" href="/layoutview/api.css" />
        <link rel="stylesheet" href="/layoutview/bootstrap.css" />
        <!-- Theme Specific -->
        <link rel="stylesheet" href="/themes/bdteam-1/css/master.css" />
        <link rel="stylesheet" href="/themes/bdteam-1/css/animate.css" />
        <link rel="stylesheet" href="/themes/bdteam-1/css/owl.carousel.css" />
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

<!-- Main Wrapper -->
<div class="ice-cube">
<section class="stats">
    <div class="container" ng-cloak>>
        <div class="row">

            <div class="col-md-4 col-sm-4 col-xs-4 raised">
                <span>Amount raised</span>
                <h1>{{campaign.amountRaised}}</h1>
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
            <div class="row">
                <div class="project-progress status">
                    <div class="project-progressbar"></div>
                    	 <div class="project-progressbar" style="width:{{campaign.percentageOfGoal}}%"></div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="feed">
<div class="container" ng-cloak>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-8 feed-details">
        <div class="feed-details-title">
            <h3 data-pullr='campaign-name'>{{campaign.name}}</h3> <!-- CAMPAIGN NAME -->
                        <p><span data-pullr='campaign-startDateFormatted' ng-cloak>{{campaign.startDateFormatted}}</span> - <span data-pullr='campaign-endDateFormatted'>{{campaign.endDateFormatted}}</span></p> <!-- CAMPAIGN DATES -->
        </div>
    </div>
    <div class="col-md-2 col-sm-2 col-xs-4 feed-details">
        <div class="sharing">
            <div class="dropdown">
                <button class="btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                    share
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
	<div pullr-campaign-layout></div> 

   <div class="row">
        <div class="col-md-12">
            <div class="user-details">
                <div class="username">
                    <img src="https://s3.amazonaws.com/uifaces/faces/twitter/chadengle/128.jpg" class="user-details-avatar"/>
                    <a href="http://www.twitch.tv/kombatshift" class="user-details-twitch">username</a>

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
                        <img src="http://placehold.it/142x142&text=avatar"/>
                    </a>

                    <div class="team-mate-single-social">
                        <a href="#" class="team-mate-single-social-twitch-link">username</a><br/>
                        <a href="#" class="icon-twitter"></a>
                        <a href="#" class="icon-facebook"></a>
                        <a href="#" class="icon-youtube"></a>
                        <a href="#" class="icon-twitch2"></a><br/>
                        <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                    </div>
                </div>
                <div class="team-mate-single">
                    <a href="#" class="avatar-link">
                        <img src="http://placehold.it/142x142&text=avatar"/>
                    </a>

                    <div class="team-mate-single-social">
                        <a href="#" class="team-mate-single-social-twitch-link">username</a><br/>
                        <a href="#" class="icon-twitter"></a>
                        <a href="#" class="icon-facebook"></a>
                        <a href="#" class="icon-youtube"></a>
                        <a href="#" class="icon-twitch2"></a><br/>
                        <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                    </div>
                </div>
                <div class="team-mate-single">
                    <a href="#" class="avatar-link">
                        <img src="http://placehold.it/142x142&text=avatar"/>
                    </a>

                    <div class="team-mate-single-social">
                        <a href="#" class="team-mate-single-social-twitch-link">username</a><br/>
                        <a href="#" class="icon-twitter"></a>
                        <a href="#" class="icon-facebook"></a>
                        <a href="#" class="icon-youtube"></a>
                        <a href="#" class="icon-twitch2"></a><br/>
                        <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                    </div>
                </div>
                <div class="team-mate-single">
                    <a href="#" class="avatar-link">
                        <img src="http://placehold.it/142x142&text=avatar"/>
                    </a>

                    <div class="team-mate-single-social">
                        <a href="#" class="team-mate-single-social-twitch-link">username</a><br/>
                        <a href="#" class="icon-twitter"></a>
                        <a href="#" class="icon-facebook"></a>
                        <a href="#" class="icon-youtube"></a>
                        <a href="#" class="icon-twitch2"></a><br/>
                        <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                    </div>
                </div>
                <div class="team-mate-single">
                    <a href="#" class="avatar-link">
                        <img src="http://placehold.it/142x142&text=avatar"/>
                    </a>

                    <div class="team-mate-single-social">
                        <a href="#" class="team-mate-single-social-twitch-link">username</a><br/>
                        <a href="#" class="icon-twitter"></a>
                        <a href="#" class="icon-facebook"></a>
                        <a href="#" class="icon-youtube"></a>
                        <a href="#" class="icon-twitch2"></a><br/>
                        <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                    </div>
                </div>
                <div class="team-mate-single">
                    <a href="#" class="avatar-link">
                        <img src="http://placehold.it/142x142&text=avatar"/>
                    </a>

                    <div class="team-mate-single-social">
                        <a href="#" class="team-mate-single-social-twitch-link">username</a><br/>
                        <a href="#" class="icon-twitter"></a>
                        <a href="#" class="icon-facebook"></a>
                        <a href="#" class="icon-youtube"></a>
                        <a href="#" class="icon-twitch2"></a><br/>
                        <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                    </div>
                </div>
                <div class="team-mate-single">
                    <a href="#" class="avatar-link">
                        <img src="http://placehold.it/142x142&text=avatar"/>
                    </a>

                    <div class="team-mate-single-social">
                        <a href="#" class="team-mate-single-social-twitch-link">username</a><br/>
                        <a href="#" class="icon-twitter"></a>
                        <a href="#" class="icon-facebook"></a>
                        <a href="#" class="icon-youtube"></a>
                        <a href="#" class="icon-twitch2"></a><br/>
                        <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                    </div>
                </div>
                <div class="team-mate-single">
                    <a href="#" class="avatar-link">
                        <img src="http://placehold.it/142x142&text=avatar"/>
                    </a>

                    <div class="team-mate-single-social">
                        <a href="#" class="team-mate-single-social-twitch-link">username</a><br/>
                        <a href="#" class="icon-twitter"></a>
                        <a href="#" class="icon-facebook"></a>
                        <a href="#" class="icon-youtube"></a>
                        <a href="#" class="icon-twitch2"></a><br/>
                        <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                    </div>
                </div>
                <div class="team-mate-single">
                    <a href="#" class="avatar-link">
                        <img src="http://placehold.it/142x142&text=avatar"/>
                    </a>

                    <div class="team-mate-single-social">
                        <a href="#" class="team-mate-single-social-twitch-link">username</a><br/>
                        <a href="#" class="icon-twitter"></a>
                        <a href="#" class="icon-facebook"></a>
                        <a href="#" class="icon-youtube"></a>
                        <a href="#" class="icon-twitch2"></a><br/>
                        <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                    </div>
                </div>
                <div class="team-mate-single">
                    <a href="#" class="avatar-link">
                        <img src="http://placehold.it/142x142&text=avatar"/>
                    </a>

                    <div class="team-mate-single-social">
                        <a href="#" class="team-mate-single-social-twitch-link">username</a><br/>
                        <a href="#" class="icon-twitter"></a>
                        <a href="#" class="icon-facebook"></a>
                        <a href="#" class="icon-youtube"></a>
                        <a href="#" class="icon-twitch2"></a><br/>
                        <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                    </div>
                </div>
                <div class="team-mate-single">
                    <a href="#" class="avatar-link">
                        <img src="http://placehold.it/142x142&text=avatar"/>
                    </a>

                    <div class="team-mate-single-social">
                        <a href="#" class="team-mate-single-social-twitch-link">username</a><br/>
                        <a href="#" class="icon-twitter"></a>
                        <a href="#" class="icon-facebook"></a>
                        <a href="#" class="icon-youtube"></a>
                        <a href="#" class="icon-twitch2"></a><br/>
                        <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                    </div>
                </div>
                <div class="team-mate-single">
                    <a href="#" class="avatar-link">
                        <img src="http://placehold.it/142x142&text=avatar"/>
                    </a>

                    <div class="team-mate-single-social">
                        <a href="#" class="team-mate-single-social-twitch-link">username</a><br/>
                        <a href="#" class="icon-twitter"></a>
                        <a href="#" class="icon-facebook"></a>
                        <a href="#" class="icon-youtube"></a>
                        <a href="#" class="icon-twitch2"></a><br/>
                        <a href="#" class="team-mate-single-social-twitch-link">view stream</a>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
</section>

<section class="charity-donors">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="charity-info">
                    <img src="/themes/bdteam-1/images/charity-logo.jpg" class="charity-logo"/>
                    <h4 data-pullr="campaign-charity-name" ng-cloak>{{campaign.charity}}</h4>

                    <p>St. Jude Children's Research Hospital is internationally recognized for its pioneering work in
                        finding cures and saving children with pediatric cancer and other catastrophic diseases. </p>
                    <a href="#" class="charity-link">website</a>
                    <a href="#" class="charity-link">twitter</a>
                    <a href="#" class="charity-link">facebook</a>

                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="donor-info">
                    <div class="donor-info-title">
                        <h4>Top donors</h4>
                    </div>
                    <div class="donor-list">
                        <ul>
                            <li>$1,000 - Donor One</li>
                            <li>$1,000 - Donor Two</li>
                            <li>$1,000 - Donor Three</li>
                            <li>$1,000 - Donor Four</li>
                            <li>$1,000 - Donor Five</li>
                            <li>$1,000 - Donor Six</li>
                            <li>$1,000 - Donor Seven</li>
                            <li>$1,000 - Donor Eight</li>
                            <li>$1,000 - Donor Nine</li>
                            <li>$1,000 - Donor Ten</li>
                            <li>$1,000 - Donor Eleven</li>
                            <li>$1,000 - Donor Twelve</li>

                    </div>
                </div>
            </div>

        </div>
    </div>

</section>

<footer>
    <p class="powered">powered by <a href="#" class="icon-pullr"></a></p>
    <p>&copy; <? print(Date("Y")); ?> pullr LLC</p>
    <p class="tandc"><a href="/terms">terms</a>|<a href="/privacy">privacy</a>|<a href="/flag" class="icon-flag2 flag"></a></p>
</footer>


<script src="/themes/bdteam-1/js/owl.carousel.js"></script>
<script>
    $(document).ready(function () {
        jQuery("#stream").fitVids();
        // Homepage stream functionality
        jQuery('.togglechat').click(function () {
            jQuery('.togglechat').toggleClass('chaton');
            jQuery('.featuredchat').fadeToggle();
            jQuery('.featuredstreamcontainer').toggleClass('biggerFeaturedStream');
        });

        $(".team-mates").owlCarousel({
            items: 8,
            itemsDesktop: [1000, 5],
            itemsDesktopSmall: [900, 6],
            itemsTablet: [600, 4],
            itemsMobile: false,
            navigation: true,
            navigationText: [
                "<i class='icon-chevron-left icon-white'></i>",
                "<i class='icon-chevron-right icon-white'></i>"
            ]
        });

        $('.avatar-link').on('click', function (event) {
            var $this = $(this);
            if ($this.hasClass('clicked')) {
                $this.removeAttr('style').removeClass('clicked');
            } else {
                $this.css('background', '#7fc242').addClass('clicked');
            }
        });
    });

    Pullr.Init({id: <?= $campaign->id ?>, key: <?= json_encode($campaign->key) ?>});
    // Pullr.Ready(function(){alert(Pullr.event.name)});
    function updatePage() {
        var progress = Pullr.campaign.percentageOfGoal + '%';
        $('.project-progressbar').css('width', progress);
    }

    Pullr.Ready(updatePage);
</script>

<script src="/themes/bdteam-1/js/custom.js"></script>
</body>
</html>