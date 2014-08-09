<h4>Preferences</h4>
<div class="form-group">
    <label>Font style</label>
    <input type="text" ng-model="preference.fontStyle" ng-change="regionChanged(region)">
</div>
<div class="form-group">
    <label>Font size</label>
    <slider ng-model="preference.fontSize" floor="10" ceiling="30" step="1"
            ng-change="regionChanged(region)"></slider>
    <span>{{preference.fontSize}} px</span>
</div>
<div class="form-group">
    <label>Font Color</label>
    <input type="color" ng-model="preference.fontColor" ng-change="regionChanged(region)">
</div>
<div class="form-group">
    <label>Animation duration</label>
    <slider ng-model="preference.animationDuration" floor="0" ceiling="10" step="1"
            ng-change="regionChanged(region)"></slider>
    <span>{{preference.animationDuration}} sec</span>
</div>
