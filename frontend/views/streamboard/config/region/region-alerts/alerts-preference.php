<?
use yii\helpers\Url;
use yii\web\View;

/**@var $this View */
?>
<div>
    <div class="module first">
        <div class="panel-group text-center">
           <button class="btn btn-primary btn-sm" ng-click="streamService.testData(preference.preferenceType, 1)">Test {{preference.preferenceType}}</button>
        </div>
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
            <h5>Font Color <input colorpicker="hex" colorpicker-position="left" colorpicker-with-input="true"
                                  type="text" ng-model="preference.fontColor" ng-change="regionChanged(region)"
                                  class="color-choice" ng-style="{'background-color':preference.fontColor}"></h5>
        </div>
    </div>
    <div class="module">
        <div class="panel-group">
            <h5>Duration <span class="slider-value value">{{preference.animationDuration}} sec</span></h5>
            <slider ng-model="preference.animationDuration" floor="1" ceiling="10" step="1"
                    ng-change="regionChanged(region)"></slider>
        </div>
    </div>
</div>
<span ng-init="baseLink='region-'+region.regionNumber+'-preference-'+preference.preferenceType"></span>
<div class="module">
    <ul class="library-tabs cf">
        <li class="active"><a href="<?= Url::to() ?>#{{baseLink}}-sounds" data-toggle="tab"><i
                    class="icon-volume-more"></i>Sounds</a></li>
        <li><a href="<?= Url::to() ?>#{{baseLink}}-images" data-toggle="tab"><i class="icon-picture"></i>Graphics</a>
        </li>
    </ul>
    <div class="tab-content sounds-graphics-content">
        <div id="{{baseLink}}-sounds" class="tab-pane active">
            <?=
            $this->render('alerts-files-gallery', [
                'fileType' => 'sound'
            ]) ?>
            <div class="panel-group">
                <h5>Volume</h5>
                <slider ng-model="preference.volume" floor="0" ceiling="100" step="1"
                        ng-change="regionChanged(region)"></slider>
            </div>
        </div>
        <div id="{{baseLink}}-images" class="tab-pane">
            <?=
            $this->render('alerts-files-gallery', [
                'fileType' => 'image'
            ]) ?>
        </div>
    </div>
</div>