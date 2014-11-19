<div class="setting-panel pane">
    <h4 class="panel-head">Tags</h4>
    <div class="tab-pane module form-group">
        <h5>Region</h5>
        <div ng-init="selRegion=1"></div>
        <select ui-select2="{minimumResultsForSearch: -1}" ng-model="selRegion"
                ng-change="regionChanged(region)" data-placeholder="Select one..." class="widget-select">
            <option ng-repeat="region in regionsService.regions" value="{{region.regionNumber}}">
                Region {{region.regionNumber}}
            </option>
        </select>
    </div>

    <div class="settings-wrap" ng-show="selRegion==region.regionNumber" ng-repeat="region in regionsService.regions">
        <div class="module">
            <div class="panel-group">
                <h5>Tags</h5>
                <div class="checkbox">
                    <label ng-class="{on:region.widgetTags.lastFollower}">
                        <input type="checkbox" ng-model="region.widgetTags.lastFollower" ng-change="toggleModule(region)">
                        Last Follower
                    </label>
                </div>

                <div class="checkbox">
                    <label ng-class="{on:region.widgetTags.lastSubscriber}">
                        <input type="checkbox" ng-model="widget.widgetTags.lastSubscriber" ng-change="toggleModule(region)">
                        Last Subscriber
                    </label>
                </div>

                <div class="checkbox">
                    <label ng-class="{on:widget.widgetTags.lastDonor}">
                        <input type="checkbox" ng-model="widget.widgetTags.lastDonor" ng-change="toggleModule(region)">
                        Last Donor
                    </label>
                </div>
                <div class="checkbox">
                    <label ng-class="{on:widget.widgetTags.lastDonorAndDonation}">
                        <input type="checkbox" ng-model="widget.widgetTags.lastDonorAndDonation" ng-change="toggleModule(region)">
                        Last Donor/Donation
                    </label>
                </div>
                <div class="checkbox">
                    <label ng-class="{on:widget.widgetTags.largestDonation}">
                        <input type="checkbox" ng-model="widget.widgetTags.largestDonation" ng-change="toggleModule(region)">
                        Largest Donation
                    </label>
                </div>
                <div class="checkbox">
                    <label ng-class="{on:widget.widgetTags.topDonor}">
                        <input type="checkbox" ng-model="widget.widgetTags.topDonor" ng-change="toggleModule(region)">
                        Top Donor
                    </label>
                </div>
            </div>
        </div>

        <div class="module">
            <div class="panel-group">
                <h5>Font Style</h5>

                <div font-style ng-model="preference.fontStyle"></div>
            </div>
            <div class="panel-group">
                <h5>Font Color <input colorpicker="hex" colorpicker-position="left" colorpicker-with-input="true"
                                      ng-model="region.widgetTags.widgetTagStyle.fontColor" ng-change="regionChanged(region)"
                                      class="color-choice" ng-style="{'background-color':preference.fontColor}"></h5>
            </div>
            <div class="panel-group">
                <h5>Font Size <span class="slider-value value">{{preference.fontSize}} px</span></h5>
                <slider ng-model="region.widgetTags.widgetTagStyle.fontSize" floor="{{MIN_FONT_SIZE}}" ceiling="{{MAX_FONT_SIZE}}" step="1"
                        ng-change="regionChanged(region)"></slider>
            </div>
            <div class="panel-group">
                <h5>Font Weight <span class="slider-value value">{{preference.fontWeight}}</span></h5>
                <slider ng-model="region.widgetTags.widgetTagStyle.fontWeight" floor="{{MIN_FONT_WEIGHT}}" ceiling="{{MAX_FONT_WEIGHT}}"
                        step="100"
                        ng-change="regionChanged(region)"></slider>
            </div>
            <div class="panel-group">
                <div class='checkbox'>
                    <label ng-class="{on:region.widgetTags.widgetTagStyle.fontUppercase}">
                        <input type="checkbox" ng-model="preference.fontUppercase" ng-change="regionChanged(region)">
                        Uppercase
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>