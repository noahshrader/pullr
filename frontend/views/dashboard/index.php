<?php
use common\models\User;
/**
 * @var yii\web\View $this
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
</section>

<div id="sidebar" class="dashboard"> <!-- BEGIN side panel -->
    <div class="campaign-invites"> <!-- BEGIN campaign invites -->
        <? if (sizeof($campaignInvites) > 0): ?>
            <?= $this->render('campaignInvites', [
                'campaignInvites' => $campaignInvites
            ]) ?>
        <? endif ?>
    </div> <!-- END campaign invites -->
    <ul> <!-- BEGIN activity feed -->
        <li class="col-sm-6">
            <? if (sizeof($recentActivity)>0): ?>
            <h3>Recent Activity</h3>
                <? foreach ($recentActivity as $notification): ?>
                <i class="glyphicon glyphicon-heart-empty"></i> <?= $notification->message ?>
                <? endforeach; ?>
            <? endif; ?>
        </li>
        <li class="col-sm-6">
        </li>
    </ul> <!-- END activity feed -->
</div> <!-- END side panel -->