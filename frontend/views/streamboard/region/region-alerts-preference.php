<h4>Preferences</h4>
<div>
    <label>Font style</label>
    <div font-style ng-model="preference.fontStyle"></div>
</div>
<div class="form-group">
    <label>Font size</label>
    <slider ng-model="preference.fontSize" floor="{{MIN_FONT_SIZE}}" ceiling="{{MAX_FONT_SIZE}}" step="1"
            ng-change="regionChanged(region)"></slider>
    <span>{{preference.fontSize}} px</span>
</div>
<div class="form-group">
    <label>Font Color</label>
    <input type="color" ng-model="preference.fontColor" ng-change="regionChanged(region)">
</div>
<div class="form-group">
    <label>Animation duration</label>
    <slider ng-model="preference.animationDuration" floor="1" ceiling="10" step="1"
            ng-change="regionChanged(region)"></slider>
    <span>{{preference.animationDuration}} sec</span>
</div>
