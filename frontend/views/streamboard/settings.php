<?php
    use yii\web\View;
    use yii\helpers\Url;
    /**@var $this View*/
    $sourceHref = Url::to('', true).'/source';
?>
<!-- Accordion slide four (Settings) -->
<div class="tab-pane active" id="settingsTab">
    <div id="streamboard-settings-header" class="text-center">
        <button class="btn btn-primary" onclick="window.prompt('Copy to clipboard: CTRL+C, Enter', ' <?= $sourceHref ?>');">Copy Link</button>
    </div>
    <div class="tab-content">
        <div class="tab-pane" id="campaigns">
            campaigns
        </div>
        <?= $this->render('settings-source'); ?>
        <div class="tab-pane" id="downloads">
            downloads
        </div>
    </div>

    <div id="settings-footer">
        <ul class="nav nav-tabs bottom-panel-nav settings-bottom-tabs">
            <li>
                <a href="<?= Url::to()?>#campaigns" data-toggle="tab" class="donations">Campaigns</a>
            </li>
            <li class="active"><a href="<?= Url::to()?>#source" data-toggle="tab" class="region1">Source</a></li>
            <li><a href="<?= Url::to()?>#downloads" data-toggle="tab" class="region1">Downloads</a></li>
        </ul>
    </div>

</div>