<?php
use common\models\User;
/**
 * @var yii\web\View $this
 */
$this->title = 'Pullr';

$this->registerJsFile('@web/js/site/index.js', common\assets\CommonAsset::className());
?>

<div id="intro-wrap" class="col-md-6 push-md-6">
	<div>
	    <h1><a class="logo icon-pullr"></a></h1>
	</div>

	<? if (\Yii::$app->user && !\Yii::$app->user->isGuest && \Yii::$app->user->identity->role == User::ROLE_ONCONFIRMATION): ?>
	    <?= $this->render('confirmationEmailModal'); ?>  
	<? endif; ?>

</div>
