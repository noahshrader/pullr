<?

use common\widgets\user\UserPhoto;
use common\components\Application;
?>



<div class="dropdown avatar-container">
    <ul class="dropdown-menu avatar-submenu" role="menu" aria-labelledby="dropdownMenu1">
        <? if (Application::IsBackend()): ?>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="admin/logout">Logout</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="settings">Settings</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="site/logout">Logout</a></li>
        <? else: ?>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="app/settings">Settings</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="app/site/logout">Logout</a></li>
        <? endif; ?>   
    </ul>
    <div class="avatar dropdown-toggle" data-toggle="dropdown">
        <?= UserPhoto::widget(['user' => Yii::$app->user->identity, 'hasLink' => false, 'options' => ['class' => 'user-photo-menu']]) ?>
    </div>
</div>