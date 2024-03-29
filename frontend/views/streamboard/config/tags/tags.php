<div class="tags-panel pane">
    <h4 class="panel-head" ng-init="selobj={}; selobj.selectedRegion=1">
        Tags
        <span>
            <a href="javascript:(function(){})()"
               ng-class="{active:selobj.selectedRegion==region.regionNumber}"
               class="tagstabset region{{region.regionNumber}}"
               ng-click="selobj.selectedRegion=region.regionNumber"
               ng-repeat="region in regionsService.regions">
            </a>
        </span>
    </h4>

    <div class="settings-wrap" ng-show="selobj.selectedRegion==region.regionNumber"
         ng-repeat="region in regionsService.regions">
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
            <a class="settingtoggle">Font<i class="mdi-navigation-arrow-drop-down"></i></a>

            <div class="module-settings">
                <div class="panel-group">
                    <h5>Font Style</h5>

                    <div font-style ng-model="region.widgetTags.fontStyle"></div>
                </div>
                <div class="panel-group">
                    <h5>Font Color <input colorpicker="hex" colorpicker-position="left"
                                          colorpicker-with-input="true"
                                          ng-model="region.widgetTags.fontColor"
                                          ng-change="regionChanged(region)"
                                          class="color-choice"
                                          ng-style="{'background-color':region.widgetTags.fontColor}">
                    </h5>
                </div>
                <div class="panel-group">
                    <h5>Font Size <span class="slider-value value">{{region.widgetTags.fontSize}} px</span></h5>
                    <slider ng-model="region.widgetTags.fontSize" floor="{{MIN_FONT_SIZE}}"
                            ceiling="{{MAX_FONT_SIZE}}" step="1"
                            ng-change="regionChanged(region)"></slider>
                </div>
                <div class="panel-group">
                    <h5>Font Weight <span class="slider-value value">{{region.widgetTags.fontWeight}}</span></h5>
                    <slider ng-model="region.widgetTags.fontWeight" floor="{{MIN_FONT_WEIGHT}}"
                            ceiling="{{MAX_FONT_WEIGHT}}"
                            step="100"
                            ng-change="regionChanged(region)"></slider>
                </div>
                <div class="panel-group red">
                    <div class="checkbox">
                        <label ng-class="{on:region.widgetTags.fontUppercase}">
                            <input type="checkbox" ng-model="region.widgetTags.fontUppercase"
                                   ng-change="regionChanged(region)">
                            Uppercase
                        </label>
                    </div>
                </div>
                <div class="panel-group red">
                        <span class="checkbox">
                            <label ng-class="{on:region.widgetTags.textShadow}">
                                <input type="checkbox" ng-model="region.widgetTags.textShadow"
                                       ng-change="regionChanged(region)">
                                Text Shadow
                            </label>
                        </span>
                </div>
            </div>
        </div>
    </div>
</div>