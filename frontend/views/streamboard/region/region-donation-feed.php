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
<div class="form-group">
    <label>Font style</label>
    <select class="form-control select-block" ng-model="widget.fontStyle" ng-change="regionChanged(region)"
            ng-options="font.value as font.name for font in GOOGLE_FONTS">
        <option value="">Select one...</option>
    </select>
</div>
<div class="form-group">
    <label>Font size</label>
    <slider ng-model="widget.fontSize" floor="10" ceiling="30" step="1"
            ng-change="regionChanged(region)"></slider>
    <span>{{widget.fontSize}} px</span>
</div>
<div class="form-group">
    <label>Font color</label>
    <input type="color" ng-model="widget.fontColor" ng-change="regionChanged(region)">
</div>
<br>
<br>
<div>
    <input type="checkbox" ng-model="widget.scrolling" ng-change="regionChanged(region)">
    <label>Scrolling</label>
</div>

<div class="form-group" ng-show="widget.scrolling">
    <label>
        Scroll Speed
    </label>
    <select class="form-control select-block" ng-model="widget.scrollingSpeed" ng-init="WIDGET_SPEEDS = <?= htmlspecialchars(json_encode($speeds)) ?>" ng-change="regionChanged(region)"
            ng-options="speed.value as speed.name for speed in WIDGET_SPEEDS">
        <option value="">Select one...</option>
    </select>
</div>
