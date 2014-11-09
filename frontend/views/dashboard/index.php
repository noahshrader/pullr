<?php
use common\models\User;
use common\models\twitch\openIDToUser;
use common\models\notifications\SystemNotification;
use common\components\Application;
use common\components\PullrUtils;

use common\models\twitch\TwitchFollow;
use common\models\twitch\TwitchSubscription;

/**
 * @var yii\web\View $this
 * @var SystemNotification $systemNotification
 */
$this->title = 'Dashboard';

$this->registerJsFile('@web/js/dashboard/index.js', [
    'depends' => common\assets\CommonAsset::className(),
]);
$user = Application::getCurrentUser();
$twitchPartner = $user->userFields->twitchPartner;
?>

<div class="dashboard-wrap pane">
    <section id="content" class="dashboard">
        <div class="content-wrap">
            <? if ($systemNotification): ?> <!-- BEGIN notification -->
                <div class="alert alert-info alert-dismissible systemNotification module">
                    <i class="icon-gamepad4"></i>
                    <button type="button" class="close" onclick="dashboardCloseSystemMessage(<?= $systemNotification->id ?>)">
                        <span>&times;</span>
                    </button>
                    <?= $systemNotification->message ?>
                </div>
            <? endif; ?> <!-- END notification -->

            <div class="tab-content">
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
                <!-- Overall -->
                <div class="tab-pane in active" id="overall">
                    <section class="stats-overview main-totals cf">
                        <!-- main total -->
                        <div class="raised-total stats-box">
                            <h1>$<?= PullrUtils::formatNumber($dashboard['overall']['totalRaised'], 2) ?></h1>
                            <h5>Raised Overall</h5>
                        </div>
                        <!-- secondary totals -->
                        <div class="raised-group-stats cf">
                            <div class="col-xs-6 stats-box">
                                <h2>$<?= PullrUtils::formatNumber($dashboard['overall']['charityRaised'], 2) ?></h2>
                                <h5>Charity</h5>
                            </div>
                            <div class="col-xs-6 stats-box">
                                <h2>$<?= PullrUtils::formatNumber($dashboard['overall']['personalRaised'], 2) ?></h2>
                                <h5>Personal</h5>
                            </div>
                        </div>
                    </section>
                    <section class="stats-overview other-raised-totals cf">
                        <span class="stick"></span>
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['overall']['totalCampaigns'] ?></h2>
                            <h5>Campaigns</h5>
                        </div>
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['overall']['totalDonations'] ?></h2>
                            <h5>Donations</h5>
                        </div>
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['overall']['totalDonors'] ?></h2>
                            <h5>Donors</h5>
                        </div>
                    </section>
                    <!-- Twitch Stats -->
                    <? if ($openIDToUser): ?>
                    <section class="row stats-overview twitch-stats">
                        <span class="stick"></span>
                        <i class="icon-twitch2 group-header"></i>
                        <div class="group-stats-wrap">
                            <div class="col-xs-<?= $twitchPartner ? 6 : 12 ?> text-center stats-box">
                                <h2><?= $user->twitchUser->followersNumber; ?></h2>
                                <h5>Followers</h5>
                            </div>
                            <? if ($twitchPartner): ?>
                            <div class="col-xs-6 text-center stats-box">
                                <h2><?= $user->twitchUser->subscribersNumber; ?></h2>
                                <h5>Subscribers</h5>
                            </div>
                            <? endif ?>
                        </div>
                    </section>
                    <? endif ?>
                </div>
                <!-- Today -->
                <div class="tab-pane in" id="today">
                    <section class="stats-overview main-totals cf">
                        <!-- main total -->
                        <div class="col-xs-12 raised-total stats-box">
                            <h1>$<?= PullrUtils::formatNumber($dashboard['today']['totalRaised'], 2) ?></h1>
                            <h5>Raised Today</h5>
                        </div>
                        <!-- secondary totals -->
                        <div class="raised-group-stats cf">
                            <div class="col-xs-6 stats-box">
                                <h2>$<?= PullrUtils::formatNumber($dashboard['today']['charityRaised'], 2) ?></h2>
                                <h5>Charity</h5>
                            </div>
                            <div class="col-xs-6 stats-box">
                                <h2>$<?= PullrUtils::formatNumber($dashboard['today']['personalRaised'], 2) ?></h2>
                                <h5>Personal</h5>
                            </div>
                        </div>
                    </section>
                    <section class="stats-overview other-raised-totals cf">
                        <span class="stick"></span>
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['today']['totalCampaigns'] ?></h2>
                            <h5>Campaigns</h5>
                        </div>
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['today']['totalDonations'] ?></h2>
                            <h5>Donations</h5>
                        </div>
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['today']['totalDonors'] ?></h2>
                            <h5>Donors</h5>
                        </div>
                    </section>
                    <!-- Twitch Stats -->
                    <? if ($openIDToUser): ?>
                    <section class="row stats-overview twitch-stats">
                        <span class="stick"></span>
                        <i class="icon-twitch2 group-header"></i>
                        <div class="group-stats-wrap">
                            <div class="col-xs-<?= $twitchPartner ? 6 : 12 ?> text-center stats-box">
                                <h2><?= TwitchFollow::getFollowerCountByToday($user->id) ?></h2>
                                <h5>Followers</h5>
                            </div>
                            <? if ($twitchPartner): ?>
                            <div class="col-xs-6 text-center stats-box">
                                <h2><?= TwitchSubscription::getSubscriberCountByToday($user->id); ?></h2>
                                <h5>Subscribers</h5>
                            </div>
                            <? endif ?>
                        </div>
                    </section>
                    <? endif ?>
                </div>
                <div class="tab-pane in" id="month">
                    <section class="stats-overview main-totals cf">
                        <!-- main total -->
                        <div class="col-xs-12 raised-total stats-box">
                            <h1>$<?= PullrUtils::formatNumber($dashboard['month']['totalRaised'], 2) ?></h1>
                            <h5>Raised This Month</h5>
                        </div>
                        <!-- secondary totals -->
                        <div class="raised-group-stats cf">
                            <div class="col-xs-6 stats-box">
                                <h2>$<?= PullrUtils::formatNumber($dashboard['month']['charityRaised'], 2) ?></h2>
                                <h5>Charity</h5>
                            </div>
                            <div class="col-xs-6 stats-box">
                                <h2>$<?= PullrUtils::formatNumber($dashboard['month']['personalRaised'], 2) ?></h2>
                                <h5>Personal</h5>
                            </div>
                        </div>
                    </section>
                    <section class="stats-overview other-raised-totals cf">
                        <span class="stick"></span>
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['month']['totalCampaigns'] ?></h2>
                            <h5>Campaigns</h5>
                        </div>
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['month']['totalDonations'] ?></h2>
                            <h5>Donations</h5>
                        </div>
                        <div class="stats-box col-xs-6">
                            <h2><?= $dashboard['month']['totalDonors'] ?></h2>
                            <h5>Donors</h5>
                        </div>
                    </section>
                    <!-- Twitch Stats -->
                    <? if ($openIDToUser): ?>
                    <section class="row stats-overview twitch-stats">
                        <span class="stick"></span>
                        <i class="icon-twitch2 group-header"></i>
                        <div class="group-stats-wrap">
                            <div class="col-xs-<?= $twitchPartner ? 6 : 12 ?> text-center stats-box">
                                <h2><?= TwitchFollow::getFollowerCountByMonth($user->id); ?></h2>
                                <h5>Followers</h5>
                            </div>
                            <? if ($twitchPartner): ?>
                            <div class="col-xs-6 text-center stats-box">
                                <h2><?= TwitchSubscription::getSubscriberCountByMonth($user->id) ?></h2>
                                <h5>Subscribers</h5>
                            </div>
                            <? endif ?>
                        </div>
                    </section>
                    <? endif ?>
                </div>
            </div>
        </div>
    </section>
    <div id="sidebar" class="dashboard pane">
        <? if (sizeof($campaignInvites) > 0): ?>
        <div class="invites-wrap module"> <!-- BEGIN campaign invites -->
            <h5 class="module-title">Invites</h5>
            <? if (sizeof($campaignInvites) > 0): ?>
                 <?=
                $this->render('campaignInvites', [
                    'campaignInvites' => $campaignInvites
                ]) ?>
            <? else: ?>
                <span class="empty">No new invites</span>
            <? endif ?>
        </div> <!-- END campaign invites -->
        <? endif ?>
        <div class="activity-wrap module"> <!-- BEGIN activity -->
            <h5 class="module-title">Activity</h5>
            <ul class="activity-feed">
                <? if (sizeof($recentActivity) > 0): ?>
                    <? foreach ($recentActivity as $notification): ?>
                        <li><span><?= $notification->message ?></span></li>
                    <? endforeach; ?>
                <? else: ?>
                    <span class="empty">No new activity</span>
                <? endif ?>
            </ul>
        </div> <!-- END activity -->
    </div>
</div>