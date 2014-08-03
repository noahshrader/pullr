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
                <!-- HTML MARKUP - Overview Stats -->
                <div class="row stats-overview">
                    <div class="col-xs-12 stats-box raised-total">
                        <i class="icon-coin"></i>
                        <h1>$45,567</h1>
                        <h5>Total Amount Raised</h5>
                    </div>
                    <div class="other-raised-totals">
                        <div>
                            <h2>1</h2>
                            <h5>Total Campaigns</h5>
                        </div>
                        <div>
                            <h2>1245</h2>
                            <h5>Total Donations</h5>
                        </div>
                        <div>
                            <h2>456</h2>
                            <h5>Total Donors</h5>
                        </div>
                    </div>
                </div>

                <div class="row stats-overview raised-group-stats">
                    <i class="group-header icon-coins"></i>
                    <div class="group-stats-wrap">
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
                </div>

                <!-- Twitch Data -->
                <? if ($twitchUser): ?>
                    <div class="row stats-overview twitch-stats">
                        <i class="group-header icon-twitch2"></i>
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
                    </div>
                <? endif ?>
            </div>
        </div>
    </div>
</section>

<div id="sidebar" class="dashboard"> <!-- BEGIN side panel -->
    <? if (sizeof($campaignInvites) > 0): ?>
        <?=
        $this->render('campaignInvites', [
            'campaignInvites' => $campaignInvites
        ]) ?>
    <? endif ?>
    <h5><i class="icon-chatbubble2"></i> Recent Activity</h5>
    <ul class="activity-feed module">
        <li>
            <? if (sizeof($recentActivity) > 0): ?>
                <? foreach ($recentActivity as $notification): ?>
                    <span><?= $notification->message ?></span>
                <? endforeach; ?>
            <? endif; ?>
        </li>
    </ul>
</div> <!-- END side panel -->

<? if ($systemNotification): ?> <!-- BEGIN notification -->
    <div class="alert alert-info alert-dismissible systemNotification">
        <button type="button" class="close" onclick="dashboardCloseSystemMessage(<?= $systemNotification->id ?>)">
            <span>&times;</span>
        </button>
        <?= $systemNotification->message ?>
    </div>
<? endif; ?> <!-- END notification -->