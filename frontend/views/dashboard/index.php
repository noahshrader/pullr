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
    <? if ($systemNotification): ?>
        <div class="alert alert-info alert-dismissible systemNotification"> <!-- BEGIN notification -->
            <button type="button" class="close" onclick="dashboardCloseSystemMessage(<?= $systemNotification->id ?>)">
                <span>&times;</span></button>
            <?= $systemNotification->message ?>
        </div> <!-- END notification -->
    <? endif; ?>
    
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

            <div class="tab-pane fade in" id="month">

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