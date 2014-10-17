<div class="module first">
    <div class="panel-group">
        <h5>Alerts</h5>
        <div class="panel-group text-center">
            <button class="btn btn-primary btn-sm" ng-click="streamService.testData('all', 1, region.regionNumber)">Test alerts</button>
        </div>
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
                ng-options="direction for direction in ANIMATION_DIRECTIONS" data-placeholder="Select one...">
            <option value=""></option>
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