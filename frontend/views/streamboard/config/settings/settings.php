<?php
use yii\web\View;
use yii\helpers\Url;

/**@var $this View */
$sourceHref = Url::to('', true) . '/source';
?>
<!-- Accordion slide four (Settings) -->
<div class="tab-content">
    <h4 class="panel-head">Settings</h4>
    <!-- General Settings (Campaigns) -->
    <div id="settings-general" class="tab-pane active">
        <?= $this->render('settings-general') ?>
    </div>
    <!-- Source Access -->
    <div id="settings-source" class="tab-pane">
        <?= $this->render('settings-source'); ?>
    </div>
</div>
<div class="right-side-footer">
    <ul class="panel-nav">
        <li class="active">
            <a href="<?= Url::to() ?>#settings-general" data-toggle="tab">
                <i class="icon-tools"></i>
                General
            </a>
        </li>
        <li>
            <a href="<?= Url::to() ?>#settings-source" data-toggle="tab">
                <i class="icon-code"></i>
                Source
            </a>
        </li>
    </ul>
</div>