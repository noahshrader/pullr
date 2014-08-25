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

<div class="dashboard-wrap pane">
    <section id="content" class="dashboard">
        <span class="corner"></span>
        <div class="dashboard-data-wrap">
            <? if ($systemNotification): ?> <!-- BEGIN notification -->
                <div class="alert alert-info alert-dismissible systemNotification module">
                    <i class="icon-console2"></i>
                    <button type="button" class="close" onclick="dashboardCloseSystemMessage(<?= $systemNotification->id ?>)">
                        <span>&times;</span>
                    </button>
                    <?= $systemNotification->message ?>
                </div>
            <? endif; ?> <!-- END notification -->
            
            <ul class="content-nav cf">
                <li class="active">
                    <a href="#overall" data-toggle="tab">Overall</a>
                </li>
                <li>
                    <a href="#today" data-toggle="tab">Today</a>
                </li>
                <li>
                    <a href="#month" data-toggle="tab">This Month</a>
                </li>
            </ul>
            <div class="tab-content">
                <!-- Overall -->
                <div class="tab-pane in active" id="overall">
                    <section class="stats-overview main-totals module cf">
                        <!-- main total -->
                        <div class="col-xs-12 raised-total stats-box">
                            <h1>$<?= number_format($dashboard['overall']['totalRaised']) ?></h1>
                            <h5>Total Amount Raised</h5>
                        </div>
                        <!-- secondary totals -->
                        <div class="raised-group-stats cf">
                            <div class="col-xs-6 stats-box">
                                <h2>$<?= number_format($dashboard['overall']['charityRaised']) ?></h2>
                                <h5>Raised for Charity</h5>
                            </div>
                            <div class="col-xs-6 stats-box">
                                <h2>$<?= number_format($dashboard['overall']['personalRaised']) ?></h2>
                                <h5>Raised Personally</h5>
                            </div>
                        </div>
                    </section>
                    <section class="stats-overview other-raised-totals module cf">
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['overall']['totalCampaigns'] ?></h2>
                            <h5>Total Campaigns</h5>
                        </div>
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['overall']['totalDonations'] ?></h2>
                            <h5>Total Donations</h5>
                        </div>
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['overall']['totalDonors'] ?></h2>
                            <h5>Total Donors</h5>
                        </div>
                    </section>
                    <!-- Twitch Stats -->
                    <? if ($twitchUser): ?>
                    <section class="row stats-overview twitch-stats module">
                        <i class="icon-twitch2 group-header"></i>
                        <div class="group-stats-wrap">
                            <div class="col-xs-<?= $twitchPartner ? 6 : 12 ?> text-center stats-box">
                                <h2><?= $twitchUser->followersNumber ?></h2>
                                <h5>Followers</h5>
                            </div>
                            <? if ($twitchPartner): ?>
                            <div class="col-xs-6 text-center stats-box">
                                <h2><?= $twitchUser->subscribersNumber ?></h2>
                                <h5>Subscribers</h5>
                            </div>
                            <? endif ?>
                        </div>
                    </section>
                    <? endif ?>
                </div>
                <!-- Today -->
                <div class="tab-pane in" id="today">
                    <section class="stats-overview main-totals module cf">
                        <!-- main total -->
                        <div class="col-xs-12 raised-total stats-box">
                            <h1>$<?= number_format($dashboard['today']['totalRaised']) ?></h1>
                            <h5>Total Amount Raised</h5>
                        </div>
                        <!-- secondary totals -->
                        <div class="raised-group-stats cf">
                            <div class="col-xs-6 stats-box">
                                <h2>$<?= number_format($dashboard['today']['charityRaised']) ?></h2>
                                <h5>Raised for Charity</h5>
                            </div>
                            <div class="col-xs-6 stats-box">
                                <h2>$<?= number_format($dashboard['today']['personalRaised']) ?></h2>
                                <h5>Raised Personally</h5>
                            </div>
                        </div>
                    </section>
                    <section class="stats-overview other-raised-totals module cf">
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['today']['totalCampaigns'] ?></h2>
                            <h5>Total Campaigns</h5>
                        </div>
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['today']['totalDonations'] ?></h2>
                            <h5>Total Donations</h5>
                        </div>
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['today']['totalDonors'] ?></h2>
                            <h5>Total Donors</h5>
                        </div>
                    </section>
                    <!-- Twitch Stats -->
                    <? if ($twitchUser): ?>
                    <section class="row stats-overview twitch-stats module">
                        <i class="icon-twitch2 group-header"></i>
                        <div class="group-stats-wrap">
                            <div class="col-xs-<?= $twitchPartner ? 6 : 12 ?> text-center stats-box">
                                <h2><?= $twitchUser->followersNumber ?></h2>
                                <h5>Followers</h5>
                            </div>
                            <? if ($twitchPartner): ?>
                            <div class="col-xs-6 text-center stats-box">
                                <h2><?= $twitchUser->subscribersNumber ?></h2>
                                <h5>Subscribers</h5>
                            </div>
                            <? endif ?>
                        </div>
                    </section>
                    <? endif ?>
                </div>
                <div class="tab-pane in" id="month">
                    <section class="stats-overview main-totals module cf">
                        <!-- main total -->
                        <div class="col-xs-12 raised-total stats-box">
                            <h1>$<?= number_format($dashboard['month']['totalRaised']) ?></h1>
                            <h5>Total Amount Raised</h5>
                        </div>
                        <!-- secondary totals -->
                        <div class="raised-group-stats cf">
                            <div class="col-xs-6 stats-box">
                                <h2>$<?= number_format($dashboard['month']['charityRaised']) ?></h2>
                                <h5>Raised for Charity</h5>
                            </div>
                            <div class="col-xs-6 stats-box">
                                <h2>$<?= number_format($dashboard['month']['personalRaised']) ?></h2>
                                <h5>Raised Personally</h5>
                            </div>
                        </div>
                    </section>
                    <section class="stats-overview other-raised-totals module cf">
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['month']['totalCampaigns'] ?></h2>
                            <h5>Total Campaigns</h5>
                        </div>
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['month']['totalDonations'] ?></h2>
                            <h5>Total Donations</h5>
                        </div>
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['month']['totalDonors'] ?></h2>
                            <h5>Total Donors</h5>
                        </div>
                    </section>
                    <!-- Twitch Stats -->
                    <? if ($twitchUser): ?>
                    <section class="row stats-overview twitch-stats module">
                        <i class="icon-twitch2 group-header"></i>
                        <div class="group-stats-wrap">
                            <div class="col-xs-<?= $twitchPartner ? 6 : 12 ?> text-center stats-box">
                                <h2><?= $twitchUser->followersNumber ?></h2>
                                <h5>Followers</h5>
                            </div>
                            <? if ($twitchPartner): ?>
                            <div class="col-xs-6 text-center stats-box">
                                <h2><?= $twitchUser->subscribersNumber ?></h2>
                                <h5>Subscribers</h5>
                            </div>
                            <? endif ?>
                        </div>
                    </section>
                    <? endif ?>
                </div>
            </div>
        </div>
        <div id="sidebar" class="dashboard pane">
            <div class="invites-wrap"> <!-- BEGIN campaign invites -->
                <? if (sizeof($campaignInvites) > 0): ?>
                     <?=
                    $this->render('campaignInvites', [
                        'campaignInvites' => $campaignInvites
                    ]) ?>
                <? endif ?>
            </div> <!-- END campaign invites -->
            <div class="activity-wrap"> <!-- BEGIN activity -->
                <ul class="activity-feed module">
                    <h5 class="module-title">Activity</h5>
                    <? if (sizeof($recentActivity) > 0): ?>
                        <? foreach ($recentActivity as $notification): ?>
                            <li><span><?= $notification->message ?></span></li>
                        <? endforeach; ?>
                    <? else: ?>
                        <div class="no-recent-activity">No recent activity</div>
                    <? endif ?>
                </ul>
            </div> <!-- END activity -->
        </div>
    </section>
</div>