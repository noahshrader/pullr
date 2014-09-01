<?
use frontend\models\streamboard\WidgetAlertsPreference;
?>
<div class='media-item' ng-class="{selected: file==preference.sound && libraryType==preference.soundType}">
    <span>{{file | fileName | replace: '-' : ' '}}</span>
    <div class='mediaActions'>
        <i class="icon-play" ng-click="alertMediaManagerService.playSound(file, libraryType, preference.volume)"></i>
        <i class="icon-remove" ng-show='libraryType == <?= json_encode(WidgetAlertsPreference::FILE_TYPE_CUSTOM) ?> ' ng-click="alertMediaManagerService.removeSound(file)"></i>
        <button class="btn btn-default btn-xs" ng-click="selectSound(preference, file, libraryType, region)">Select</button>
    </div>
</div>
