<?
use common\widgets\user\UserPhoto;
use common\components\Application;
?>

<div class="dropdown avatar-container">
    <ul class="avatar-submenu right" role="menu" aria-labelledby="dropdownMenu1">
        <? if (Application::IsBackend()): ?>
            <li role="presentation"><a role="menuitem" href="admin/logout"><i class="icon mdi-communication-live-help"></i>Logout</a></li>
        <? else: ?>
            <li role="presentation"><a role="menuitem" href="http://support.pullr.io" target="_blank"><i class="icon mdi-communication-live-help"></i>Support</a></li>
            <li role="presentation"><a role="menuitem" href="app/site/logout"><i class="icon mdi-action-exit-to-app"></i>Logout</a></li>
        <? endif; ?>   
    </ul>
	<div class="avatar dropdown-toggle" data-toggle="dropdown">
		<?= UserPhoto::widget(['user' => Yii::$app->user->identity, 'hasLink' => false, 'showName'  => YII_ENV_DEV, 'options' => ['class' => 'user-photo-menu']]) ?>
	</div>
</div>