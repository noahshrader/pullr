<?
use yii\helpers\Url;
use yii\web\View;
/**@var $this View */
?>
<h4>Preferences</h4>
<div>
    <label>Font style</label>
    <div font-style ng-model="preference.fontStyle"></div>
</div>
<div class="form-group">
    <label>Font size</label>
    <slider ng-model="preference.fontSize" floor="{{MIN_FONT_SIZE}}" ceiling="{{MAX_FONT_SIZE}}" step="1"
            ng-change="regionChanged(region)"></slider>
    <span>{{preference.fontSize}} px</span>
</div>
<div class="form-group">
    <label>Font Color</label>
    <input type="color" ng-model="preference.fontColor" ng-change="regionChanged(region)">
</div>
<div class="form-group">
    <label>Animation duration</label>
    <slider ng-model="preference.animationDuration" floor="1" ceiling="10" step="1"
            ng-change="regionChanged(region)"></slider>
    <span>{{preference.animationDuration}} sec</span>
</div>
<h5>Media</h5>
<div>
    <ul class="nav nav-tabs sounds-graphics-tabs">
        <li class="active"><a href="<?= Url::to() ?>#region-{{region.regionNumber}}-preference-{{preference.preferenceType}}-sounds" data-toggle="tab">Sounds</a></li>
        <li><a href="<?= Url::to() ?>#region-{{region.regionNumber}}-preference-{{preference.preferenceType}}-graphics" data-toggle="tab">Graphics</a></li>
    </ul>
</div>
<div class="tab-content sounds-graphics-content">
    <div id="region-{{region.regionNumber}}-preference-{{preference.preferenceType}}-sounds" class="tab-pane active">
        <?= $this->render('alerts-sounds') ?>
    </div>
    <div id="region-{{region.regionNumber}}-preference-{{preference.preferenceType}}-images" class="tab-pane">
        <?= $this->render('alerts-sounds') ?>
    </div>
</div>

