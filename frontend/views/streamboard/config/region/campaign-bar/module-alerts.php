<? 
use frontend\models\streamboard\WidgetAlertsPreference;
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
        <div class="checkbox">
            <label ng-class="{on:module.includeSubscribers}">
                <input type="checkbox" ng-model="module.includeSubscribers" ng-change="regionChanged(region)">
                Subscribers
            </label>
        </div>
        <div class="checkbox">
            <label ng-class="{on:module.includeDonations}">
                <input type="checkbox" ng-model="module.includeDonations" ng-change="regionChanged(region)">
                Donations
            </label>
        </div>
    </div>
</div>

<div class="module">
    <div class="panel-group">
        <h5>Font style</h5>
        <div font-style ng-model="module.fontStyle"></div>
    </div>
    <div class="panel-group">
        <h5>Font size <span class="slider-value value">{{module.fontSize}} px</span></h5>
        <slider ng-model="module.fontSize" floor="{{MIN_FONT_SIZE}}" ceiling="{{MAX_FONT_SIZE}}" step="1"
                ng-change="regionChanged(region)"></slider>
    </div>
    <div class="panel-group">
        <h5>Font Color <input colorpicker="hex" colorpicker-position="left" colorpicker-with-input="true" ng-model="module.fontColor" ng-change="regionChanged(region)" class="color-choice" ng-style="{'background-color':module.fontColor}"></h5>
    </div>
    <div class="panel-group">
        <h5>Background Color <input colorpicker="hex" colorpicker-position="left" colorpicker-with-input="true" ng-model="module.backgroundColor" ng-change="regionChanged(region)" class="color-choice" ng-style="{'background-color':module.backgroundColor}"></h5>
    </div>
</div>

<div class="module">
    <div class="panel-group" ng-init="ANIMATION_DIRECTIONS=['Fade','Slide in from top','Slide in from bottom']">
        <h5>Animation Style</h5>
        <select ui-select2="{minimumResultsForSearch: -1}" ng-model="module.animationDirection" ng-change="regionChanged(region)"
             data-placeholder="Select one...">
            <option value=""></option>
            <option value="bounceIn,bounceOut">Bounce In</option>            
            <option value="bounceInUp, bounceOutUp">Bounce Down</option>
            <option value="bounceInDown, bounceOutDown">Bounce Up</option>
            <option value="bounceInLeft, bounceOutLeft">Bounce Left</option>
            <option value="bounceInRight, bounceOutRight">Bounce Right</option>
            <option value="fadeIn, fadeOut">Fade</option>                       
            <option value="flipInX, flipOutX">Flip Vertical</option>
            <option value="flipInY, flipOutY">Flip Horizontal</option>
            <option value="slideInDown, slideOutUp">Slide Down</option>
            <option value="slideInUp, slideOutDown">Slide Up</option>
            <option value="slideInLeft, slideOutLeft">Slide Left</option>
            <option value="slideInRight, slideOutRight">Slide Right</option>
        </select>
    </div>
    <div class="panel-group">
        <h5>Duration <span class="slider-value value">{{module.animationDuration}} sec</span></h5>
        <slider ng-model="module.animationDuration" floor="1" ceiling="10" step="1"
                ng-change="regionChanged(region)"></slider>
    </div>
    <div class="panel-group">
        <h5>Delay <span class="slider-value value">{{module.animationDelay}} sec</span></h5>
        <slider ng-model="module.animationDelay" floor="0" ceiling="30" step="1"
                ng-change="regionChanged(region)"></slider>
    </div>
</div>

<div class="module">
    <ul class="library-tabs cf">        
        <li class="active"><a href='#' data-toggle="tab"><i class="icon-picture"></i>Graphics</a></li>
    </ul>
    <div class="tab-content sounds-graphics-content">        
        <div id="region-1-preference-donations-images" class="tab-pane active">
            <div child-scope="" ng-init="fileType = 'campaignAlertBackground';uploadError = null;" class="ng-scope">
         
                
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
                            <div ng-repeat="file in (alertMediaManagerService.customCampaignAlertBackgrounds)">
                                <div class="panel-group media-item images cf" ng-class="{selected: file==module.background}" ng-click="selectCampaignAlertBackground(module, file, region)">
                                    <img class='alert-image-preview' ng-src='{{alertMediaManagerService.getCampaignAlertBackgroundUrl(file,<?=json_encode(WidgetAlertsPreference::FILE_TYPE_CUSTOM) ?>)}}'>
                                    <div class='mediaActions'>
                                        <i class="glyphicon glyphicon-remove" ng-click="removeCampaignAlertBackground(file, region, $event)"></i>
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
