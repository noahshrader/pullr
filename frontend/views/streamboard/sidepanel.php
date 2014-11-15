<?
use yii\helpers\Url;
use yii\web\View;

/**@var $this View */
$tabsClass = $regionsNumber == 2 ? 'four-tabs' : 'three-tabs';
?>
<!-- // Layout Options Side Panel // -->
<section id="sidepanel" class="sidepanel open"
    interaction
    resizable
    resizable-callback='onResizeSidebarStop'
    resizable-config='{handles:"w", delay:0, animate:false}'   
    ng-controller='sideBarCtrl'
    >
    <div class="sidepanel-head">
        <ul class="<?= $tabsClass ?> panel-nav cf">
            <li class="active">
                <a href="<?= Url::to() ?>#donations" data-toggle="tab" class="donations">
                    <i class="icon icon-coin"></i></a>
            </li>
            <? for ($regionNumber = 1; $regionNumber <= $regionsNumber; $regionNumber++): ?>
                <li><a href="<?= Url::to() ?>#region_<?= $regionNumber?>" data-toggle="tab" class="region<?=$regionNumber?>" ng-click='regionTabChanged(regionsService.regions[<?=$regionNumber - 1;?>])'><?= $regionNumber ?></a></li>
            <? endfor ?>
            <li>
                <a href="<?= Url::to() ?>#tagsTab" data-toggle="tab">
                    <i class="icon icon-list"></i>
                </a>
            </li>
            <li>
                <a href="<?= Url::to() ?>#settingsTab" data-toggle="tab">
                    <i class="icon icon-settings"></i>
                </a>
            </li>
        </ul>
    </div>
    <!-- We are using RegionCtrl here to be able to use ng-repeat in tab-regions -->
    <div class="tab-content" ng-controller="RegionConfigCtrl">
        <div id="donations" class="tab-pane active" ng-controller="DonationsCtrl">
            <?= $this->render('donations/donations') ?>
        </div>
        <!-- (Region 1) or (Region 2) -->
        <div class="tab-pane addpadd" id="region_{{region.regionNumber}}" ng-repeat="region in regionsService.regions">
            <?= $this->render('config/region/region') ?>
        </div>
        <div class="tab-pane addpadd" id="tagsTab">
            <?= $this->render('config/tags/tags') ?>
        </div>
        <div class="tab-pane addpadd" id="settingsTab" ng-controller="SettingsCtrl">
            <?= $this->render('config/settings/settings') ?>
        </div>
    </div>
    <a class="sidetoggle icon-menu"></a>
</section>
