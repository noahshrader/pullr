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