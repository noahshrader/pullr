<?php
    use yii\web\View;
    use yii\helpers\Url;
    /**@var $this View*/
?>
<!-- Accordion slide four (Settings) -->
<div class="tab-pane active" id="settingsTab">
    <div>
        <ul class="nav nav-tabs cf">
            <li class="">
                <a href="<?= Url::to()?>#campaigns" data-toggle="tab" class="donations">Campaigns</a>
            </li>
            <li class="active"><a href="<?= Url::to()?>#source" data-toggle="tab" class="region1">Source</a></li>
            <li class="active"><a href="<?= Url::to()?>#downloads" data-toggle="tab" class="region1">Downloads</a></li>
        </ul>
    </div>
    <hr>
    <div class="tab-content">
        <div class="tab-pane" id="campaigns">
        </div>
        <?= $this->render('settings-source'); ?>
    </div>
</div>