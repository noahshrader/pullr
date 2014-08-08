<?php
use yii\web\View;
use yii\helpers\Url;

/**@var $this View */
$sourceHref = Url::to('', true) . '/source';
?>
<!-- Accordion slide four (Settings) -->
<div class="tab-content">
    <div id="settings-general" class="tab-pane active">
        <?= $this->render('settings-general') ?>
    </div>
    <div id="settings-source" class="tab-pane">
        <?= $this->render('settings-source'); ?>
    </div>
    <div id="settings-downloads" class="tab-pane">
        downloads
    </div>
</div>

<div class="right-side-footer">
    <ul class="nav nav-tabs bottom-panel-nav settings-bottom-tabs">
        <li class="active">
            <a href="<?= Url::to() ?>#settings-general" data-toggle="tab">General</a>
        </li>
        <li><a href="<?= Url::to() ?>#settings-source" data-toggle="tab">Source</a></li>
        <li><a href="<?= Url::to() ?>#settings-downloads" data-toggle="tab">Downloads</a></li>
    </ul>
</div>
