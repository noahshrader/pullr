<?
use yii\helpers\Url;
use yii\web\View;
use frontend\models\streamboard\WidgetAlertsPreference;

$animationStyleList = WidgetAlertsPreference::$ANIMATION_STYLE;
/**@var $this View */
?>
<div>
    <div class="module first">
       <div class="panel-group">
            <h5>Alert Text</h5>
                <div class="hint--bottom" data-hint="Custom Tags:&#10;[[DonorName]]&#10;[[DonorAmount]]&#10;[[CampaignName]]&#10;[[FollowerName]]&#10;[[SubscriberName]]">
                    <textarea ng-model="preference.alertText" ng-change="alertTextChange(region)"></textarea>
                </div>

        </div>
    </div>
    <div class="module">
        <a class="settingtoggle">Font Settings</a>
        <div class="module-settings">
            <div class="panel-group">
                <h5>Font Style</h5>
                <span font-style ng-model="preference.fontStyle"></span>
            </div>
            <div class="panel-group">
                <h5>Font Color <input colorpicker="hex" colorpicker-position="left" colorpicker-with-input="true"
                                      ng-model="preference.fontColor" ng-change="regionChanged(region)"
                                      class="color-choice" ng-style="{'background-color':preference.fontColor}"></h5>
            </div>
            <div class="panel-group">
                <h5>Font Size <span class="slider-value value">{{preference.fontSize}} px</span></h5>
                <slider ng-model="preference.fontSize" floor="{{MIN_FONT_SIZE}}" ceiling="{{MAX_FONT_SIZE}}" step="1"
                        ng-change="regionChanged(region)"></slider>
            </div>
            <div class="panel-group">
                <h5>Font Weight <span class="slider-value value">{{preference.fontWeight}}</span></h5>
                <slider ng-model="preference.fontWeight" floor="{{MIN_FONT_WEIGHT}}" ceiling="{{MAX_FONT_WEIGHT}}" step="100"
                        ng-change="regionChanged(region)"></slider>                     
            </div>
            <div class="panel-group">
                <h5>Text Alignment</h5>
                <div class="btn-group align">
                    <label ng-model="preference.textAlignment" btn-radio="'left'" ng-click="changeAlertTextAlignment('left', preference, region)"><i class="mdi-editor-format-align-left"></i></label>
                    <label ng-model="preference.textAlignment" btn-radio="'center'" ng-click="changeAlertTextAlignment('center', preference, region)"><i class="mdi-editor-format-align-center"></i></label>
                    <label ng-model="preference.textAlignment" btn-radio="'right'" ng-click="changeAlertTextAlignment('right', preference, region)"><i class="mdi-editor-format-align-right"></i></label>
                </div>
            </div>
            <div class="panel-group">
                <span class='checkbox'>
                    <label ng-class="{on:preference.fontUppercase}">
                        <input type="checkbox" ng-model="preference.fontUppercase" ng-change="regionChanged(region)">
                        Uppercase
                    </label>
                </span>
            </div>
        </div>
    </div>
    <div class="module">
        <a class="settingtoggle">General Settings</a>
        <div class="module-settings">
            <div class="panel-group">
                <h5>Animation style</h5>
                <select ui-select2="{minimumResultsForSearch: -1}" ng-model="preference.animationDirection" ng-change="regionChanged(region)"
                     data-placeholder="Select one...">
                    <option value=""></option>
                    <? foreach ($animationStyleList as $animationName => $animationStyle) :?>
                        <option value='<?=$animationStyle[0]; ?>, <?=$animationStyle[1]; ?>'><?=$animationName; ?></option>
                    <? endforeach; ?>
                    
                </select>
            </div>
            <div class="panel-group">
                <h5>Duration <span class="slider-value value">{{preference.animationDuration}} sec</span></h5>
                <slider ng-model="preference.animationDuration" floor="1" ceiling="10" step="1"
                        ng-change="regionChanged(region)"></slider>
            </div>
            <div class="panel-group">
                <div class='checkbox'>
                    <label ng-class="{on:preference.hideAlertText}">
                        <input type="checkbox" ng-model="preference.hideAlertText" ng-change="regionChanged(region)">
                        Hide alert text
                    </label>
                    <label ng-class="{on:preference.hideAlertImage}">
                        <input type="checkbox" ng-model="preference.hideAlertImage" ng-change="regionChanged(region)">
                        Hide alert image
                    </label>
                </div>            
            </div>
        </div>
    </div>
</div>
<span ng-init="baseLink='region-'+region.regionNumber+'-preference-'+preference.preferenceType"></span>
<div class="module">
    <a class="settingtoggle">Media Settings</a>
    <div class="module-settings">
        <ul class="library-tabs cf">
            <li class="active">
                <a href="<?= Url::to() ?>#{{baseLink}}-sounds" data-toggle="tab">
                    <i class="mdi-av-volume-up"></i>
                    Sounds
                </a>
            </li>
            <li>
                <a href="<?= Url::to() ?>#{{baseLink}}-images" data-toggle="tab">
                    <i class="mdi-image-panorama"></i>
                    Graphics
                </a>
            </li>
        </ul>
        <div class="tab-content sounds-graphics-content">
            <div id="{{baseLink}}-sounds" class="tab-pane active">
                <div class="panel-group volume">
                    <h5>Volume</h5>
                    <slider ng-model="preference.volume" floor="0" ceiling="100" step="1"
                            ng-change="regionChanged(region)"></slider>
                </div>
                <?=
                $this->render('alerts-files-gallery', [
                    'fileType' => 'sound'
                ]) ?>
            </div>
            <div id="{{baseLink}}-images" class="tab-pane">
                <?=
                $this->render('alerts-files-gallery', [
                    'fileType' => 'image'
                ]) ?>
            </div>
        </div>
    </div>
</div>