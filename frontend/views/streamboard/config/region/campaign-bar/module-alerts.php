<? 
use yii\helpers\Url;
use frontend\models\streamboard\WidgetAlertsPreference;

$animationStyleList = WidgetAlertsPreference::$ANIMATION_STYLE;
?>
<div class="module first">
    <div class="panel-group">
        <h5>Alerts</h5>
        <div class="checkbox">
            <label ng-class="{on:module.includeFollowers}">
                <input type="checkbox" ng-model="module.includeFollowers" ng-change="regionChanged(region)">
                Followers
            </label>
        </div>
        <div ng-show="module.includeFollowers" class="hint--bottom" data-hint="You are available following variables&#10;[[CampaignName]]&#10;[[FollowerName]]">
            <textarea ng-model="module.followerText"  maxlength="200" placeholder="Alert Text" ng-change="alertTextChange(region)"></textarea>
        </div>

        <div class="checkbox">
            <label ng-class="{on:module.includeSubscribers}">
                <input type="checkbox" ng-model="module.includeSubscribers" ng-change="regionChanged(region)">
                Subscribers
            </label>
        </div>
        <div ng-show="module.includeSubscribers" class="hint--top" data-hint="You are available following variables&#10;[[CampaignName]]&#10;[[SubscriberName]]">
            <textarea ng-model="module.subscriberText"  maxlength="200" placeholder="Alert Text" ng-change="alertTextChange(region)"></textarea>
        </div>
        <div class="checkbox">
            <label ng-class="{on:module.includeDonations}">
                <input type="checkbox" ng-model="module.includeDonations" ng-change="regionChanged(region)">
                Donations
            </label>
        </div>
        <div ng-show="module.includeDonations" class="hint--top" data-hint="You are available following variables&#10;[[DonorName]]&#10;[[DonorAmount]]&#10;[[CampaignName]]">
            <textarea ng-model="module.donationText"  maxlength="200" placeholder="Alert Text" ng-change="alertTextChange(region)"></textarea>
        </div>
    </div>
</div>

<div class="module">
    <div class="panel-group">
        <h5>Font Style</h5>
        <div font-style ng-model="module.fontStyle"></div>
    </div>
    <div class="panel-group">
        <h5>Font Color <input colorpicker="hex" colorpicker-position="left" colorpicker-with-input="true" ng-model="module.fontColor" ng-change="regionChanged(region)" class="color-choice" ng-style="{'background-color':module.fontColor}"></h5>
    </div>
    <div class="panel-group">
        <h5>Font Size <span class="slider-value value">{{module.fontSize}} px</span></h5>
        <slider ng-model="module.fontSize" floor="{{MIN_FONT_SIZE}}" ceiling="{{MAX_FONT_SIZE}}" step="1"
                ng-change="regionChanged(region)"></slider>
    </div>
    <div class="panel-group">
        <h5>Font Weight <span class="slider-value value">{{module.fontWeight}}</span></h5>
        <slider ng-model="module.fontWeight" floor="{{MIN_FONT_WEIGHT}}" ceiling="{{MAX_FONT_WEIGHT}}" step="100"
                ng-change="regionChanged(region)"></slider>
    </div>
    <div class="panel-group">
        <h5>Font Color <input colorpicker="hex" colorpicker-position="left" colorpicker-with-input="true" ng-model="module.fontColor" ng-change="regionChanged(region)" class="color-choice" ng-style="{'background-color':module.fontColor}"></h5>
    </div>
</div>
<div class="module">
    <div class="panel-group">
        <h5>Background Color <input colorpicker="hex" colorpicker-position="left" colorpicker-with-input="true" ng-model="module.backgroundColor" ng-change="regionChanged(region)" class="color-choice" ng-style="{'background-color':module.backgroundColor}"></h5>
    </div>
</div>
<div class="module">
    <div class="panel-group">
        <h5>Animation Style</h5>
        <select ui-select2="{minimumResultsForSearch: -1}" ng-model="module.animationDirection" ng-change="regionChanged(region)"
             data-placeholder="Select one...">
            <option value=""></option>
            <? foreach ($animationStyleList as $animationName => $animationStyle) :?>
                <option value='<?=$animationStyle[0]; ?>, <?=$animationStyle[1]; ?>'><?=$animationName; ?></option>
            <? endforeach; ?>
        </select>
    </div>
</div>
<div class="module">
    <div class="panel-group">
        <h5>Delay <span class="slider-value value">{{module.animationDelay}} sec</span></h5>
        <slider ng-model="module.animationDelay" floor="0" ceiling="30" step="1"
                ng-change="regionChanged(region)"></slider>
    </div>
    <div class="panel-group">
        <h5>Duration <span class="slider-value value">{{module.animationDuration}} sec</span></h5>
        <slider ng-model="module.animationDuration" floor="1" ceiling="10" step="1"
                ng-change="regionChanged(region)"></slider>
    </div>
</div>
<div class="module" ng-init="baseLink='region-'+region.regionNumber+'-preference-campaign-alert'; preference=module">
    <ul class="library-tabs cf">   
        <li class="active">
            <a href="<?= Url::to() ?>#{{baseLink}}-sounds" data-toggle="tab">
                <i class="icon-volume-more"></i>
                Sounds
            </a>
        </li>     
        <li>
            <a href='<?= Url::to() ?>#{{baseLink}}-images' data-toggle="tab">
                <i class="icon-picture"></i>
                Background
            </a>
        </li>
    </ul>
    <div class="tab-content sounds-graphics-content">
        <div id="{{baseLink}}-sounds" class="tab-pane active" ng-init="preference">
            <div class="panel-group volume">
                <h5>Volume</h5>
                <slider ng-model="preference.volume" floor="0" ceiling="100" step="1"
                        ng-change="regionChanged(region)"></slider>
            </div>
            <?=
            $this->render('../region-alerts/alerts-files-gallery', [
                'fileType' => 'sound'
            ]) ?>
        </div>
        <div id="{{baseLink}}-images" class="tab-pane">
            <div child-scope="" ng-init="fileType = 'campaignBackground';uploadError = null;" class="ng-scope">                        
                <div class="tab-content sounds-graphics-content">
                    <div id="region-1-preference-donations-images-custom" class="tab-pane active">
                        <div class="error">
                            {{error}}
                        </div>
                        <div class="panel-group">
                            <div class="uploader" ng-file-drop="onFileUpload($files, fileType, this)" ng-file-drag-over-class="uploader-drag-over">
                                <span>Drops Files Here</span>
                            </div>
                        </div>
                        <div class="files-container">
                            <div ng-repeat="file in (alertMediaManagerService.customCampaignBackgrounds)">
                                <div class="panel-group media-item images cf" ng-class="{selected: file==module.background}" ng-click="selectCampaignAlertBackground(module, file, region)">
                                    <img class='alert-image-preview' ng-src='{{alertMediaManagerService.getCampaignBackgroundUrl(file,<?=json_encode(WidgetAlertsPreference::FILE_TYPE_CUSTOM) ?>)}}'>
                                    <div class='mediaActions'>
                                        <i class="icon icon-close" ng-click="removeCampaignBackground(file, region, $event)"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>