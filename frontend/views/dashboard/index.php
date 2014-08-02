<?php
use common\models\User;
use common\models\twitch\TwitchUser;
use common\models\notifications\SystemNotification;
use common\components\Application;

/**
 * @var yii\web\View $this
 * @var SystemNotification $systemNotification
 * @var TwitchUser $twitchUser
 */
$this->title = 'Dashboard';

$this->registerJsFile('@web/js/dashboard/index.js', common\assets\CommonAsset::className());
$user = Application::getCurrentUser();
$twitchPartner = $user->userFields->twitchPartner;
?>

<section id="content" class="dashboard">
    
    <div class="dashboard-data-wrap">

        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#today" data-toggle="tab">Today</a>
            </li>
            <li>
                <a href="#month" data-toggle="tab">This Month</a>
            </li>
            <li>
                <a href="#overall" data-toggle="tab">Overall</a>
            </li>
            <li>
                <a href="#daterange" data-toggle="tab">Date Range</a>
            </li>
        </ul>

        <div class="tab-content">

            <div class="tab-pane fade in active" id="today">

                <div class="row stats-overview">
                    <!-- HTML MARKUP - TOTAL AMOUNT RAISED FOR TODAY GOES HERE -->
                    <div class="col-xs-12 stats-box raised-total">
                        <i class="icon-coin"></i>
                        <h1>$45,567</h1>
                        <h5>Total Amount Raised</h5>
                    </div>
                    <div class="clearfix"></div>
                    <!-- HTML MARKUP - TOTAL CAMPAIGNS FOR TODAY -->
                    <div class="col-xs-4 stats-box">
                        <h2>1</h2>
                        <h5>Total Campaigns</h5>
                    </div>
                    <!-- HTML MARKUP - TOTAL DONTATIONS FOR TODAY -->
                    <div class="col-xs-4 stats-box">
                        <h2>1245</h2>
                        <h5>Total Donations</h5>
                    </div>
                    <!-- HTML MARKUP - TOTAL DONORS FOR TODAY -->
                    <div class="col-xs-4 stats-box">
                        <h2>456</h2>
                        <h5>Total Donors</h5>
                    </div>
                </div>

                <div class="row stats-overview raised-group-stats">
                    <i class="icon-usergroup"></i>
                    <!-- HTML MARKUP - RAISED FOR CHARITY -->
                    <div class="col-xs-6 stats-box">
                        <h2>$1245</h2>
                        <h5>Raised for Charity</h5>
                    </div>

                    <!-- HTML MARKUP - RAISED PERSONALLY -->
                    <div class="col-xs-6 stats-box">
                        <h2>$1245</h2>
                        <h5>Raised Personally</h5>
                    </div>

                </div>

                <? if ($twitchUser): ?>
                    <div class="row stats-overview twitch-stats">
                        <i class="icon-twitch2"></i>
                        <!-- BEGIN twitch followers -->
                        <div class="col-xs-<?= $twitchPartner ? 6 : 12 ?> text-center stats-box">
                            <h2><?= $twitchUser->followersNumber ?></h2>
                            <h5>Twitch Followers</h5>
                        </div>
                        <!-- END twitch followers -->
                        <? if ($twitchPartner): ?>
                            <!-- BEGIN twitch subscribers -->
                            <div class="col-xs-6 text-center stats-box">
                                <h2><?= $twitchUser->subscribersNumber ?></h2>
                                <h5>Twitch Subscribers</h5>
                            </div>
                            <!-- END twitch subscribers -->
                        <? endif ?>
                    </div>
                <? endif ?>
            </div><!-- END Today Tab Pane -->

            <div class="tab-pane fade in" id="month">

               <div class="row stats-overview">
                    <!-- HTML MARKUP - TOTAL AMOUNT RAISED FOR TODAY GOES HERE -->
                    <div class="col-xs-12 stats-box">
                        <h2>$45,567</h2>
                        <h5>Total Amount Raised</h5>
                    </div>
                    <div class="clearfix"></div>
                    <!-- HTML MARKUP - TOTAL CAMPAIGNS FOR TODAY -->
                    <div class="col-xs-4 stats-box">
                        <h2>1</h2>
                        <h5>Total Campaigns</h5>
                    </div>
                    <!-- HTML MARKUP - TOTAL DONTATIONS FOR TODAY -->
                    <div class="col-xs-4 stats-box">
                        <h2>1245</h2>
                        <h5>Total Donations</h5>
                    </div>
                    <!-- HTML MARKUP - TOTAL DONORS FOR TODAY -->
                    <div class="col-xs-4 stats-box">
                        <h2>456</h2>
                        <h5>Total Donors</h5>
                    </div>
                </div>

                <div class="row stats-overview">
                    <!-- HTML MARKUP - RAISED FOR CHARITY -->
                    <div class="col-xs-6 stats-box">
                        <h2>$1245</h2>
                        <h5>Raised for Charity</h5>
                    </div>

                    <!-- HTML MARKUP - RAISED PERSONALLY -->
                    <div class="col-xs-6 stats-box">
                        <h2>$1245</h2>
                        <h5>Raised Personally</h5>
                    </div>

                </div>

                <? if ($twitchUser): ?>
                    <div class="row stats-overview">
                        <!-- BEGIN twitch followers -->
                        <div class="col-xs-<?= $twitchPartner ? 6 : 12 ?> text-center stats-box">
                            <h2><?= $twitchUser->followersNumber ?></h2>
                            <h5>Twitch Followers</h5>
                        </div>
                        <!-- END twitch followers -->


                        <? if ($twitchPartner): ?>
                            <!-- BEGIN twitch subscribers -->
                            <div class="col-xs-6 text-center stats-box">
                                <h2><?= $twitchUser->subscribersNumber ?></h2>
                                <h5>Twitch Subscribers</h5>
                            </div>
                            <!-- END twitch subscribers -->
                        <? endif ?>
                    </div>


                <? endif ?>


            </div><!-- END Today Tab Pane -->

            <div class="tab-pane fade in" id="overall">

               <div class="row stats-overview">
                    <!-- HTML MARKUP - TOTAL AMOUNT RAISED FOR TODAY GOES HERE -->
                    <div class="col-xs-12 stats-box">
                        <h2>$45,567</h2>
                        <h5>Total Amount Raised</h5>
                    </div>
                    <div class="clearfix"></div>
                    <!-- HTML MARKUP - TOTAL CAMPAIGNS FOR TODAY -->
                    <div class="col-xs-4 stats-box">
                        <h2>1</h2>
                        <h5>Total Campaigns</h5>
                    </div>
                    <!-- HTML MARKUP - TOTAL DONTATIONS FOR TODAY -->
                    <div class="col-xs-4 stats-box">
                        <h2>1245</h2>
                        <h5>Total Donations</h5>
                    </div>
                    <!-- HTML MARKUP - TOTAL DONORS FOR TODAY -->
                    <div class="col-xs-4 stats-box">
                        <h2>456</h2>
                        <h5>Total Donors</h5>
                    </div>
                </div>

                <div class="row stats-overview">
                    <!-- HTML MARKUP - RAISED FOR CHARITY -->
                    <div class="col-xs-6 stats-box">
                        <h2>$1245</h2>
                        <h5>Raised for Charity</h5>
                    </div>

                    <!-- HTML MARKUP - RAISED PERSONALLY -->
                    <div class="col-xs-6 stats-box">
                        <h2>$1245</h2>
                        <h5>Raised Personally</h5>
                    </div>

                </div>

                <? if ($twitchUser): ?>
                    <div class="row stats-overview">
                        <!-- BEGIN twitch followers -->
                        <div class="col-xs-<?= $twitchPartner ? 6 : 12 ?> text-center stats-box">
                            <h2><?= $twitchUser->followersNumber ?></h2>
                            <h5>Twitch Followers</h5>
                        </div>
                        <!-- END twitch followers -->


                        <? if ($twitchPartner): ?>
                            <!-- BEGIN twitch subscribers -->
                            <div class="col-xs-6 text-center stats-box">
                                <h2><?= $twitchUser->subscribersNumber ?></h2>
                                <h5>Twitch Subscribers</h5>
                            </div>
                            <!-- END twitch subscribers -->
                        <? endif ?>
                    </div>


                <? endif ?>


            </div><!-- END Today Tab Pane -->

            <div class="tab-pane fade in" id="daterange">

                <div class="row stats-overview">
                    <!-- HTML MARKUP - TOTAL AMOUNT RAISED FOR TODAY GOES HERE -->
                    <div class="col-xs-12 stats-box">
                        <h2>$45,567</h2>
                        <h5>Total Amount Raised</h5>
                    </div>
                    <div class="clearfix"></div>
                    <!-- HTML MARKUP - TOTAL CAMPAIGNS FOR TODAY -->
                    <div class="col-xs-4 stats-box">
                        <h2>1</h2>
                        <h5>Total Campaigns</h5>
                    </div>
                    <!-- HTML MARKUP - TOTAL DONTATIONS FOR TODAY -->
                    <div class="col-xs-4 stats-box">
                        <h2>1245</h2>
                        <h5>Total Donations</h5>
                    </div>
                    <!-- HTML MARKUP - TOTAL DONORS FOR TODAY -->
                    <div class="col-xs-4 stats-box">
                        <h2>456</h2>
                        <h5>Total Donors</h5>
                    </div>
                </div>

                <div class="row stats-overview">
                    <!-- HTML MARKUP - RAISED FOR CHARITY -->
                    <div class="col-xs-6 stats-box">
                        <h2>$1245</h2>
                        <h5>Raised for Charity</h5>
                    </div>

                    <!-- HTML MARKUP - RAISED PERSONALLY -->
                    <div class="col-xs-6 stats-box">
                        <h2>$1245</h2>
                        <h5>Raised Personally</h5>
                    </div>

                </div>

                <? if ($twitchUser): ?>
                    <div class="row stats-overview">
                        <!-- BEGIN twitch followers -->
                        <div class="col-xs-<?= $twitchPartner ? 6 : 12 ?> text-center stats-box">
                            <h2><?= $twitchUser->followersNumber ?></h2>
                            <h5>Twitch Followers</h5>
                        </div>
                        <!-- END twitch followers -->


                        <? if ($twitchPartner): ?>
                            <!-- BEGIN twitch subscribers -->
                            <div class="col-xs-6 text-center stats-box">
                                <h2><?= $twitchUser->subscribersNumber ?></h2>
                                <h5>Twitch Subscribers</h5>
                            </div>
                            <!-- END twitch subscribers -->
                        <? endif ?>
                    </div>
                <? endif ?>
            </div><!-- END Today Tab Pane -->
    </div>
</section>

<div id="sidebar" class="dashboard"> <!-- BEGIN side panel -->
    <? if ($systemNotification): ?>
        <div class="alert alert-info alert-dismissible systemNotification"> <!-- BEGIN notification -->
            <button type="button" class="close" onclick="dashboardCloseSystemMessage(<?= $systemNotification->id ?>)">
                <span>&times;</span></button>
            <?= $systemNotification->message ?>
        </div> <!-- END notification -->
    <? endif; ?>
    <? if (sizeof($campaignInvites) > 0): ?>
        <?=
        $this->render('campaignInvites', [
            'campaignInvites' => $campaignInvites
        ]) ?>
    <? endif ?>
    <ul> <!-- BEGIN activity feed -->
        <li>
            <? if (sizeof($recentActivity) > 0): ?>
                <h5>Recent Activity</h5>
                <? foreach ($recentActivity as $notification): ?>
                    <i class="glyphicon glyphicon-heart-empty"></i> <?= $notification->message ?>
                <? endforeach; ?>
            <? endif; ?>
        </li>
        <li>
        </li>
    </ul>
    <!-- END activity feed -->
</div> <!-- END side panel -->