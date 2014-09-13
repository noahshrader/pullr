<?
use frontend\models\streamboard\WidgetDonationFeed;

$speeds = [];
foreach (WidgetDonationFeed::$SCROLL_SPEEDS as $speed){
   $speeds[] = ['name' => $speed, 'value' => $speed];
}
?>
<div class="module">
    <div class="form-group">
        <textarea ng-model="widget.noDonationMessage" ng-change="regionChanged(region)" placeholder="No donations message"></textarea>
    </div>
</div>
<div class="module">
    <div class="form-group">
        <h5>Font style</h5>
        <div font-style ng-model="widget.fontStyle"></div>
    </div>
    <div class="form-group">
        <h5>Font size <span class="slider-value value">{{widget.fontSize}} px</span></h5>
        <slider ng-model="widget.fontSize" floor="{{MIN_FONT_SIZE}}" ceiling="{{MAX_FONT_SIZE}}" step="1"
                ng-change="regionChanged(region)"></slider>
    </div>
    <div class="form-group">
        <h5>Font color <input colorpicker="rgba" colorpicker-position="left" type="text" ng-model="widget.fontColor" ng-change="regionChanged(region)" class="color-choice" ng-style="{'background-color':widget.fontColor}"></h5>
    </div>
</div>
<div class="module">
    <div class="form-group checkbox cf">
        <label ng-class="{on:widget.scrolling}">
            <input type="checkbox" ng-model="widget.scrolling" ng-change="regionChanged(region)">
            Scrolling
        </label>
        <div ng-show="widget.scrolling" class="scroll-options">
            <h5>Speed</h5>
            <select ui-select2="{minimumResultsForSearch: -1}" ng-model="widget.scrollSpeed" ng-init="WIDGET_SPEEDS = <?= htmlspecialchars(json_encode($speeds)) ?>" ng-change="regionChanged(region)"
                    ng-options="speed.value as speed.name for speed in WIDGET_SPEEDS" data-placeholder="Select one...">
                <option value=""></option>
            </select>
        </div>
    </div>
</div>