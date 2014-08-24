<?
use yii\helpers\Url;
use yii\web\View;

/**@var $this View */
$tabsClass = $regionsNumber == 2 ? 'four-tabs' : 'three-tabs';
?>
<div ng-app="streamboardApp">
    <?= $this->render('streamboard-js-variables') ?>
    <!-- // Layout Options Side Panel // -->
    <section id="sidepanel" class="sidepanel open resizable pane">
        <div class="sidepanel-head">
            <ul class="<?= $tabsClass ?> panel-nav cf">
                <li class="active">
                    <a href="<?= Url::to() ?>#donations" data-toggle="tab" class="donations">
                        <i class="icon icon-coin"></i></a>
                </li>
                <? for ($regionNumber = 1; $regionNumber <= $regionsNumber; $regionNumber++): ?>
                    <li><a href="<?= Url::to() ?>#region_<?= $regionNumber?>" data-toggle="tab" class="region<?=$regionNumber?>"><?= $regionNumber ?></a></li>
                <? endfor ?>
                <li><a href="<?= Url::to() ?>#settingsTab" data-toggle="tab"><i class="icon icon-settings"></i></a></li>
            </ul>
        </div>
        <!-- We are using RegionCtrl here to be able to use ng-repeat in tab-regions -->
        <div class="tab-content" ng-controller="RegionCtrl">
            <div id="donations" class="tab-pane active" ng-controller="DonationsCtrl">
                <?= $this->render('donations') ?>
            </div>
            <!-- Accordion slide (Region 1) or (Region 2) -->
            <div class="tab-pane region" id="region_{{region.regionNumber}}" ng-repeat="region in regions">
                <?= $this->render('region/region') ?>
            </div>
            <div class="tab-pane" id="settingsTab" ng-controller="SettingsCtrl">
                <?= $this->render('settings') ?>
            </div>
        </div>
    </section>
</div>