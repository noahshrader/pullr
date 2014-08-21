<?
use frontend\models\streamboard\WidgetAlertsPreference;
?>
<div class='media-item' ng-class="{selected: sound==preference.sound && soundType==preference.soundType}">
    <span>{{sound.substring(0,sound.length-4) | replace: '-' : ' '}}</span>
    <div class='mediaActions'>
        <i class="glyphicon glyphicon-play" ng-click="alertMediaManagerService.playSound(sound, soundType)"></i>
        <i class="glyphicon glyphicon-remove" ng-show='soundType == <?= json_encode(WidgetAlertsPreference::FILE_TYPE_CUSTOM) ?> ' ng-click="alertMediaManagerService.removeSound(sound)"></i>
        <button class="btn btn-default btn-xs" ng-click="selectSound(preference, sound, soundType, region)">Select</button>
    </div>
</div>
