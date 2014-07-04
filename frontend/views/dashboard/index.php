<?php
use common\models\User;
/**
 * @var yii\web\View $this
 */
$this->title = 'Dashboard';

$this->registerJsFile('@web/js/dashboard/index.js', common\assets\CommonAsset::className());

?>

<? if ($systemNotification): ?>
<div>
   <div class="alert alert-info alert-dismissible systemNotification">
        <button type="button" class="close" onclick="dashboardCloseSystemMessage(<?= $systemNotification->id ?>)"><span>&times;</span></button>
        <?= $systemNotification->message ?>
   </div>
</div>
<? endif; ?>

<? if (sizeof($campaignInvites) > 0): ?>
    <?= $this->render('campaignInvites', [
        'campaignInvites' => $campaignInvites
    ]) ?>
<? endif ?>

<div class="row">
    <div class="col-sm-6">
        <? if (sizeof($recentActivity)>0): ?>
        <h3>Recent Activity</h3>
            <? foreach ($recentActivity as $notification): ?>
            <i class="glyphicon glyphicon-heart-empty"></i> <?= $notification->message ?>
            <? endforeach; ?>
        <? endif; ?>
    </div>
    <div class="col-sm-6">
        
    </div>
</div>

