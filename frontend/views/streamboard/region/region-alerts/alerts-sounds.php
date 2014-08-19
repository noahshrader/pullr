<!--main variable - preference -->
<div class="sound-container">
    <div ng-repeat="sound in Pullr.Streamboard.WidgetCampaignBarAlerts.PREDEFINED_SOUNDS" class='media-item' ng-class="{selected: sound==preference.sound}">
        <span>{{sound.substring(0,sound.length-4).replace('-',' ')}}</span>
        <div class='mediaActions'>
            <i class="glyphicon glyphicon-play" ng-click="playSound(sound)"></i>
            <button class="btn btn-default btn-xs" ng-click="selectSound(preference, sound, region)">Select</button>
        </div>
    </div>
</div>