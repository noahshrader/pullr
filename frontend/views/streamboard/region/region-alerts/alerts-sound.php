<?
use frontend\models\streamboard\WidgetAlertsPreference;
?>
<div class='media-item' ng-class="{selected: file==preference.sound && libraryType==preference.soundType}">
    <span>{{file | fileName | replace: '-' : ' '}}</span>
    <div class='mediaActions'>
        <i class="glyphicon glyphicon-play" ng-click="alertMediaManagerService.playSound(file, libraryType)"></i>
        <i class="glyphicon glyphicon-remove" ng-show='libraryType == <?= json_encode(WidgetAlertsPreference::FILE_TYPE_CUSTOM) ?> ' ng-click="alertMediaManagerService.removeSound(file)"></i>
        <button class="btn btn-default btn-xs" ng-click="selectSound(preference, file, libraryType, region)">Select</button>
    </div>
</div>
