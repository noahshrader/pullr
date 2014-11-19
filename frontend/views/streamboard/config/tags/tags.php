<div class="tab-content tags-panel pane tags-tabcontent">
    <h4 class="panel-head" ng-init="selobj={}; selobj.selectedRegion=1">
        Tags&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="javascript:(function(){})()"
           ng-class="{actived:selobj.selectedRegion==region.regionNumber}"
           class="tagstabset region{{region.regionNumber}}"
           ng-click="selobj.selectedRegion=region.regionNumber"
           ng-repeat="region in regionsService.regions">
            &nbsp;
        </a>
    </h4>

    <div class="settings-wrap" ng-show="selobj.selectedRegion==region.regionNumber"
         ng-repeat="region in regionsService.regions">
        <div ng-init="region.widgetTags.userId=1">
            <div class="module">
                <div class="panel-group">
                    <h5>Tags</h5>

                    <div class="checkbox">
                        <label ng-class="{on:region.widgetTags.lastFollower}">
                            <input type="checkbox" ng-model="region.widgetTags.lastFollower"
                                   ng-change="toggleModule(region)">
                            Last Follower
                        </label>
                    </div>

                    <div class="checkbox">
                        <label ng-class="{on:region.widgetTags.lastSubscriber}">
                            <input type="checkbox" ng-model="region.widgetTags.lastSubscriber"
                                   ng-change="toggleModule(region)">
                            Last Subscriber
                        </label>
                    </div>

                    <div class="checkbox">
                        <label ng-class="{on:region.widgetTags.lastDonor}">
                            <input type="checkbox" ng-model="region.widgetTags.lastDonor"
                                   ng-change="toggleModule(region)">
                            Last Donor
                        </label>
                    </div>
                    <div class="checkbox">
                        <label ng-class="{on:region.widgetTags.lastDonorAndDonation}">
                            <input type="checkbox" ng-model="region.widgetTags.lastDonorAndDonation"
                                   ng-change="toggleModule(region)">
                            Last Donor/Donation
                        </label>
                    </div>
                    <div class="checkbox">
                        <label ng-class="{on:region.widgetTags.largestDonation}">
                            <input type="checkbox" ng-model="region.widgetTags.largestDonation"
                                   ng-change="toggleModule(region)">
                            Largest Donation
                        </label>
                    </div>
                    <div class="checkbox">
                        <label ng-class="{on:region.widgetTags.topDonor}">
                            <input type="checkbox" ng-model="region.widgetTags.topDonor"
                                   ng-change="toggleModule(region)">
                            Top Donor
                        </label>
                    </div>
                </div>
            </div>

            <div class="module">
                <div class="panel-group">
                    <h5>Font Style</h5>

                    <div font-style ng-model="region.widgetTags.widgetTagStyle.fontStyle"></div>
                </div>
                <div class="panel-group">
                    <h5>Font Color <input colorpicker="hex" colorpicker-position="left"
                                          colorpicker-with-input="true"
                                          ng-model="region.widgetTags.widgetTagStyle.fontColor"
                                          ng-change="regionChanged(region)"
                                          class="color-choice"
                                          ng-style="{'background-color':region.widgetTags.widgetTagStyle.fontColor}">
                    </h5>
                </div>
                <div class="panel-group">
                    <h5>Font Size <span class="slider-value value">{{preference.fontSize}} px</span></h5>
                    <slider ng-model="region.widgetTags.widgetTagStyle.fontSize" floor="{{MIN_FONT_SIZE}}"
                            ceiling="{{MAX_FONT_SIZE}}" step="1"
                            ng-change="regionChanged(region)"></slider>
                </div>
                <div class="panel-group">
                    <h5>Font Weight <span class="slider-value value">{{preference.fontWeight}}</span></h5>
                    <slider ng-model="region.widgetTags.widgetTagStyle.fontWeight" floor="{{MIN_FONT_WEIGHT}}"
                            ceiling="{{MAX_FONT_WEIGHT}}"
                            step="100"
                            ng-change="regionChanged(region)"></slider>
                </div>
                <div class="panel-group">
                    <div class='checkbox'>
                        <label ng-class="{on:region.widgetTags.widgetTagStyle.fontUppercase}">
                            <input type="checkbox" ng-model="region.widgetTags.widgetTagStyle.fontUppercase"
                                   ng-change="regionChanged(region)">
                            Uppercase
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>