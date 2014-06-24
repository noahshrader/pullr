<?php
use common\models\User;
/**
 * @var yii\web\View $this
 */
$this->title = 'Pullr';

$this->registerJsFile('@web/js/site/index.js', common\assets\CommonAsset::className());
?>

	<? if (\Yii::$app->user && !\Yii::$app->user->isGuest && \Yii::$app->user->identity->role == User::ROLE_ONCONFIRMATION): ?>
	    <?= $this->render('confirmationEmailModal'); ?>  
	<? endif; ?>