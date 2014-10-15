<div class="module">
    <div class="form-group">
        <h5>Campaign</h5>
        <select ui-select2="{minimumResultsForSearch: -1}" ng-model="widget.campaignId" ng-change="regionChanged(region)"
                data-placeholder="Select a campaign...">
            <option value=""></option>
            <option ng-repeat='campaign in campaignsService.campaigns' value="{{campaign.id}}">
                {{campaign.name}}
            </option>
        </select>
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
        <h5>Font Color <input colorpicker="hex" colorpicker-position="left" colorpicker-with-input="true" ng-model="widget.fontColor" ng-change="regionChanged(region)" class="color-choice" ng-style="{'background-color':widget.fontColor}"></h5>
    </div>
    <div class="form-group">
        <h5>Background Color <input colorpicker="hex" colorpicker-position="left" colorpicker-with-input="true" ng-model="widget.backgroundColor" ng-change="regionChanged(region)" class="color-choice" ng-style="{'background-color':widget.backgroundColor}"></h5>
    </div>
</div>
<div class="module last">
    <div class="form-group">
        <h5>Modules</h5>
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
</div>
<div class="right-side-footer" ng-show='widget.alertsEnable || widget.messagesEnable || widget.timerEnable'>
    <ul class="panel-nav paneltoggle">
        <li ng-if="widget.alertsEnable"><a data-panel="alertsModule" class="icon-notify"></a></li>
        <li ng-if="widget.messagesEnable"><a data-panel="messagesModule" class="icon-bubble4"></a></li>
        <li ng-if="widget.timerEnable"><a data-panel="timerModule" class="icon-timer"></a></li>
    </ul>

    <div class="alertsModule_panel slidepanel pane" child-scope>
        <div ng-init="module = widget.alertsModule">
            <h4 class="panel-title">Alerts Settings</h4>
            <?= $this->render('module-alerts') ?>
        </div>
    </div>
    <div class="messagesModule_panel slidepanel pane" child-scope>
        <div ng-init="module = widget.messagesModule">
            <h4 class="panel-title">Message Settings</h4>
            <?= $this->render('module-messages') ?>
        </div>
    </div>
    <div class="timerModule_panel slidepanel pane" child-scope>
        <div ng-init="module = widget.timerModule">
            <h4 class="panel-title">Timer Settings</h4>
            <?= $this->render('module-timer') ?>
        </div>
    </div>
</div>
