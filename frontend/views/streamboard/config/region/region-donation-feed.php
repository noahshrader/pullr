<?
use frontend\models\streamboard\WidgetDonationFeed;

$speeds = [];
foreach (WidgetDonationFeed::$SCROLL_SPEEDS as $speed){
   $speeds[] = ['name' => $speed, 'value' => $speed];
}
?>
<div class="form-group">
    <label>No donations message</label>
    <textarea ng-model="widget.noDonationMessage" ng-change="regionChanged(region)"></textarea>
</div>
<div>
    <label>Font style</label>
    <div font-style ng-model="widget.fontStyle"></div>
</div>
<div class="form-group">
    <label>Font size</label>
    <slider ng-model="widget.fontSize" floor="{{MIN_FONT_SIZE}}" ceiling="{{MAX_FONT_SIZE}}" step="1"
            ng-change="regionChanged(region)"></slider>
    <span>{{widget.fontSize}} px</span>
</div>
<div class="form-group">
    <label>Font color</label>
    <input colorpicker="rgba" colorpicker-position="left" type="text" ng-model="widget.fontColor" ng-change="regionChanged(region)" class="color-choice" ng-style="{'background-color':widget.fontColor}">
</div>
<br>
<br>
<div class="checkbox">
    <label ng-class="{on:widget.scrolling}">
        <input type="checkbox" ng-model="widget.scrolling" ng-change="regionChanged(region)">
        Scrolling
    </label>
</div>

<div ng-show="widget.scrolling">
    <label>
        Scroll Speed
    </label>
    <select selectpicker ng-model="widget.scrollSpeed" ng-init="WIDGET_SPEEDS = <?= htmlspecialchars(json_encode($speeds)) ?>" ng-change="regionChanged(region)"
            ng-options="speed.value as speed.name for speed in WIDGET_SPEEDS">
        <option value="">Select one...</option>
    </select>
</div>
