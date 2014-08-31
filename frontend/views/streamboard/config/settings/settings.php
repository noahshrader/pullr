<?php
use yii\web\View;
use yii\helpers\Url;

/**@var $this View */
$sourceHref = Url::to('', true) . '/source';
?>
<!-- Accordion slide four (Settings) -->
<div class="tab-content">
    <!-- General Settings (Campaigns) -->
    <div id="settings-general" class="tab-pane active">
        <?= $this->render('settings-general') ?>
    </div>
    <!-- Source Access -->
    <div id="settings-source" class="tab-pane">
        <?= $this->render('settings-source'); ?>
    </div>
    <!-- Downloads -->
    <div id="settings-downloads" class="tab-pane">
        <div class="settings-wrap">
            <a class="btn btn-default">Streamboard for Windows</a>
            <a class="btn">Streamboard for Mac</a>
            <a class="btn">Streamboard for Linux</a>
        </div>
    </div>
</div>
<div class="right-side-footer">
    <ul class="panel-nav">
        <li class="active">
            <a href="<?= Url::to() ?>#settings-general" data-toggle="tab">General</a>
        </li>
        <li><a href="<?= Url::to() ?>#settings-source" data-toggle="tab">Source</a></li>
        <li><a href="<?= Url::to() ?>#settings-downloads" data-toggle="tab">Downloads</a></li>
    </ul>
</div>