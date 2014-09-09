<?
use yii\helpers\Url;
use yii\web\View;

/**@var $this View */
?>
<div>
    <div class="panel-group">
        <h5>Font Style</h5>
        <div font-style ng-model="preference.fontStyle"></div>
    </div>
    <div class="panel-group">
        <h5>Font Size <span class="slider-value value">{{preference.fontSize}} px</span></h5>
        <slider ng-model="preference.fontSize" floor="{{MIN_FONT_SIZE}}" ceiling="{{MAX_FONT_SIZE}}" step="1"
                ng-change="regionChanged(region)"></slider>
    </div>
    <div class="panel-group">
        <h5>Font Color <input type="color" ng-model="preference.fontColor" ng-change="regionChanged(region)"></h5>
    </div>
    <div class="panel-group">
        <h5>Duration <span class="slider-value value">{{preference.animationDuration}} sec</span></h5>
        <slider ng-model="preference.animationDuration" floor="1" ceiling="10" step="1"
                ng-change="regionChanged(region)"></slider>
    </div>
</div>
<span ng-init="baseLink='region-'+region.regionNumber+'-preference-'+preference.preferenceType"></span>
<div>
    <ul class="sounds-graphics-tabs">
        <li class="active"><a href="<?= Url::to() ?>#{{baseLink}}-sounds" data-toggle="tab" class="icon-volume-more"></a></li>
        <li><a href="<?= Url::to() ?>#{{baseLink}}-images" data-toggle="tab" class="icon-picture"></a></li>
    </ul>
</div>
<div class="tab-content sounds-graphics-content">
    <div id="{{baseLink}}-sounds" class="tab-pane active">
        <?= $this->render('alerts-files-gallery', [
            'fileType' => 'sound'
        ]) ?>
    </div>
    <div id="{{baseLink}}-images" class="tab-pane">
        <?= $this->render('alerts-files-gallery', [
            'fileType' => 'image'
        ]) ?>
    </div>
</div>