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

