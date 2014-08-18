<h4>Alerts</h4>
<div class="form-group">
    <label>Which type of alerts do you want to include?</label>

    <div>
        <input type="checkbox" ng-model="module.includeFollowers" ng-change="regionChanged(region)">
        <label>Followers</label>
    </div>
    <div>
        <input type="checkbox" ng-model="module.includeSubscribers" ng-change="regionChanged(region)">
        <label>Subscribers</label>
    </div>
    <div>
        <input type="checkbox" ng-model="module.includeDonations" ng-change="regionChanged(region)">
        <label>Donations</label>
    </div>
</div>
<h5>Preferences</h5>
<div>
    <label>Font style</label>
    <div font-style ng-model="module.fontStyle"></div>
</div>
<div class="form-group">
    <label>Font size</label>
    <slider ng-model="module.fontSize" floor="{{MIN_FONT_SIZE}}" ceiling="{{MAX_FONT_SIZE}}" step="1"
            ng-change="regionChanged(region)"></slider>
    <span>{{module.fontSize}} px</span>
</div>
<div class="form-group">
    <label>Font Color</label>
    <input type="color" ng-model="module.fontColor" ng-change="regionChanged(region)">
</div>
<div class="form-group">
    <label>Background Color</label>
    <input type="color" ng-model="module.backgroundColor" ng-change="regionChanged(region)">
</div>
<div class="form-group" ng-init="ANIMATION_DIRECTIONS=['Fade','Slide in from top','Slide in from bottom']">
    <label>Animation Direction</label>
    <select selectpicker ng-model="module.animationDirection" ng-change="regionChanged(region)"
            ng-options="direction for direction in ANIMATION_DIRECTIONS">
        <option value="">Select one...</option>
    </select>
</div>
<div class="form-group">
    <label>Animation duration</label>
    <slider ng-model="module.animationDuration" floor="1" ceiling="10" step="1"
            ng-change="regionChanged(region)"></slider>
    <span>{{module.animationDuration}} sec</span>
</div>
<div class="form-group">
    <label>Animation duration</label>
    <slider ng-model="module.animationDelay" floor="0" ceiling="30" step="1"
            ng-change="regionChanged(region)"></slider>
    <span>{{module.animationDuration}} sec</span>
</div>
