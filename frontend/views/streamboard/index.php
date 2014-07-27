<?
use yii\helpers\Url;
use yii\web\View;

/**@var $this View */
?>
<div ng-app="streamboardApp">
    <!-- // Layout Options Side Panel // -->
    <section id="sidepanel" class="sidepanel open resizable">
        <div class="sidepanel-head">
            <ul class="nav nav-tabs four-tabs cf">
                <li class="active">
                    <a href="<?= Url::to() ?>#donations" data-toggle="tab" class="donations"><i
                            class="icon icon-cash"></i></a>
                </li>
                <li><a href="<?= Url::to() ?>#region_1" data-toggle="tab" class="region1">1</a></li>
                <li><a href="<?= Url::to() ?>#region_2" data-toggle="tab" class="region2">2</a></li>
                <li><a href="<?= Url::to() ?>#settingsTab" data-toggle="tab"><i class="icon icon-settings"></i></a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div id="donations" class="tab-pane active" data-ng-controller="DonationsCtrl">
                <?= $this->render('donations') ?>
            </div>
            <!-- Accordion slide two (Region 1) -->
            <div class="tab-pane" id="region_1">
                <h3>Region 1</h3>
            </div>
            <!-- Accordion slide three (Region 2) -->
            <div class="tab-pane" id="region_2">
                <h3>Region 2</h3>
            </div>
            <div class="tab-pane" id="settingsTab">
                <?= $this->render('settings') ?>
            </div>
        </div>
    </section>

</div>
