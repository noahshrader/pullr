<?

use common\widgets\user\UserPhoto;
use common\components\Application;
?>

<a class="avatar avatar-container"><?= UserPhoto::widget(['user' => Yii::$app->user->identity, 'hasLink' => false, 'options' => ['class' => 'user-photo-menu']]) ?></a>

<nav class="sidebar-nav nav-bottom">
    <ul>
        <? if (Application::IsBackend()): ?>
            <li role="presentation"><a role="menuitem" href="admin/logout">Logout</a></li>
        <? else: ?>
            <li role="presentation"><a role="menuitem" href="app/help">Help</a></li>
            <li role="presentation"><a role="menuitem" href="app/site/logout">Logout</a></li>
        <? endif; ?>   
    </ul>
</nav>