<select ui-select2="{minimumResultsForSearch: -1}" ng-model="widget.campaignId" ng-change="regionChanged(region)"
        ng-options="campaign.id as campaign.name for (key, campaign) in campaignsService.campaigns" data-placeholder="Select a campaign...">
    <option value=""></option>
</select>
<h4>Preferences</h4>
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
    <label>Font Color</label>
    <input colorpicker="rgba" colorpicker-position="left" type="text" ng-model="widget.fontColor" ng-change="regionChanged(region)" class="color-choice" ng-style="{'background-color':widget.fontColor}">
</div>
<div class="form-group">
    <label>Background Color</label>
    <input colorpicker="rgba" colorpicker-position="left" type="text" ng-model="widget.backgroundColor" ng-change="regionChanged(region)" class="color-choice" ng-style="{'background-color':widget.backgroundColor}">
</div>
<h4>Available modules</h4>
<div class="form-group">
    <div class="checkbox">
        <label ng-class="{on:widget.alertsEnable}">
            <input type="checkbox" ng-model="widget.alertsEnable" ng-change="regionChanged(region)">
            Alerts
        </label>
    </div>
    <div class="checkbox">
        <label ng-class="{on:widget.messagesEnable}">
            <input type="checkbox" ng-model="widget.messagesEnable" ng-change="regionChanged(region)">
            Rotating Messages
        </label>
    </div>
    <div class="checkbox">
        <label ng-class="{on:widget.timerEnable}">
            <input type="checkbox" ng-model="widget.timerEnable" ng-change="regionChanged(region)">
            Timer
        </label>
    </div>
    <div class="checkbox">
        <label ng-class="{on:widget.progressBarEnable}">
            <input type="checkbox" ng-model="widget.progressBarEnable" ng-change="regionChanged(region)">
            Progress Bar
        </label>
    </div>
</div>
<div class="right-side-footer">
    <ul class="bottom-panel-nav paneltoggle">
        <li ng-if="widget.alertsEnable"><a data-panel="alertsModule">Alerts</a></li>
        <li ng-if="widget.messagesEnable"><a data-panel="messagesModule">Messages</a></li>
        <li ng-if="widget.timerEnable"><a data-panel="timerModule">Timer</a></li>
    </ul>

    <div class="alertsModule_panel slidepanel" child-scope>
        <div ng-init="module = widget.alertsModule">
            <?= $this->render('module-alerts') ?>
        </div>
    </div>
    <div class="messagesModule_panel slidepanel" child-scope>
        <div ng-init="module = widget.messagesModule">
            <?= $this->render('module-messages') ?>
        </div>
    </div>
    <div class="timerModule_panel slidepanel" child-scope>
        <div ng-init="module = widget.timerModule">
            <?= $this->render('module-timer') ?>
        </div>
    </div>
</div>
