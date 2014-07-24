<?php
use common\models\User;
use common\models\twitch\TwitchUser;
use common\models\notifications\SystemNotification;
/**
 * @var yii\web\View $this
 * @var SystemNotification $systemNotification
 * @var TwitchUser $twitchUser
 */
$this->title = 'Dashboard';

$this->registerJsFile('@web/js/dashboard/index.js', common\assets\CommonAsset::className());

?>

<section id="content">
    <? if ($systemNotification): ?>
       <div class="alert alert-info alert-dismissible systemNotification"> <!-- BEGIN notification -->
            <button type="button" class="close" onclick="dashboardCloseSystemMessage(<?= $systemNotification->id ?>)"><span>&times;</span></button>
            <?= $systemNotification->message ?>
       </div> <!-- END notification -->
    <? endif; ?>
    <? if ($twitchUser): ?>
        <div class="row stats-overview">
            <div class="col-xs-6 text-center stats-box">
                <h2><?= $twitchUser->followersNumber ?></h2>
                <h5>Twitch Followers</h5>
            </div>
        </div>
    <? endif ?>
</section>

<div id="sidebar" class="dashboard"> <!-- BEGIN side panel -->
    <? if (sizeof($campaignInvites) > 0): ?>
        <?= $this->render('campaignInvites', [
            'campaignInvites' => $campaignInvites
        ]) ?>
    <? endif ?>
    <ul> <!-- BEGIN activity feed -->
        <li>
            <? if (sizeof($recentActivity)>0): ?>
            <h5>Recent Activity</h5>
            <? foreach ($recentActivity as $notification): ?>
            <i class="glyphicon glyphicon-heart-empty"></i> <?= $notification->message ?>
            <? endforeach; ?>
            <? endif; ?>
        </li>
        <li>
        </li>
    </ul> <!-- END activity feed -->
</div> <!-- END side panel -->