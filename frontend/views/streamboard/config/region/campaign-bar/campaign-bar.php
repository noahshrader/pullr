<? 
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\streamboard\WidgetAlertsPreference;
?>
<div class="module">
    <div class="form-group">
        <h5>Campaign</h5>
        <select ui-select2="{minimumResultsForSearch: -1}" ng-model="widget.campaignId" ng-change="regionChanged(region)"
                data-placeholder="Select a campaign...">
            <option value=""></option>
            <option ng-repeat='campaign in campaignsService.campaigns' value="{{campaign.id}}">
                {{campaign.name}}
            </option>
        </select>
    </div>
</div>
<div class="module">
    <a class="settingtoggle">Modules<i class="mdi-navigation-arrow-drop-down"></i></a>
    <div class="module-settings">
        <div class="form-group">
            <div class="checkbox">
                <label ng-class="{on:widget.alertsEnable}">
                    <input type="checkbox" ng-model="widget.alertsEnable" ng-change="toggleModule(region)">
                    Alerts
                </label>
            </div>
            <div class="checkbox">
                <label ng-class="{on:widget.messagesEnable}">
                    <input type="checkbox" ng-model="widget.messagesEnable" ng-change="toggleModule(region)">
                    Rotating Messages
                </label>
            </div>
            <div class="checkbox">
                <label ng-class="{on:widget.timerEnable}">
                    <input type="checkbox" ng-model="widget.timerEnable" ng-change="toggleModule(region)">
                    Timer
                </label>
            </div>
            <div class="checkbox">
                <label ng-class="{on:widget.progressBarEnable}">
                    <input type="checkbox" ng-model="widget.progressBarEnable" ng-change="toggleModule(region)">
                    Progress Bar
                </label>
            </div>
        </div>
    </div>
</div>
<div class="module">
    <a class="settingtoggle">Font<i class="mdi-navigation-arrow-drop-down"></i></a>
    <div class="module-settings">
        <div class="form-group">
            <h5>Font Style</h5>
            <div font-style ng-model="widget.fontStyle"></div>
        </div>
        <div class="form-group">
            <h5>Font Color <input colorpicker="hex" colorpicker-position="left" colorpicker-with-input="true" ng-model="widget.fontColor" ng-change="regionChanged(region)" class="color-choice" ng-style="{'background-color':widget.fontColor}"></h5>
        </div>
        <div class="form-group">
            <h5>Font Size <span class="slider-value value">{{widget.fontSize}} px</span></h5>
            <slider ng-model="widget.fontSize" floor="{{MIN_FONT_SIZE}}" ceiling="{{MAX_FONT_SIZE}}" step="1"
                    ng-change="regionChanged(region)"></slider>
        </div>
        <div class="form-group">
            <h5>Font Weight <span class="slider-value value">{{widget.fontWeight}}</span></h5>
            <slider ng-model="widget.fontWeight" floor="{{MIN_FONT_WEIGHT}}" ceiling="{{MAX_FONT_WEIGHT}}" step="100"
                    ng-change="regionChanged(region)"></slider>
        </div>
        <div class="form-group red">
            <div class="checkbox">
                <label ng-class="{on:widget.fontUppercase}">
                    <input type="checkbox" ng-model="widget.fontUppercase" ng-change="regionChanged(region)">
                    Uppercase
                </label>
            </div>
        </div>
        <div class="form-group red">
            <span class='checkbox'>
                <label ng-class="{on:widget.textShadow}">
                    <input type="checkbox" ng-model="widget.textShadow" ng-change="regionChanged(region)">
                    Text Shadow
                </label>
            </span>
        </div>
    </div>
</div>
<div class="module last">
    <a class="settingtoggle">Background<i class="mdi-navigation-arrow-drop-down"></i></a>
    <div class="module-settings">
        <div class="form-group">
            <h5>Color <input colorpicker="hex" colorpicker-position="left" colorpicker-with-input="true" ng-model="widget.backgroundColor" ng-change="regionChanged(region)" class="color-choice" ng-style="{'background-color':widget.backgroundColor}"></h5>
        </div>
        <div class="tab-content sounds-graphics-content">        
            <div id="region-1-preference-donations-images" class="tab-pane active">
                <div child-scope="" ng-init="fileType = 'campaignBackground';uploadError = null;" class="ng-scope">
                <!--main variable - preference -->
                    <div class="tab-content sounds-graphics-content">
                        <div id="region-1-preference-donations-images-custom" class="tab-pane active">
                            <div class="form-group">
                                <h5>Graphic</h5>
                                <div class="error">
                                    {{error}}
                                </div>
                                <div class="uploader" ng-file-drop="onFileUpload($files, fileType, this)" ng-file-drag-over-class="uploader-drag-over">
                                    <span>Drops Files Here</span>
                                </div>
                            </div>
                        </div>
                        <div class="files-container">
                            <div ng-repeat="file in (alertMediaManagerService.customCampaignBackgrounds)">
                                <div class="form-group media-item images cf" ng-class="{selected: file==widget.background}" ng-click="campaignBackgroundChanged(file, region)">
                                    <img class='alert-image-preview' ng-src='{{alertMediaManagerService.getCampaignBackgroundUrl(file,<?=json_encode(WidgetAlertsPreference::FILE_TYPE_CUSTOM) ?>)}}'>
                                    <div class='mediaActions'>
                                        <i class="mdi-navigation-close" ng-click="removeCampaignBackground(file, region, $event)"></i>
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
<div class="right-side-footer" ng-show=' ! hideFooter' ng-style="{width: streamboardConfig.config.sideBarWidth}">
    <ul class="panel-nav paneltoggle">
        <li ng-show="widget.alertsEnable">
            <a data-panel="alertsModule">
                <i class="mdi-av-new-releases"></i>
                Alerts
            </a>
        </li>
        <li ng-show="widget.messagesEnable">
            <a data-panel="messagesModule">
                <i class="mdi-communication-message"></i>
                Messages
            </a>
        </li>
        <li ng-show="widget.timerEnable">
            <a data-panel="timerModule">
                <i class="mdi-image-timer"></i>
                Timer
            </a>
        </li>
    </ul>

    <div class="alertsModule_panel slidepanel pane" child-scope>
        <div ng-init="module = widget.alertsModule">
            <h4 class="panel-title">
                Alerts Settings
                <button class="btn btn-sm" ng-click="streamService.testCampaignAlert(region)">Test</button>
            </h4>
            <?= $this->render('module-alerts') ?>
        </div>
    </div>
    <div class="messagesModule_panel slidepanel pane" child-scope>
        <div ng-init="module = widget.messagesModule">
            <h4 class="panel-title">Message Settings</h4>
            <?= $this->render('module-messages') ?>
        </div>
    </div>
    <div class="timerModule_panel slidepanel pane" child-scope>
        <div ng-init="module = widget.timerModule">
            <h4 class="panel-title">Timer Settings</h4>
            <?= $this->render('module-timer') ?>
        </div>
    </div>
</div>
